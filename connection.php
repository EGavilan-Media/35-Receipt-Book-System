<?php
$servername="localhost";
$username="root";
$password="";
$dbname="egm_sales";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if(!$conn){
  die("Connection failed: ".mysqli_connect_error());
}

// echo "Connected successfully";
function load_customer_list($conn){
	$sql = "SELECT * FROM tbl_customers WHERE customer_status = 'Active' ORDER BY customer_full_name ASC";

  	$result = mysqli_query($conn, $sql);


	while($row = mysqli_fetch_assoc($result))
	{
		$output .= '<option value="'.$row["customer_id"].'">'.$row["customer_full_name"].' '.$row["customer_full_name"].'</option>';
	}
	return $output;
}

function load_category_list($conn){
	$sql = "SELECT * FROM tbl_categories WHERE category_status = 'Active' ORDER BY category_name ASC";
  $result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)){
		$output .= '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
	}
	return $output;
}

function load_brand_list($conn){
	$sql = "SELECT * FROM tbl_brands WHERE brand_status = 'Active' ORDER BY brand_name ASC";
  	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)){
		$output .= '<option value="'.$row["brand_id"].'">'.$row["brand_name"].'</option>';
	}
	return $output;
}

function load_supplier_list($conn){
	$sql = "SELECT * FROM tbl_suppliers WHERE supplier_status = 'Active' ORDER BY supplier_full_name ASC";
  	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)){
		$output .= '<option value="'.$row["supplier_id"].'">'.$row["supplier_full_name"].'</option>';
	}
	return $output;
}

// function load_product_list($conn){
// 	$sql = "SELECT * FROM tbl_product WHERE product_status = 'Active' ORDER BY product_name ASC";

//   	$result = mysqli_query($conn, $sql);


// 	while($row = mysqli_fetch_assoc($result))
// 	{
// 		$output .= '<option value="'.$row["product_id"].'">'.$row["product_name"].'</option>';
// 	}
// 	return $output;
// }

?>