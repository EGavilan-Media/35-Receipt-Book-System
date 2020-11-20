<?php
include "connection.php";
session_start();
$output = '';

if(isset($_POST["action"])){

  // Fetch all brand
  if($_POST["action"] == "brand_fetch"){

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
      $search_query = " and (brand_id LIKE '%".$search_value."%'
                            OR brand_name LIKE '%".$search_value."%'
                            OR brand_created_at LIKE '%".$search_value."%'
                            OR brand_status LIKE '%".$search_value."%' ) ";
    }

    // Total number of records without filtering
    $sql_brand = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_brands");
    $records = mysqli_fetch_assoc($sql_brand);
    $total_records = $records['allcount'];

    // Total number of records with filtering
    $sql_brand = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_brands WHERE 1 ".$search_query);
    $records = mysqli_fetch_assoc($sql_brand);
    $total_record_with_filter = $records['allcount'];

    // Fetch records
    $brand_query = "SELECT * FROM tbl_brands WHERE 1 ".$search_query." ORDER BY ".$column_name." ".$column_sort_order." LIMIT ".$row.",".$row_per_page;

    $brand_records = mysqli_query($conn, $brand_query);
    $data = array();

    while ($row = mysqli_fetch_assoc($brand_records)){

      $status = '';
      if($row["brand_status"] == "Active")
      {
        $status = '<label class="badge badge-success">Active</label>';
      }

      if($row["brand_status"] == "Inactive")
      {
        $status = '<label class="badge badge-danger">Inactive</label>';
      }

      $data[] = array(
        "brand_id"              =>  $row['brand_id'],
        "brand_name"            =>  $row['brand_name'],
        "brand_created_at"      =>  $row['brand_created_at'],
        "brand_status"          =>  $status,
        "action"                   =>  '<button type="button" class="btn btn-secondary view_brand btn-sm" data-toggle="modal" data-target="#view_modal" id="'.$row['brand_id'].'"><i class="fas fa-eye"></i></button>
                                        <button type="button" class="btn btn-success update_brand btn-sm" id="'.$row['brand_id'].'"><i class="fas fa-edit"></i></button>'
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

  // Add brand
  if($_POST["action"] == "add_brand"){

    // Check if brand already exists.
    $sql = "SELECT * FROM tbl_brands WHERE brand_name = '".$_POST["name"]."'";
    $result = mysqli_query($conn, $sql);
    $check_rows = mysqli_num_rows($result);

    if($check_rows > 0) {
      $output = array(
        'status'          =>	'error',
      );
    } else {

      $sql = "INSERT INTO tbl_brands (brand_created_by,
                                    brand_name,
                                    brand_status,
                                    brand_created_at) 
                            VALUES('".$_SESSION["user_id"]."',
                                  '".$_POST["name"]."',
                                  '".$_POST["status"]."',
                                  NOW())";

      if(mysqli_query($conn, $sql)){
        $output = array(
          'status'          => 'success',
          'message'         => ' New brand has been successfully added.'
        );
      }
    }

    echo json_encode($output);

  }

  // Single fetch
  if($_POST["action"] == "single_fetch"){

    $sql = "SELECT cate.brand_id,
              cate.brand_name,
              cate.brand_status,
              user_1.user_full_name,
              cate.brand_created_at,
              cate.brand_updated_at,
              user_2.user_full_name
            FROM tbl_brands AS cate 
            INNER JOIN tbl_users AS user_1
            ON cate.brand_created_by = user_1.user_id 
            LEFT JOIN tbl_users AS user_2
            ON cate.brand_last_update_by = user_2.user_id
            WHERE cate.brand_id = '".$_POST["brand_id"]."'";

      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_row($result);

      $output = array(
            "brand_id"		          =>	$row[0],
            "brand_name"		        =>	$row[1],
            "brand_status"		      => $row[2],
            "brand_created_by"      => $row[3],
            "brand_created_at"      => $row[4],
            "brand_updated_at"      => $row[5],
            "brand_last_update_by"  => $row[6]
      );

    echo json_encode($output);

  }

  // Update brand
  if($_POST["action"] == "update_brand"){

    // Check if brand already exists.
    $sql = "SELECT * FROM tbl_brands WHERE brand_name = '".$_POST["name"]."' AND brand_id != '".$_POST["brand_id"]."'";
    $result = mysqli_query($conn, $sql);
    $check_rows = mysqli_num_rows($result);

    if($check_rows > 0) {
      $output = array(
        'status'          =>	'error',
      );
    } else {

      $sql = "UPDATE tbl_brands SET brand_last_update_by = '".$_SESSION["user_id"]."',
                                          brand_name = '".$_POST["name"]."',
                                          brand_status = '".$_POST["status"]."',
                                          brand_updated_at = NOW()
                                        WHERE brand_id = '".$_POST["brand_id"]."'";

      if(mysqli_query($conn, $sql)){
        $output = array(
          'status'          => 'success',
          'message'         => ' Brand has been successfully updated.'
        );
      }
    }

    echo json_encode($output);

  }
}
?>