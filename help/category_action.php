<?php
include "connection.php";
session_start();
$output = '';

if(isset($_POST["action"])){

  // Fetch all category
  if($_POST["action"] == "category_fetch"){

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
      $search_query = " and (category_id LIKE '%".$search_value."%'
                            OR category_name LIKE '%".$search_value."%'
                            OR category_status LIKE '%".$search_value."%' ) ";
    }

    // Total number of records without filtering
    $sql_category = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_categories");
    $records = mysqli_fetch_assoc($sql_category);
    $total_records = $records['allcount'];

    // Total number of records with filtering
    $sql_category = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_categories WHERE 1 ".$search_query);
    $records = mysqli_fetch_assoc($sql_category);
    $total_record_with_filter = $records['allcount'];

    // Fetch records
    $category_query = "SELECT * FROM tbl_categories WHERE 1 ".$search_query." ORDER BY ".$column_name." ".$column_sort_order." LIMIT ".$row.",".$row_per_page;

    $category_records = mysqli_query($conn, $category_query);
    $data = array();

    while ($row = mysqli_fetch_assoc($category_records)){

      $status = '';
      if($row["category_status"] == "Active"){
        $status = '<label class="badge badge-success">Active</label>';
      }else if($row["category_status"] == "Inactive"){
        $status = '<label class="badge badge-danger">Inactive</label>';
      }

      $data[] = array(
        "category_id"              =>  $row['category_id'],
        "category_name"            =>  $row['category_name'],
        "category_created_at"      =>  $row['category_created_at'],
        "category_status"          =>  $status,
        "action"                   =>  '<button type="button" class="btn btn-secondary view_category btn-sm" data-toggle="modal" data-target="#view_modal" id="'.$row['category_id'].'"><i class="fas fa-eye"></i></button>
                                        <button type="button" class="btn btn-success update_category btn-sm" id="'.$row['category_id'].'"><i class="fas fa-edit"></i></button>'
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

  // Add category
  if($_POST["action"] == "add_category"){

    // Check if category already exists.
    $sql = "SELECT * FROM tbl_categories WHERE category_name = '".$_POST["name"]."'";
    $result = mysqli_query($conn, $sql);
    $check_rows = mysqli_num_rows($result);

    if($check_rows > 0) {
      $output = array(
        'status'          =>	'error',
      );
    } else {

      $sql = "INSERT INTO tbl_categories (category_created_by,
                                    category_name,
                                    category_status,
                                    category_created_at) 
                            VALUES('".$_SESSION["user_id"]."',
                                  '".$_POST["name"]."',
                                  '".$_POST["status"]."',
                                  NOW())";

      if(mysqli_query($conn, $sql)){
        $output = array(
          'status'          => 'success',
          'message'         => ' New category has been successfully added.'
        );
      }
    }

    echo json_encode($output);

  }

  // Single fetch
  if($_POST["action"] == "single_fetch"){

    $sql = "SELECT cate.category_id,
              cate.category_name,
              cate.category_status,
              user_1.user_full_name,
              cate.category_created_at,
              cate.category_updated_at,
              user_2.user_full_name
            FROM tbl_categories AS cate 
            INNER JOIN tbl_users AS user_1
            ON cate.category_created_by = user_1.user_id 
            LEFT JOIN tbl_users AS user_2
            ON cate.category_last_update_by = user_2.user_id
            WHERE cate.category_id = '".$_POST["category_id"]."'";

      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_row($result);

      $output = array(
            "category_id"		           =>	$row[0],
            "category_name"		        =>	$row[1],
            "category_status"		       => $row[2],
            "category_created_by"      => $row[3],
            "category_created_at"      => $row[4],
            "category_updated_at"      => $row[5],
            "category_last_update_by"  => $row[6]
      );

    echo json_encode($output);

  }

  // Update category
  if($_POST["action"] == "update_category"){

    // Check if category already exists.
    $sql = "SELECT * FROM tbl_categories WHERE category_name = '".$_POST["name"]."' AND category_id != '".$_POST["category_id"]."'";
    $result = mysqli_query($conn, $sql);
    $check_rows = mysqli_num_rows($result);

    if($check_rows > 0) {
      $output = array(
        'status'          =>	'error',
      );
    } else {

      $sql = "UPDATE tbl_categories SET category_last_update_by = '".$_SESSION["user_id"]."',
                                          category_name = '".$_POST["name"]."',
                                          category_status = '".$_POST["status"]."',
                                          category_updated_at = NOW()
                                        WHERE category_id = '".$_POST["category_id"]."'";

      if(mysqli_query($conn, $sql)){
        $output = array(
          'status'          => 'success',
          'message'         => ' Category has been successfully updated.'
        );
      }
    }

    echo json_encode($output);

  }
}
?>