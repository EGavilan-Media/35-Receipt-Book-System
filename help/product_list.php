<?php
include "connection.php";

  if(!isset($_GET['q'])){ 
    // Fetch records
    $product_query = "SELECT * FROM tbl_products WHERE product_status = 'Active' ORDER BY product_name ASC";

  }else{
    // Fetch records
    $product_query = "SELECT * FROM tbl_products WHERE product_status = 'Active' AND product_name LIKE '%".$_GET['q']."%' ORDER BY product_name ASC";
  }

  $product_records = mysqli_query($conn, $product_query);
  $data = array();

  while ($row = mysqli_fetch_assoc($product_records)){
    $data[] = array(
      "id"         =>  $row['product_id'],
      "text"       =>  $row['product_name']
    );
  }

  echo json_encode($data); 

?>
