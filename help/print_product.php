<?php
require('vendor/fpdf/fpdf.php');

$connect = mysqli_connect('localhost','root','');
mysqli_select_db($connect,'egm_sales');

class PDF extends FPDF {
	function Header(){
		$this->SetFont('Arial','B',15);

		$this->Cell(30);
		$this->Image('images/egavilanmedia.jpg',98,10,100);
		$this->Ln(4);
		
		$this->SetTextColor(7, 56, 100);

		$this->SetY(15);
		$this->SetFont('Arial', 'B', 30);
		$this->Ln(30);
		$this->SetX(116);
		$this->Write(5, 'Product List');

		$this->Ln(15);

		$this->SetFont('Arial','B',12);
		$this->SetFillColor(7, 56, 99);
		$this->SetDrawColor(0,0,0);
		$this->SetFont('Arial', '', 12);
		$this->SetTextColor(255,255,255);
		$this->Cell(10,10,'ID',1,0,'',true);
		$this->Cell(70,10,'Name',1,0,'',true);
		$this->Cell(20,10,'Qty',1,0,'',true);
		$this->Cell(20,10,'Price',1,0,'',true);
		$this->Cell(40,10,'Cagetory',1,0,'',true);
		$this->Cell(40,10,'Brand',1,0,'',true);
		$this->Cell(50,10,'Supplier',1,0,'',true);
		$this->Cell(30,10,'Status',1,1,'',true);
	}
	function Footer(){
		$this->SetY(-15);
		$this->SetFont('Arial','',8);
		$this->Cell(0,10,'Page '.$this->PageNo()." / {pages}",0,0,'C');
	}
}

$pdf = new PDF('P','mm','A4');

$pdf->AliasNbPages('{pages}');

$pdf->SetAutoPageBreak(true,15);
$pdf->AddPage('L');

$pdf->SetFont('Arial','',10);
$pdf->SetDrawColor(0,0,0);

$product_query = "SELECT product.product_id,
			product.product_name,
			product.product_quantity,
			product.product_price,
			category.category_name,
			brand.brand_name,
			supplier.supplier_full_name,
			product.product_created_at,
			product.product_status
			FROM tbl_products AS product
			INNER JOIN tbl_categories AS category
			ON product.category_id = category.category_id 
			INNER JOIN tbl_brands AS brand
			ON product.brand_id = brand.brand_id
			INNER JOIN tbl_suppliers AS supplier
			ON product.supplier_id = supplier.supplier_id";

$product_records = mysqli_query($connect, $product_query);

while($data = mysqli_fetch_array($product_records)){
	$cellWidth = 70;
	$cellHeight = 5;

	if($pdf->GetStringWidth($data[1]) < $cellWidth){
		$line = 1;
	}else{
		$textLength = strlen($data[1]);
		$errMargin = 10;
		$startChar = 0;
		$maxChar = 0;
		$textArray = array();
		$tmpString = "";

		while($startChar < $textLength){
			while($pdf->GetStringWidth( $tmpString ) < ($cellWidth-$errMargin) && ($startChar+$maxChar) < $textLength ) {
				$maxChar++;
				$tmpString = substr($data[1],$startChar,$maxChar);
			}
			$startChar = $startChar+$maxChar;
			array_push($textArray,$tmpString);
			$maxChar = 0;
			$tmpString = '';
		}
		$line = count($textArray);
	}

	$pdf->Cell(10,($line * $cellHeight),$data[0],1,0);
	$xPos = $pdf->GetX();
	$yPos = $pdf->GetY();
	$pdf->MultiCell($cellWidth,$cellHeight,$data[1],1);

	$pdf->SetXY($xPos + $cellWidth , $yPos);

	$pdf->Cell(20,($line * $cellHeight),$data[2],1,0);
	$pdf->Cell(20,($line * $cellHeight),$data[3],1,0);
	$pdf->Cell(40,($line * $cellHeight),$data[4],1,0);
	$pdf->Cell(40,($line * $cellHeight),$data[5],1,0);
	$pdf->Cell(50,($line * $cellHeight),$data[6],1,0);
	$pdf->Cell(30,($line * $cellHeight),$data[8],1,1);
}

$pdf->Output('I','Product List.pdf');
?>
