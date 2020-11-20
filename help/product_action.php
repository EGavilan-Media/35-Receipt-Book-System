<?php
include "connection.php";
session_start();
$output = '';

if(isset($_POST["action"])){

  // Fetch products
  if($_POST["action"] == "product_fetch"){

    // Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $row_per_page = $_POST['length'];
    $column_index = $_POST['order'][0]['column'];
    $column_name = $_POST['columns'][$column_index]['data'];
    $column_sort_order = $_POST['order'][0]['dir'];
    $search_value = $_POST['search']['value'];

    // Search
    $search_query = " ";
    if($search_value != ''){
      $search_query = " and (product_id LIKE '%".$search_value."%'
                            OR product_name LIKE '%".$search_value."%'
                            OR product_quantity LIKE '%".$search_value."%'
                            OR product_price LIKE '%".$search_value."%'
                            OR product_status LIKE '%".$search_value."%' ) ";
    }

    // Total number of records without filtering
    $sql_product = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_products");
    $records = mysqli_fetch_assoc($sql_product);
    $total_records = $records['allcount'];

    // Total number of records with filtering
    $sql_product = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_products WHERE 1 ".$search_query);
    $records = mysqli_fetch_assoc($sql_product);
    $total_record_with_filter = $records['allcount'];

    // Fetch records
    $product_query="SELECT product.product_id,
                      product.product_name,
                      product.product_price,
                      product.product_quantity,
                      category.category_name,
                      brand.brand_name,
                      supplier.supplier_full_name,
                      product.product_created_at,
                      product.product_status
                    FROM tbl_products AS product
                    INNER JOIN tbl_categories AS category
                    ON product.category_id=category.category_id 
                    INNER JOIN tbl_brands AS brand
                    ON product.brand_id=brand.brand_id
                    INNER JOIN tbl_suppliers AS supplier
                    ON product.supplier_id=supplier.supplier_id 
                    WHERE 1 ".$search_query." ORDER BY ".$column_name." 
                    ".$column_sort_order." LIMIT ".$row.",".$row_per_page;

    $product_records = mysqli_query($conn, $product_query);
    $data = array();

    while ($row = mysqli_fetch_assoc($product_records)){

      $status = '';
      if($row["product_status"] == "Active"){
        $status = '<label class="badge badge-success">Active</label>';
      }else if($row["product_status"] == "Inactive"){
        $status = '<label class="badge badge-danger">Inactive</label>';
      }

      $data[] = array(
        "product_id"              =>  $row['product_id'],
        "product_name"            =>  $row['product_name'],
        "product_price"           =>  $row['product_price'],
        "product_quantity"        =>  $row['product_quantity'],
        "category_name"           =>  $row['category_name'],
        "brand_name"              =>  $row['brand_name'],
        "supplier_full_name"      =>  $row['supplier_full_name'],
        "product_created_at"      =>  $row['product_created_at'],
        "product_status"          =>  $status,
        "action"                  =>  '<button type="button" class="btn btn-secondary view_product btn-sm" data-toggle="modal" data-target="#view_modal" id="'.$row['product_id'].'"><i class="fas fa-eye"></i></button>
                                       <button type="button" class="btn btn-success update_product btn-sm" id="'.$row['product_id'].'"><i class="fas fa-edit"></i></button>'
      );
    }

    $response = array(
      "draw"                  => intval($draw),
      "iTotalRecords"         => $total_records,
      "iTotalDisplayRecords"  => $total_record_with_filter,
      "aaData"                => $data
    );

    echo json_encode($response);

  }

  // Add customer
  if($_POST["action"] == "add_product"){

    $sql = "INSERT INTO tbl_products (product_created_by,
                                  supplier_id,
                                  category_id,
                                  brand_id,
                                  product_name,
                                  product_description,
                                  product_price,
                                  product_quantity,
                                  product_status,
                                  product_created_at) 
                          VALUES('".$_SESSION["user_id"]."',
                                '".$_POST["supplier_id"]."',
                                '".$_POST["category_id"]."',
                                '".$_POST["brand_id"]."',
                                '".$_POST["name"]."',
                                '".$_POST["description"]."',
                                '".$_POST["price"]."',
                                '".$_POST["quantity"]."',
                                '".$_POST["status"]."',
                                NOW())";

    if(mysqli_query($conn, $sql)){
      $output = array(
        'status'          => 'success',
        'message'         => ' New product has been successfully added.'
      );
    }

    echo json_encode($output);

  }

  // Single fetch
  if($_POST["action"] == "single_fetch"){

    $sql="SELECT product.product_id,
                  category.category_name,
                  supplier.supplier_full_name,
                  brand.brand_name,
                  product.product_name,
                  product.product_description,
                  product.product_price,
                  product.product_quantity,
                  product.product_status,
                  user.user_full_name,
                  product.product_created_at,
                  user_2.user_full_name,
                  product.supplier_id,
                  product.category_id,
                  product.brand_id,
                  product.product_updated_at
                FROM tbl_products AS product
                INNER JOIN tbl_categories AS category
                ON product.category_id=category.category_id 
                INNER JOIN tbl_brands AS brand
                ON product.brand_id=brand.brand_id
                INNER JOIN tbl_suppliers AS supplier
                ON product.supplier_id=supplier.supplier_id 
                INNER JOIN tbl_users AS user
                ON product.product_created_by=user.user_id 
                LEFT JOIN tbl_users AS user_2
                ON product.product_last_update_by=user_2.user_id 
                WHERE product.product_id = '".$_POST["product_id"]."'";

      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_row($result);

      $output = array(
            "product_id"                =>	$row[0],
            "category_name"             => $row[1],
            "supplier_full_name"        => $row[2],
            "brand_name"                => $row[3],
            "product_name"              => $row[4],
            "product_description"       => $row[5],
            "product_price"             => $row[6],
            "product_quantity"          => $row[7],
            "product_status"            => $row[8],
            "product_created_by"        => $row[9],
            "product_created_at"        => $row[10],
            "product_last_update_by"    => $row[11],
            "supplier_id"               => $row[12],
            "category_id"               => $row[13],
            "brand_id"                  => $row[14],
            "product_updated_at"        => $row[15]
      );

    echo json_encode($output);

  }

  // Update Product
  if($_POST["action"] == "update_product"){

    $sql = "UPDATE tbl_products SET product_last_update_by = '".$_SESSION["user_id"]."',
                                  supplier_id = '".$_POST["supplier_id"]."',
                                  category_id = '".$_POST["category_id"]."',
                                  brand_id = '".$_POST["brand_id"]."',
                                  product_name = '".$_POST["name"]."',
                                  product_description= '".$_POST["description"]."',
                                  product_price = '".$_POST["price"]."',
                                  product_quantity = '".$_POST["quantity"]."',
                                  product_status = '".$_POST["status"]."',
                                  product_updated_at = NOW()
                                WHERE product_id = '".$_POST["product_id"]."'";

    if(mysqli_query($conn, $sql)){
      $output = array(
        'status'          => 'success',
        'message'         => ' Product has been successfully updated.'
      );
    }

    echo json_encode($output);

  }
}
?>