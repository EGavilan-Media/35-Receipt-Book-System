<?php

include "connection.php";
session_start();
$output = '';
if(isset($_POST["action"])){

  // Fetch quotes
  if($_POST["action"] == "quote_fetch"){

    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];
    $searchValue = $_POST['search']['value'];

    $searchQuery = " ";
    if($searchValue != ''){
      $searchQuery = " and (quote_id LIKE '%".$searchValue."%'
                            OR quote_total_before_tax LIKE '%".$searchValue."%'
                            OR quote_total_tax LIKE '%".$searchValue."%'
                            OR quote_total_after_tax LIKE '%".$searchValue."%'
                            OR quote_created_datetime LIKE '%".$searchValue."%' ) ";
    }

    // Total number of records without filteri
    $quoteResult = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_quote");
    $records = mysqli_fetch_assoc($quoteResult);
    $totalRecords = $records['allcount'];

    // Total number of records with filtering
    $quoteResult = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_quote WHERE 1 ".$searchQuery);
    $records = mysqli_fetch_assoc($quoteResult);
    $totalRecordwithFilter = $records['allcount'];

        $sqlQuote="SELECT quote.quote_id,
                      quote.quote_total_before_tax,
                      quote.quote_total_tax,
                      quote.quote_total_after_tax,
                      custo.customer_firstname,
                      custo.customer_lastname,
                      quote.quote_created_datetime
                      FROM tbl_quote AS quote
                      INNER JOIN tbl_customer AS custo
                      ON quote.customer_id=custo.customer_id WHERE 1
                      ".$searchQuery."
                      ORDER BY ".$columnName."
                      ".$columnSortOrder."
                      LIMIT ".$row.",".$rowperpage;


    $prodRecords = mysqli_query($conn, $sqlQuote);
    $data = array();

    while ($row = mysqli_fetch_assoc($prodRecords)){

      $data[] = array(
        "quote_id"                      =>  $row['quote_id'],
        "customer_name"                 =>  $row['customer_firstname']." ".$row['customer_lastname'],
        "quote_total_before_tax"        =>  $row['quote_total_before_tax'],
        "quote_total_tax"               =>  $row['quote_total_tax'],
        "quote_total_after_tax"         =>  $row['quote_total_after_tax'],
        "quote_created_datetime"        =>  $row['quote_created_datetime'],
        "view"                          =>  '<a href="printQuote.php?quote_id='.$row['quote_id'].'" class="btn btn-primary" target="_blank");"><i class="fas fa-print"></i></a>'
      );
    }

    $response = array(
      "draw"                  => intval($draw),
      "iTotalRecords"         => $totalRecords,
      "iTotalDisplayRecords"  => $totalRecordwithFilter,
      "aaData"                => $data

    );

    echo json_encode($response);

  }

    // Single fetch
    if($_POST["action"] == "customer_single_fetch"){

      $customer_id = $_POST['customer_id'];

      $sql = "SELECT customer_address, customer_email, customer_telephone FROM tbl_customer WHERE customer_id = '$customer_id'";

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

      $sql = "SELECT product_name, product_price, product_quantity FROM tbl_product WHERE product_id = '$product_id'";

      $result = mysqli_query($conn, $sql);

      $row = mysqli_fetch_row($result);

      $output = array(
        "name"		      => 	$row[0],
        "price"		      => 	$row[1],
        "qty"		        => 	$row[2]
      );

      echo json_encode($output);

    }

  // Add product to produtTableTemp
  if($_POST["action"] == "add_item"){

    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $tax = $_POST['tax'];
    $subtotal = $_POST['subtotal'];
    $total = $_POST['total'];
    $i=0;

    // Validate product is not already entried
    if(isset($_SESSION['productTableTemp'])){

      foreach (@$_SESSION['productTableTemp'] as $key) {

        $row=explode("||", @$key);

        if($row[0] == $product_id){
          $i=$i+1;
        }
      }

      if($i==0){
        $productTable =
        $product_id."||".
        $name."||".
        $qty."||".
        $price."||".
        $tax."||".
        $subtotal."||".
        $total;

        $_SESSION['productTableTemp'][]=$productTable;
        $_SESSION['quotetationCustomerIDTemp']=$customer_id;
      } else {
        echo "duplicated";
      }

    } else {
      $productTable =
      $product_id."||".
      $name."||".
      $qty."||".
      $price."||".
      $tax."||".
      $subtotal."||".
      $total;

      $_SESSION['productTableTemp'][]=$productTable;
      $_SESSION['quotetationCustomerIDTemp']=$customer_id;
    }

  }

  // Eliminate Item from the produtTableTemp
  if($_POST["action"] == "eliminate_item"){

    $index = $_POST['index'];

    unset($_SESSION['productTableTemp'][$index]);
    $data=array_values($_SESSION['productTableTemp']);
    unset($_SESSION['productTableTemp']);
    $_SESSION['productTableTemp']=$data;

  }

  // Create Quotation
  if($_POST["action"] == "create_quote"){

    // Check that the productTableTemp SESSION exists
    if (isset($_SESSION['productTableTemp'])) {

      $customer_id = $_SESSION['quotetationCustomerIDTemp'];
      $note = $_POST['note'];
      $subTotal = $_SESSION['sub_total'];
      $taxAmount = $_SESSION['tax_amount'];
      $total = $_SESSION['total'];

      $quoteQuery = "INSERT INTO tbl_quote (customer_id,
                                      quote_total_tax,
                                      quote_total_before_tax,
                                      quote_total_after_tax,
                                      note,
                                      quote_created_datetime)
                              VALUES('$customer_id',
                                    '$taxAmount',
                                    '$subTotal',
                                    '$total',
                                    '$note',
                                    NOW())";

      if(mysqli_query($conn, $quoteQuery)){

        $output = array(
          'status'        => 'success',
          'alert'         => 'New quotation created.'
        );

        // Get the last storage quote_id
        $lastQuoteIDResult = mysqli_query($conn,"SELECT quote_id FROM tbl_quote GROUP BY quote_id DESC");
        $quote_id = mysqli_fetch_row($lastQuoteIDResult)[0];

        $productTable=$_SESSION['productTableTemp'];

        for ($i = 0; $i < count($productTable) ; $i++) {
          $product=explode("||", $productTable[$i]);

          $productQuery="INSERT INTO tbl_quote_item (quote_id,
                              product_id,
                              quote_product_quantity)
                        values ('$quote_id',
                            '$product[0]',
                            '$product[2]')";

          if(mysqli_query($conn, $productQuery)){
            unset($_SESSION['productTableTemp']);
            unset($_SESSION['quotetationCustomerIDTemp']);
          }
        }

      }else{
        $output = array(
          'status'        => 'error'
        );
      }

    }else{
      $output = array(
        'status'        => 'empty product table',
        'alert'         => 'Please enter at least one product.'
      );
    }
    echo json_encode($output);
  }

  // Cancel creation of the current Quotation
  if($_POST["action"] == "cancel_quotation"){
    unset($_SESSION['productTableTemp']);
    unset($_SESSION['quotetationCustomerIDTemp']);
  }

}

?>