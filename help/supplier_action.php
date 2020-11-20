<?php
include "connection.php";
session_start();
$output = '';

if(isset($_POST["action"])){

  // Fetch supplier
  if($_POST["action"] == "supplier_fetch"){

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
      $search_query = " and (supplier_id LIKE '%".$search_value."%'
                            OR supplier_full_name LIKE '%".$search_value."%'
                            OR supplier_email LIKE '%".$search_value."%'
                            OR supplier_telephone LIKE '%".$search_value."%'
                            OR supplier_status LIKE '%".$search_value."%' ) ";
    }

    // Total number of records without filtering
    $sql_supplier = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_suppliers");
    $records = mysqli_fetch_assoc($sql_supplier);
    $total_records = $records['allcount'];

    // Total number of records with filtering
    $sql_supplier = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_suppliers WHERE 1 ".$search_query);
    $records = mysqli_fetch_assoc($sql_supplier);
    $total_record_with_filter = $records['allcount'];

    // Fetch records
    $supplier_query = "SELECT * FROM tbl_suppliers WHERE 1 ".$search_query." ORDER BY ".$column_name." ".$column_sort_order." LIMIT ".$row.",".$row_per_page;

    $supplier_records = mysqli_query($conn, $supplier_query);
    $data = array();

    while ($row = mysqli_fetch_assoc($supplier_records)){

      $status = '';
      if($row["supplier_status"] == "Active"){
        $status = '<label class="badge badge-success">Active</label>';
      }else if($row["supplier_status"] == "Inactive"){
        $status = '<label class="badge badge-danger">Inactive</label>';
      }

      $data[] = array(
        "supplier_id"              =>  $row['supplier_id'],
        "supplier_full_name"       =>  $row['supplier_full_name'],
        "supplier_email"           =>  $row['supplier_email'],
        "supplier_telephone"       =>  $row['supplier_telephone'],
        "supplier_created_at"      =>  $row['supplier_created_at'],
        "supplier_status"          =>  $status,
        "action"                   =>  '<button type="button" class="btn btn-secondary view_supplier btn-sm" data-toggle="modal" data-target="#view_modal" id="'.$row['supplier_id'].'"><i class="fas fa-eye"></i></button>
                                        <button type="button" class="btn btn-success update_supplier btn-sm" id="'.$row['supplier_id'].'"><i class="fas fa-edit"></i></button>'
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

  // Add supplier
  if($_POST["action"] == "add_supplier"){

    $sql = "INSERT INTO tbl_suppliers (supplier_created_by,
                                  supplier_full_name,
                                  supplier_email,
                                  supplier_address,
                                  supplier_telephone,
                                  supplier_status,
                                  supplier_created_at)
                          VALUES('".$_SESSION["user_id"]."',
                                '".$_POST["full_name"]."',
                                '".$_POST["email"]."',
                                '".$_POST["address"]."',
                                '".$_POST["telephone"]."',
                                '".$_POST["status"]."',
                                NOW())";

    if(mysqli_query($conn, $sql)){
      $output = array(
        'status'          => 'success',
        'message'         => ' New supplier has been successfully added.'
      );
    }  

    echo json_encode($output);

  }

  // Single fetch
  if($_POST["action"] == "single_fetch"){

    $sql = "SELECT cust.supplier_id,
              cust.supplier_full_name,
              cust.supplier_email,
              cust.supplier_address,
              cust.supplier_telephone,
              cust.supplier_status,
              user_1.user_full_name,
              cust.supplier_created_at,
              cust.supplier_updated_at,
              user_2.user_full_name
            FROM tbl_suppliers AS cust 
            INNER JOIN tbl_users AS user_1
            ON cust.supplier_created_by = user_1.user_id
            LEFT JOIN tbl_users AS user_2
            ON cust.supplier_last_update_by = user_2.user_id
            WHERE cust.supplier_id = '".$_POST["supplier_id"]."'";

      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_row($result);

      $output = array(
            "supplier_id"		           =>	$row[0],
            "supplier_full_name"		   =>	$row[1],
            "supplier_email"		       => $row[2],
            "supplier_address"		     => $row[3],
            "supplier_telephone"		   => $row[4],
            "supplier_status"		       => $row[5],
            "supplier_created_by"      => $row[6],
            "supplier_created_at"      => $row[7],
            "supplier_updated_at"      => $row[8],
            "supplier_last_update_by"  => $row[9]
      );

    echo json_encode($output);

  }

  // Update supplier
  if($_POST["action"] == "update_supplier"){

    $sql = "UPDATE tbl_suppliers SET supplier_last_update_by = '".$_SESSION["user_id"]."',
                                  supplier_full_name = '".$_POST["full_name"]."',
                                  supplier_email = '".$_POST["email"]."',
                                  supplier_address = '".$_POST["address"]."',
                                  supplier_telephone = '".$_POST["telephone"]."',
                                  supplier_status = '".$_POST["status"]."',
                                  supplier_updated_at = NOW()
                                WHERE supplier_id = '".$_POST["supplier_id"]."'";

    if(mysqli_query($conn, $sql)){
      $output = array(
        'status'          => 'success',
        'message'         => ' Supplier profile has been successfully updated.'
      );
    }
  
    echo json_encode($output);

  }
}
?>