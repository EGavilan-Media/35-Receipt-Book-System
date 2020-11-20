<?php 
session_start();
?>
 <table class="table table-bordered table-hover">
    <thead>
	 	<tr class="font-weight-bold">
	 		<td>No.</td>
	 		<td>Product</td>
	 		<td>Qty</td>
	 		<td>Price</td>
	 		<td>Tax</td>
	 		<td>Subtotal</td>
	 		<td>Total</td>
	 		<td>Delete</td>
	 	</tr>
 	</thead>
<?php 
$total=0;
$tax_amount=0;
$sub_total=0;
$cliente="";
$i=0;
	
if(isset($_SESSION['temp_product_table'])):			
foreach (@$_SESSION['temp_product_table'] as $key) {
	$row=explode("||", @$key);
?>
 	<tr>
 		<td><?php echo $i+1; ?></td>
 		<td><?php echo $row[1] ?></td>
 		<td><?php echo $row[2] ?></td>
 		<td><?php echo $row[3] ?></td>
 		<td><?php echo $row[4] ?></td>
 		<td><?php echo $row[5] ?></td>
 		<td><?php echo $row[6] ?></td>
 		<td>
 			<span class="btn btn-danger btn-xs" onclick="deleteProduct('<?php echo $i; ?>');">
 				<i class="fas fa-times"></i>
 			</span>
 		</td>
 	</tr>

<?php
$sub_total = $sub_total + $row[5];
$tax_amount = $tax_amount + $row[4];
$total = $total + $row[6];
$i++;

$_SESSION['sub_total']=$sub_total;
$_SESSION['tax_amount']=$tax_amount;
$_SESSION['total']=$total;
}
endif;	
if ($i == 0){
	$_SESSION['sub_total']=0.00;
	$_SESSION['tax_amount']=0.00;
	$_SESSION['total']=0.00;
}
 ?>
 </table>
<hr class="colorgraph">
<div class="row">
  <div class="col-sm-12 col-lg-7">
  <!-- WORK ON THIS PART. off set the div -->
  </div>
  <div class="col-sm-12 col-lg-5">
    <div class="card text-muted bg-light">
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <tbody>
            <tr>
              <th class="text-right">Sub Total
              </th>
              <td class="text-center">
                <?php echo "$".$sub_total; ?> 
              </td>
            </tr>
            <tr>
              <th class="text-right">Tax Total
              </th>
              <td class="text-center">
                <?php echo "$".$tax_amount; ?> 
              </td>
            </tr>
            <tr>
              <th class="text-right">Grand Total
              </th>
              <td class="text-center">
                <?php echo "$".$total; ?> 
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>