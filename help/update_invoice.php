<?php
include('include/header.php');
include('connection.php');
?>

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="index.php">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Create Invoice</li>
  </ol>

  <!-- Product DataTables -->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-table"></i> Invoices Table
      <div class="float-right">
        <a href="invoice.php" class="btn btn-primary btn-sm" role="button" aria-pressed="true"><i class="fas fa-file-invoice fa-fw"></i> Manage Invoices</a>
      </div>
    </div>
    <div class="card-body">
    <form id="frmQuote">




















      <div class="form-group">
        
      </div>










      <div class="form-row">

        <div class="form-group col-md-6">
          <div class="jumbotron jumbotron-fluid">
            <div class="container">
              <div class="logo">
                <!-- <img src="images/egavilanmedia.jpg" alt="EGavilan Media" style="width:375px;height:195;"> -->
                <img src="images/egavilanmedia.jpg" class="img-fluid" alt="Responsive image">
              </div>
              <hr class="my-4">
              <p><strong>Invoice and Inventory Management System</strong></p>
              <p>egavilanmedia@gmail.com</p>
              <p>485 877 7844</p>
              <p>Cupertino, CA 95044, United State of America</p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <!-- <div class="form-group">
            <label>Customer Name <i class="text-danger"> *</i></label>
              <select name="customer_id" id="customer_id" class="custom-select">
                <option value="" hidden>-- Select Customer --</option>
                <?php
                  // echo load_customer_list($conn);
                ?>
              </select>
              <div id="customer_name_error_message" class="text-danger"></div>
          </div> -->
          <div class="form-group">            
            <label>Customer Name <i class="text-danger"> *</i></label>
            <select class="customer_id form-control" name="customer_id" id="customer_id"></select>
            <div id="customer_id_error_message" class="text-danger"></div>
          </div>
          <div class="form-group">
            <label>Address</i></label>
            <textarea id="address" class="form-control" rows="3" placeholder="Enter customer address"></textarea>
          </div>
          <div class="form-group">
            <label>Telephone</i></label>
            <input type="text" id="telephone" class="form-control" placeholder="Enter telephone">
          </div>
          <div class="form-group">
            <label>Email</i></label>
            <input type="text" id="email" class="form-control" placeholder="Enter email">
          </div>
        </div>
      </div>













      <hr class="colorgraph">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-hover" id="tab_logic">
            <thead>
              <tr>
                <th width="20%" class="text-center"> Product </th>
                <th width="10%" class="text-center"> Stock</th>
                <th width="10%" class="text-center"> Qty </th>
                <th width="10%" class="text-center"> Price </th>
                <th width="10%"class="text-center"> Tax </th>
                <th width="12%"class="text-center"> Subtotal </th>
                <th width="13%"class="text-center"> Total </th>
                <th width="6%"class="text-center"> Add</th>
              </tr>
            </thead>
            <tbody> 
              <tr>
                <td>
                  <select name="product_id" id="product_id" class="custom-select">
                    <option value="" hidden>-- Select Product --</option>
                    <?php
                      // echo load_product_list($conn);
                    ?>
                  </select>
                  <div id="product_name_error_message" class="text-danger">
                </td>
                <td>
                  <input type="hidden" name="name" id="name">
                  <input type="text" name="stock" id="stock" placeholder='0' class="form-control" readonly>
                </td>
                <td>
                  <!-- <input type="number" name="qty" id="qty" placeholder='0' class="form-control" min="1" max="100" onkeypress="isInputNumber(event)"> -->
                  <div id="qty_error_message" class="text-danger">
                </td>                
                <td>
                  <input type="number" name="price" id="price" placeholder='0.00' class="form-control" step="0.00" min="0" readonly >
                </td>
                <td>
                  <input type="number" name="tax" id="tax" placeholder='0.00' class="form-control total" readonly>
                  <div id="tax_error_message" class="text-danger">
                </td>
                <td>
                  <input type="number" name="subtotal" id="subtotal" placeholder='0.00' class="form-control total" readonly>
                </td>
                <td>
                  <input type="number" name="total" id="total" placeholder='0.00' class="form-control total" readonly>
                  <div id="total_error_message" class="text-danger">
                </td>
                <td>
                  <button type="button" class="btn btn-success" id="btnAddProduct"><i class="fas fa-plus"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row clearfix">
        <div class="col-md-12">
          <div id="loadquotationTableTemp"></div>
        </div>
      </div>
      <hr class="colorgraph">
      <div class="row">
        <div class="col-sm-12">
          <div class="">
            <h5>Note</h5>
            <textarea class="form-control" id="note" name="note" rows="3" maxlength="500" placeholder="Please enter a note."></textarea>
            <span><p id="characterLeft">You have reached the limit</p></span>
          </div>
        </div>
      </div>
      <hr class="colorgraph">
      <div class="row">
        <div class="col-md-12">
          <input type="hidden" name="action" id="action" value="" />
          <button type="button" class="btn btn-primary font-weight-bold" id="btnCreateQuote"><i class="fas fa-save"></i> Save</button>
          <button type="button" class="btn btn-danger font-weight-bold float-right" id="btnCancel"><i class="fas fa-times"></i> Cancel</button>
        </div>
      </div>
    </form>
    </div>
  </div>

  <!-- Footer -->
<?php
include("include/footer.php");
?>

<script>
  $(document).ready(function(){

    order_id = "<?php echo $_GET['order_id']?>";
    // console.log(order_id);


    $.ajax({
      type:"POST",
      data:{action:'single_fetch', order_id:order_id},
      url:"invoice_action.php",
      dataType:"json",
      success:function(data){
        var new_option = new Option(data.customer_full_name, data.customer_id, false, false);
        $('#customer_id').append(new_option).trigger('change');
      }
    });

    $('.customer_id').select2({
      placeholder: '-- Select Customer --',
      allowClear: true,
      width: '100%',
      ajax: {
        url: 'customer_list.php',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });

    $('#customer_id').change(function(){
      customer_id = $('#customer_id').val();
      $.ajax({
        type:"POST",
        data:{action:'customer_single_fetch', customer_id:customer_id},
        url:"invoice_action.php",
        dataType:"json",
        success:function(data){
          $('#email').val(data['email']);
          $('#address').val(data['address']);
          $('#telephone').val(data['telephone']);
        }
      });
		});
  });
</script>