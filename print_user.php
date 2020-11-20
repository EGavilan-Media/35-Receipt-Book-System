<?php
require('vendor/fpdf/fpdf.php');

$connect = mysqli_connect('localhost','root','');
mysqli_select_db($connect,'egm_sales');

class PDF extends FPDF {
	function Header(){
		$this->SetFont('Arial','B',15);

		$this->Cell(12);
		$this->Image('images/egavilanmedia.jpg',55,10,100);
		$this->Ln(4);
		
		$this->SetTextColor(7, 56, 99);

		$this->SetY(15);
		$this->SetFont('Arial', 'B', 30);
		$this->Ln(30);
		$this->SetX(78);
		$this->Write(5, 'User List');

		$this->Ln(15);

		$this->SetFont('Arial','B',12);
		$this->SetFillColor(7, 56, 99);
		$this->SetDrawColor(0,0,0);
		$this->SetFont('Arial', '', 12);
		$this->SetTextColor(255,255,255);
		$this->Cell(10,10,'ID',1,0,'',true);
		$this->Cell(55,10,'Full Name',1,0,'',true);
		$this->Cell(55,10,'Email',1,0,'',true);
		$this->Cell(20,10,'Gender',1,0,'',true);
		$this->Cell(20,10,'Status',1,0,'',true);
		$this->Cell(20,10,'Role',1,1,'',true);

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
$pdf->AddPage();

$pdf->SetFont('Arial','',10);
$pdf->SetDrawColor(0,0,0);

$query = mysqli_query($connect,"SELECT * FROM tbl_users");

while($data = mysqli_fetch_array($query)){
	$pdf->Cell(10,5,$data[0],1,0);
	$pdf->Cell(55,5,$data[3],1,0);
	$pdf->Cell(55,5,$data[4],1,0);
	$pdf->Cell(20,5,$data[5],1,0);
	$pdf->Cell(20,5,$data[6],1,0);
	$pdf->Cell(20,5,$data[7],1,1);
}

$pdf->Output('I','User List.pdf');
?>
