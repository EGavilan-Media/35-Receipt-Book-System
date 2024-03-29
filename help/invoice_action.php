<?php

include "connection.php";
session_start();
$output = '';
if(isset($_POST["action"])){

  // Fetch invoices
  if($_POST["action"] == "item_fetch"){

    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $row_per_page = $_POST['length'];
    $column_index = $_POST['order'][0]['column'];
    $column_name = $_POST['columns'][$column_index]['data'];
    $column_sort_order = $_POST['order'][0]['dir'];
    $csearch_value = $_POST['search']['value'];

    $search_query = " ";
    if($csearch_value != ''){
      $search_query = " and (order_id LIKE '%".$csearch_value."%'
                            OR order_total_before_tax LIKE '%".$csearch_value."%'
                            OR order_total_tax LIKE '%".$csearch_value."%'
                            OR order_total_after_tax LIKE '%".$csearch_value."%'
                            OR order_created_at LIKE '%".$csearch_value."%' ) ";
    }

    // Total number of records without filteri
    $order_result = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_orders");
    $records = mysqli_fetch_assoc($order_result);
    $total_records = $records['allcount'];

    // Total number of records with filtering
    $order_result = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_orders WHERE 1 ".$search_query);
    $records = mysqli_fetch_assoc($order_result);
    $total_record_with_filter = $records['allcount'];

    $sql="SELECT ord.order_id,
                  custo.customer_full_name,
                  ord.order_total_before_tax,
                  ord.order_total_tax,
                  ord.order_total_after_tax,
                  ord.order_created_at
                FROM tbl_orders AS ord
                INNER JOIN tbl_customers AS custo
                ON ord.customer_id=custo.customer_id WHERE 1
                ".$search_query."
                ORDER BY ".$column_name."
                ".$column_sort_order."
                LIMIT ".$row.",".$row_per_page;

    $result = mysqli_query($conn, $sql);
    $data = array();

    while ($row = mysqli_fetch_assoc($result)){

      $data[] = array(
        "order_id"                      =>  $row['order_id'],
        "customer_full_name"            =>  $row['customer_full_name'],
        "order_total_before_tax"        =>  $row['order_total_before_tax'],
        "order_total_tax"               =>  $row['order_total_tax'],
        "order_total_after_tax"         =>  $row['order_total_after_tax'],
        "order_created_at"              =>  $row['order_created_at'],
        "view"                          =>  '<a href="print_invoice.php?order_id='.$row['order_id'].'&output" class="btn btn-info btn-sm"><i class="fas fa-download"></i></a>
                                              <a href="print_invoice.php?order_id='.$row['order_id'].'" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-print"></i></a>
                                              <a href="update_invoice.php?order_id='.$row['order_id'].'" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                              <button type="button" order_id="'.$row['order_id'].'" class="btn btn-danger btn-sm delete_invoice"><i class="fas fa-trash-alt"></i></button>'
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

    // Single fetch
  if($_POST["action"] == "customer_single_fetch"){

    $customer_id = $_POST['customer_id'];

    $sql = "SELECT customer_address, customer_email, customer_telephone FROM tbl_customers WHERE customer_id = '$customer_id'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_row($result);

    $output = array(
      "address"		      => 	$row[0],
      "email"		        => 	$row[1],
      "telephone"	      =>	$row[2]
    );

    echo json_encode($output);

  }

    // Single fetch
  if($_POST["action"] == "product_single_fetch"){

    $product_id = $_POST['product_id'];

    $sql = "SELECT product_name, product_price, product_quantity FROM tbl_products WHERE product_id = '$product_id'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_row($result);

    $output = array(
      "product_name"		      => 	$row[0],
      "product_price"		        => 	$row[1],
      "product_quantity"	      =>	$row[2]
    );

    echo json_encode($output);

  }

  // Add product to the invoice temp product table.
  if($_POST["action"] == "add_product"){
    // Validate product is not already entried
    if(isset($_SESSION['temp_product_table'])){
      $i=0;
      foreach (@$_SESSION['temp_product_table'] as $key) { 
        $row=explode("||", @$key);

        if($row[0] == $_POST['product_id']){
          $i=$i+1;
        }
      }
      if($i==0){
        $product_table = $_POST['product_id']."||".$_POST['name']."||".$_POST['qty']."||".$_POST['price']."||".$_POST['tax']."||".$_POST['subtotal']."||".$_POST['total'];
        $_SESSION['temp_product_table'][] = $product_table;
        $_SESSION['customer_id'] = $_POST['customer_id'];
        $_SESSION['invoice_note'] = $_POST['note'];
      }else{
        echo "duplicated";
      }
    }else{
      $product_table = $_POST['product_id']."||".$_POST['name']."||".$_POST['qty']."||".$_POST['price']."||".$_POST['tax']."||".$_POST['subtotal']."||".$_POST['total'];
      $_SESSION['temp_product_table'][] = $product_table;
      $_SESSION['customer_id'] = $_POST['customer_id'];
      $_SESSION['invoice_note'] = $_POST['note'];  
    }
  }

  // Delete product fron the temp product table
  if($_POST["action"] == "delete_product"){
    unset($_SESSION['temp_product_table'][$_POST['index']]);
    $data=array_values($_SESSION['temp_product_table']);
    unset($_SESSION['temp_product_table']);
    $_SESSION['temp_product_table']=$data;
  }

  // Create invoice
  if($_POST["action"] == "create_invoice"){

    // Check that the item_table_temp SESSION exists
    if (isset($_SESSION['temp_product_table']) && $_SESSION["total"] != 0.00) {

      $sql = "INSERT INTO tbl_orders (order_created_by,
                                      order_receiver_name,
                                      order_receiver_address,
                                      order_total_before_tax,
                                      order_total_tax,
                                      order_total_after_tax,
                                      order_note,
                                      order_created_at)
                              VALUES('".$_SESSION["user_id"]."',
                                    '".$_POST["customer_name"]."',
                                    '".$_POST["customer_address"]."',
                                    '".$_SESSION["sub_total"]."',
                                    '".$_SESSION["tax_amount"]."',
                                    '".$_SESSION["total"]."',
                                    '".$_POST["note"]."',
                                    NOW())";

      if(mysqli_query($conn, $sql)){

        // Get last insert id 
        $order_id = mysqli_insert_id($conn);

        $item_table=$_SESSION['item_table_temp'];

        for ($i = 0; $i < count($item_table) ; $i++) {
          $item = explode("||", $item_table[$i]);

          $itemQuery="INSERT INTO invoice_order_item (order_id,
                              item_name,
                              order_item_quantity,
                              order_item_price,
                              order_item_tax_amount,
                              order_item_subtotal_amount,
                              order_item_final_amount)
                        values ('$order_id',
                            '$item[0]',
                            '$item[1]',
                            '$item[2]',
                            '$item[3]',
                            '$item[4]',
                            '$item[5]')";

          if(mysqli_query($conn, $itemQuery)){

            
            $output = array(
              'status'        => 'success',
              'message'       => 'New invoice successfully created!',
              'order_id'      => $order_id
            );
            unset($_SESSION['item_table_temp']);
            unset($_SESSION['customer_name']);
            unset($_SESSION['customer_address']);
          }
        }

      }else{
        $output = array(
          'status'        => 'error'
        );
      }

    }else{
      $output = array(
        'status'        => 'empty',
        'message'       => 'Please enter at least one product information.'
      );
    }
    echo json_encode($output);
  }

  // Cancel creation of the current invoice
  if($_POST["action"] == "cancel_invoice"){
    unset($_SESSION['temp_product_table']);
  }


  // ============================================WORKING UPDATING INVOICE============================================================================
  // Delete note
  if($_POST["action"] == "delete_order"){

    $sql = "DELETE FROM tbl_orders WHERE order_id = '".$_POST["order_id"]."'";

    if(mysqli_query($conn, $sql)){

      $output = array(
        'status'        => 'success',
        'message'	    	=> 'Invoice has been deleted successfully.',
      );

    }

    echo json_encode($output);

  }

  // Single order fetch
  if($_POST["action"] == "single_fetch"){

    $sql = "SELECT order_id,
                    customer_id, 
                    order_total_before_tax, 
                    order_note 
                  FROM tbl_orders
                  WHERE order_id = '".$_POST["order_id"]."'";

    $sql="SELECT ord.order_id,
                  custo.customer_id,
                  custo.customer_full_name
                FROM tbl_orders AS ord
                INNER JOIN tbl_customers AS custo
                ON ord.customer_id=custo.customer_id WHERE 1 ";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);

    $output = array(
      "order_id"		              =>	$row[0],
      "customer_id"             	=>	$row[1],
      "customer_full_name"		    => 	$row[2]
    );

    echo json_encode($output);

  }

  // Add items to the item_table_temp
  if($_POST["action"] == "update_item"){

    $update_item_table = $_POST['item_name']."||".$_POST['qty']."||".$_POST['price']."||".$_POST['tax']."||".$_POST['subtotal']."||".$_POST['total'];
    $_SESSION['update_item_table_temp'][] = $update_item_table;

  }

  // Eliminate Item from the update_item_table_temp
  if($_POST["action"] == "delete_update_item"){

    unset($_SESSION['update_item_table_temp'][$_POST['index']]);
    $table_data=array_values($_SESSION['update_item_table_temp']);
    unset($_SESSION['update_item_table_temp']);
    $_SESSION['update_item_table_temp']=$table_data;

  }

  if($_POST["action"] == "cancel_update"){
    unset($_SESSION['order_id']);
    unset($_SESSION['update_item_table_temp']);
    unset($_SESSION['update_sub_total']);
    unset($_SESSION['update_tax_amount']);
    unset($_SESSION['update_total']);
    unset($_SESSION['update_order_note']);
  }

  // Update invoice
  if($_POST["action"] == "update_invoice"){

    // Check that the item_table_temp SESSION exists
    if (isset($_SESSION['update_item_table_temp']) && $_SESSION["update_total"] != 0.00) {

      $sql = "UPDATE tbl_orders SET order_last_update_by = '".$_SESSION["user_id"]."',
                                  order_receiver_name = '".$_POST["customer_name"]."',
                                  order_receiver_address = '".$_POST["customer_address"]."',
                                  order_total_before_tax = '".$_SESSION["update_sub_total"]."',
                                  order_total_tax = '".$_SESSION["update_tax_amount"]."',
                                  order_total_after_tax = '".$_SESSION["update_total"]."',
                                  order_note = '".$_POST["note"]."',
                                  order_updated_at = NOW()
                                WHERE order_id = '".$_SESSION["order_id"]."'";

        if(mysqli_query($conn, $sql)){

          $sql2 = "DELETE FROM invoice_order_item WHERE order_id = '".$_SESSION["order_id"]."'";
          $result = mysqli_query($conn, $sql2);

          $item_table=$_SESSION['update_item_table_temp'];

          for ($i = 0; $i < count($item_table); $i++) {
            $item = explode("||", $item_table[$i]);
  
            $itemQuery="INSERT INTO invoice_order_item (order_id,
                                item_name,
                                order_item_quantity,
                                order_item_price,
                                order_item_tax_amount,
                                order_item_subtotal_amount,
                                order_item_final_amount)
                          values ('".$_SESSION["order_id"]."',
                              '$item[0]',
                              '$item[1]',
                              '$item[2]',
                              '$item[3]',
                              '$item[4]',
                              '$item[5]')";

            if(mysqli_query($conn, $itemQuery)){
  
              $output = array(
                'status'          => 'success',
                'order_id'        => $_SESSION["order_id"]
              );

            }
          }
        }

    }else{
      $output = array(
        'status'        => 'empty',
        'message'       => 'Please enter at least one item information.'
      );
    }

    echo json_encode($output);
    unset($_SESSION['order_id']);
    unset($_SESSION['update_item_table_temp']);
    unset($_SESSION['update_sub_total']);
    unset($_SESSION['update_tax_amount']);
    unset($_SESSION['update_total']);
    unset($_SESSION['update_order_note']);

  }

}
?>