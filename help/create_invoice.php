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
      <form id="invoice_form">
        <div class="form-row">
          <div class="form-group col-md-6">
            <div class="jumbotron jumbotron-fluid">
              <div class="container">
                <div class="logo">
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
                    <select class="product_id form-control" name="product_id" id="product_id"></select>
                    <div id="product_name_error_message" class="text-danger">
                  </td>
                  <td>
                    <input type="hidden" name="name" id="name">
                    <input type="text" name="stock" id="stock" placeholder='0' class="form-control" readonly>
                  </td>
                  <td>
                    <input type="number" name="qty" id="qty" placeholder='0' class="form-control" min="1" max="100">
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
                    <button type="button" class="btn btn-success" id="add_product"><i class="fas fa-plus"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row clearfix">
          <div class="col-md-12">
            <div id="load_temp_product_table"></div>
          </div>
        </div>
        <hr class="colorgraph">
        <div class="row">
          <div class="col-sm-12">
            <div class="">
              <h5>Note</h5>
              <textarea class="form-control" id="note" name="note" rows="3" maxlength="500" placeholder="Please enter a note."></textarea>
              <span><p id="character_left">You have reached the limit</p></span>
            </div>
          </div>
        </div>
        <hr class="colorgraph">
        <div class="row">
          <div class="col-md-12">
            <input type="hidden" name="action" id="action" value="" />
            <button type="button" class="btn btn-primary font-weight-bold" id="btnCreateQuote"><i class="fas fa-save"></i> Save</button>
            <button type="button" class="btn btn-danger font-weight-bold float-right" id="cancel_invoice"><i class="fas fa-times"></i> Cancel</button>
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

    // Bring temp product table.
    $('#load_temp_product_table').load("invoice_temp_product.php");

    $('#btnCreateQuote').click(function() {
      createInvoice();
    });

    $(document).keypress(function(e) {
      if(e.which == 13) {
        createInvoice();
      }
    });


    $('.customer_id').select2({
      placeholder: '-- Select Customer 2 --',
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

    $('.product_id').select2({
      placeholder: '-- Select Product --',
      allowClear: true,
      width: '300',
      ajax: {
        url: 'product_list.php',
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

    $('#product_id').change(function(){
      product_id = $('#product_id').val();

      $.ajax({
        type:"POST",
        data:{action:'product_single_fetch', product_id:product_id},
        url:"invoice_action.php",
        dataType:"json",
        success:function(data){
          $('#name').val(data['product_name']);
          $('#stock').val(data['product_quantity']);
          $('#price').val(data['product_price']);
        }
      });
    });

    $('#qty').on('keyup change',function(){      
      calculateProduct();
      checkQty();
    });

    function calculateProduct(){
      var stock, qty, price, tax_subtotal, subtotal, total;
      var tax_percentage = 0.18;

      stock = parseFloat(document.getElementById("stock").value);
      qty = parseFloat(document.getElementById("qty").value);
      price = parseFloat(document.getElementById("price").value);

      subtotal = qty * price;
      subtotal = (Math.round(subtotal * 100) / 100).toFixed(2);
      tax_subtotal = subtotal * tax_percentage;
      tax_subtotal = (Math.round(tax_subtotal * 100) / 100).toFixed(2);
      total = parseFloat(subtotal) + parseFloat(tax_subtotal);
      total = (Math.round(total * 100) / 100).toFixed(2);

      $('#tax').val(tax_subtotal);
      $('#subtotal').val(subtotal);
      $('#total').val(total);
    }

    function clearOrderTable()  {
      $('#invoice_form')[0].reset();
  
      $('#customer_id').val('0'); 
      $('#customer_id').trigger('change'); 
      
      $('#product_id').val('0'); 
      $('#product_id').trigger('change');

      clearProductForm();

      $("#qty_error_message").hide();
      $("#customer_id_error_message").hide();
      $("#qty").removeClass("is-invalid");

    }

    function clearProductForm(){
      $('#product_id').val('0'); 
      $('#product_id').trigger('change');
      $("#product_name_error_message").hide();

      $("#stock").val("");
      $("#qty").val("");
      $("#price").val("");
      $("#tax").val("");
      $("#subtotal").val("");
      $("#total").val("");
    }

    var error_customer_name = false;
    var error_product_name = false;
    var error_qty = false;

    $('.customer_id').on('change', function() {
      checkCustomerName();
    });

    $('.product_id').on('change', function() {
      checkProductName();
    });

    function checkCustomerName() {
      if( $.trim( $('#customer_id').val() ) == '' ){
        $("#customer_id_error_message").html("Customer name is a required field.");
        $("#customer_id_error_message").show();
        $("#customer_id").addClass("is-invalid");
        error_customer_name = true;
      } else {
        $("#customer_id_error_message").hide();
        $("#customer_id").removeClass("is-invalid");
      }
    }

    function checkProductName() {
      if( $.trim( $('#product_id').val() ) == '' ){
        $("#product_name_error_message").html("Product name is a required field.");
        $("#product_name_error_message").show();
        $("#product_id").addClass("is-invalid");
        error_product_name = true;
      } else {
        $("#product_name_error_message").hide();
        $("#product_id").removeClass("is-invalid");
      }
    }

    function checkQty() {

      var qty_value = parseInt($("#qty").val());
      var stock_value = parseInt($("#stock").val());

      if ($.trim($('#qty').val()) == '') {
        $("#qty_error_message").html("Input is blank!");
        $("#qty_error_message").show();
        $("#qty").addClass("is-invalid");
        error_qty= true;
      } else if(qty_value == 0) {
        $("#qty_error_message").html("Enter qty!");
        $("#qty_error_message").show();
        error_qty = true;
        $("#qty").addClass("is-invalid");
      } else if(qty_value > stock_value) {
        $("#qty_error_message").html("Should be less or equal than in stock.");
        $("#qty_error_message").show();
        error_qty = true;
        $("#qty").addClass("is-invalid");
      } else if( qty_value > 100) {
        $("#qty_error_message").html("Qty must be less than or equal to 100.");
        $("#qty_error_message").show();
        error_qty = true;
      } else {
        $("#qty_error_message").hide();
        $("#qty").removeClass("is-invalid");
      }
    }

    $('#add_product').click(function() {
      error_customer_name = false;
      error_product_name = false;
      error_qty = false;

      checkCustomerName();
      checkProductName();
      checkQty();

      if(error_customer_name == false && error_product_name == false && error_qty == false){              
        $.ajax({
          type: "POST",
          data: $('#invoice_form').serialize()+'&action=add_product',
          url: "invoice_action.php",
          success: function(data) {
            if(data=='duplicated') {
              swal("Product already added to the list.", "", "error");
            } else {
              $('#load_temp_product_table').load("invoice_temp_product.php");
              clearProductForm();
              beep();
            }
          }
        });
      } else {
        swal("", "Please make sure all required fields are filled out correctly.", "error");
        return false;
      }
    });

    $('#character_left').text('500 characters left');
    $('#note').keyup(function () {
        var maximum_characters = 500;
        var note_length = $(this).val().length;
        if (note_length >= maximum_characters) {
            $('#character_left').text('You have reached the limit!');
            $('#character_left').addClass("text-danger");
        } 
        else {
            var total_characters = maximum_characters - note_length;
            $('#character_left').text(total_characters + ' characters left');
            $('#character_left').removeClass('text-danger');            
        }
    });
  
    // Make a sound when a new product is added to temp product table.
    function beep() {
      var sound = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");  
      sound.play();
    }

    function createInvoice(){
      error_customer_name = false;

      checkCustomerName();

      if(error_customer_name == false){  
        $.ajax({
          type:"POST",
          data:{action:'create_invoice'},
          url:"invoice_action.php",
          dataType:"json",
          success:function(data){
            console.log(data);
            if(data.status=='success') {
              $('#load_temp_product_table').load("invoice_temp_product.php");
              clearOrderTable();
              swal("Successfully!", data.alert, "success");
            } else if (data.status=='empty') {
              swal(data.message, "", "error");
            }
          },
          error:function(){
            swal("Oops! Something went wrong.", "", "error");
          }
        });
      } else {
        swal("Please make sure all required fields are filled out correctly.", "", "error");
      }
    }

    // Cancel creating invoice.
    $('#cancel_invoice').click(function() {
      swal({
        title: "Are you sure?",
        text: "You will not be able to recover this information!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
          url: "invoice_action.php",
          method:"POST",
          data:{action:'cancel_invoice'},
          success: function(data) {
            $('#load_temp_product_table').load("invoice_temp_product.php");
            clearOrderTable();
          }
        });     
        }
      });
    });
  });

  // Delete product from temp product table. 
  function deleteProduct(index) {
    $.ajax({
      type: "POST",
      data: {action:'delete_product', index:index},
      url:"invoice_action.php",
      success: function(data) {
        $('#load_temp_product_table').load("invoice_temp_product.php");
      }
    });
  }
</script>