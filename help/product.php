<?php
include('include/header.php');
include('connection.php');
?>

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="index.php">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Manage Products</li>
  </ol>

  <!-- Product DataTables -->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-table"></i> Products Table
      <div class="float-right">
        <button type="button" id="add_product" class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> Add New Product</button>
        <a href="print_product.php" target="_blank" class="btn btn-secondary btn-sm">
          <i class="fas fa-print"></i> print
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="product_table" width="100%">
          <thead class="p-3 mb-2 bg-light font-weight-bold">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Category</th>
              <th>Brand</th>
              <th>Supplier</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- MODALS -->
  <!-- Add Product Modal -->
  <div class="modal fade" id="form_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_title"></h5>
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="product_form">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Category <i class="text-danger"> *</i></label>
                <select name="category_id" id="category_id" class="custom-select">
                  <option value="" hidden>-- Select Category --</option>
                  <?php
                    echo load_category_list($conn);
                  ?>
                </select>
                <div id="category_error_message" class="text-danger"></div>
              </div>
              <div class="form-group col-md-6">
                <label>Brand <i class="text-danger"> *</i></label>
                <select name="brand_id" id="brand_id" class="custom-select">
                  <option value="" hidden>-- Select Brand --</option>
                  <?php
                    echo load_brand_list($conn);
                  ?>
                </select>
                <div id="brand_error_message" class="text-danger"></div>
              </div>
            </div>
            <div class="form-group">
              <label>Name <i class="text-danger">*</i></label>
              <input type="text" id="name" name="name" class="form-control" maxlength="100" autocomplete="off" placeholder="Enter product name">
              <div id="name_error_message" class="text-danger"></div>
            </div>
            <div class="form-group">
              <label>Description </i></label>
              <textarea id="description" name="description" class="form-control" rows="2" maxlength="500" autocomplete="off" placeholder="Enter product description"></textarea>
              <div id="description_error_message" class="text-danger"></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Price <i class="text-danger"> *</i></label>
                <input type="number" id="price" name="price" class="form-control" maxlength="10" autocomplete="off" placeholder="Enter product price" step="0.01">
                <div id="price_error_message" class="text-danger"></div>
              </div>
              <div class="form-group col-md-6">
                <label>Quantity <i class="text-danger"> *</i></label>
                <input type="number" id="quantity" name="quantity" class="form-control" maxlength="10" autocomplete="off" placeholder="Enter product quantity">
                <div id="quantity_error_message" class="text-danger"></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Supplier <i class="text-danger"> *</i></label>
                <select name="supplier_id" id="supplier_id" class="custom-select">
                  <option value="" hidden>-- Select supplier --</option>
                  <?php
                    echo load_supplier_list($conn);
                  ?>
                </select>
                <div id="supplier_error_message" class="text-danger"></div>
              </div>
              <div class="form-group col-md-6">
                <label>Status <i class="text-danger"> *</i></label>
                <select name="status" id="status" class="custom-select">
                  <option value="" hidden>-- Status --</option>
                  <option>Active</option>
                  <option>Inactive</option>
                </select>
                <div id="status_error_message" class="text-danger"></div>
              </div>
            </div>
            <br>
            <div class="modal-footer">
              <input type="hidden" name="product_id" id="product_id"/>
              <input type="hidden" name="action" id="action" value="add_product"/>
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
              <input type="submit" name="button_action" id="button_action" class="btn btn-primary" value="Save" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Add Product Modal -->

  <!-- View Product Modal-->
  <div class="modal fade" id="view_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Product Details</h5>
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <th>ID</th>
              <td>
                <div id="view_id"></div>
              </td>
            </tr>
            <tr>
              <th>Supplier name</th>
              <td>
                <div id="view_supplier_name"></div>
              </td>
            </tr>
            <tr>
              <th>Category name</th>
              <td>
                <div id="view_category_name"></div>
              </td>
            </tr>
            <tr>
              <th>Brand name</th>
              <td>
                <div id="view_brand_name"></div>
              </td>
            </tr>
            <tr>
              <th>Name</th>
              <td>
                <div id="view_name"></div>
              </td>
            </tr>
            <tr>
              <th>Description</th>
              <td>
                <div id="view_description"></div>
              </td>
            </tr>
            <tr>
              <th>Price</th>
              <td>
                <div id="view_price"></div>
              </td>
            </tr>
            <tr>
              <th>Quantity</th>
              <td>
                <div id="view_quantity"></div>
              </td>
            </tr>
            <tr>
              <th>Status</th>
              <td>
                <div id="view_status"></div>
              </td>
            </tr>
            <tr>
              <th>Created by</th>
              <td>
                <div id="view_created_by"></div>
              </td>
            </tr>
            <tr>
              <th>Created</th>
              <td>
                <div id="view_created_at"></div>
              </td>
            </tr>
            <tr>
              <th>Last updated by</th>
              <td>
                <div id="view_last_update_by"></div>
              </td>
            </tr>
            <tr>
              <th>Last updated</th>
              <td>
                <div id="view_updated_at"></div>
              </td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End View Product Modal -->

  <!-- Footer -->
<?php
include("include/footer.php");
?>

<script>

  $(document).ready(function(){
    var datatable = $('#product_table').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
          url:'product_action.php',
          type: 'POST',
          data: {action:'product_fetch'}
      },
      'columns': [
          { data: 'product_id' },
          { data: 'product_name'},
          { data: 'product_price'},
          { data: 'product_quantity'},
          { data: 'category_name'},
          { data: 'brand_name'},
          { data: 'supplier_full_name'},
          { data: 'product_status'},
          { data: 'action',"orderable":false}
      ]
    });

    $('#add_product').click(function(){
      $('#modal_title').text('Add Product');
      $('#button_action').val('Save');
      $('#action').val('add_product');
      $('#form_modal').modal('show');
      clearField();
    });

    function clearField() {
      $('#product_form')[0].reset();

      $("#category_error_message").hide();
      $("#category_id").removeClass("is-invalid");

      $("#brand_error_message").hide();
      $("#brand_id").removeClass("is-invalid");

      $("#name_error_message").hide();
      $("#name").removeClass("is-invalid");

      $("#price_error_message").hide();
      $("#price").removeClass("is-invalid");

      $("#quantity_error_message").hide();
      $("#quantity").removeClass("is-invalid");

      $("#supplier_error_message").hide();
      $("#supplier_id").removeClass("is-invalid");

      $("#status_error_message").hide();
      $("#status").removeClass("is-invalid");
    }

    var error_category = false;
    var error_brand = false;
    var error_name = false;
    var error_description = false;
    var error_price = false;
    var error_quantity = false;
    var error_status = true;

    $("#category_id").focusout(function(){
      checkCategory();
    });

    $("#brand_id").focusout(function(){
      checkBrand();
    });

    $("#name").focusout(function(){
      checkName();
    });

    $("#price").focusout(function(){
      checkPrice();
    });

    $("#quantity").focusout(function(){
      checkQuantity();
    });

    $("#supplier_id").focusout(function(){
      checkSupplier();
    });

    $("#status").focusout(function(){
      checkStatus();
    });

    function checkCategory(){
      if( $.trim( $('#category_id').val() ) == '' ){
        $("#category_error_message").html("Category is a required field.");
        $("#category_error_message").show();
        $("#category_id").addClass("is-invalid");
        error_category = true;
      } else {
        $("#category_error_message").hide();
        $("#category_id").removeClass("is-invalid");
      }
    }

    function checkBrand(){
      if( $.trim( $('#brand_id').val() ) == '' ){
        $("#brand_error_message").html("Brand is a required field.");
        $("#brand_error_message").show();
        $("#brand_id").addClass("is-invalid");
        error_brand = true;
      } else {
        $("#brand_error_message").hide();
        $("#brand_id").removeClass("is-invalid");
      }
    }

    function checkName(){
      if( $.trim( $('#name').val() ) == '' ){
        $("#name_error_message").html("Name is a required field.");
        $("#name_error_message").show();
        $("#name").addClass("is-invalid");
        error_name = true;
      } else {
        $("#name_error_message").hide();
        $("#name").removeClass("is-invalid");
      }
    }

    function checkPrice() {
      if( $.trim( $('#price').val() ) == '' ){
        $("#price_error_message").html("Price is a required field.");
        $("#price_error_message").show();
        $("#price").addClass("is-invalid");
        error_price = true;
      } else {
        $("#price_error_message").hide();
        $("#price").removeClass("is-invalid");
      }
    }

    function checkQuantity() {
      if( $.trim( $('#quantity').val() ) == '' ){
        $("#quantity_error_message").html("Quantity is a required field.");
        $("#quantity_error_message").show();
        $("#quantity").addClass("is-invalid");
        error_quantity = true;
      } else {
        $("#quantity_error_message").hide();
        $("#quantity").removeClass("is-invalid");
      }
    }

    function checkSupplier(){
      if( $.trim( $('#supplier_id').val() ) == '' ){
        $("#supplier_error_message").html("Supplier is a required field.");
        $("#supplier_error_message").show();
        $("#supplier_id").addClass("is-invalid");
        error_supplier = true;
      } else {
        $("#supplier_error_message").hide();
        $("#supplier_id").removeClass("is-invalid");
      }
    }

    function checkStatus(){
      if( $.trim( $('#status').val() ) == '' ){
        $("#status_error_message").html("Status is a required field.");
        $("#status_error_message").show();
        $("#status").addClass("is-invalid");
        error_status = true;
      } else {
        $("#status_error_message").hide();
        $("#status").removeClass("is-invalid");
      }
    }

    $('#product_form').on('submit', function(event){
      event.preventDefault();

      error_category = false;
      error_brand = false;
      error_name = false;
      error_price = false;
      error_quantity = false;
      error_supplier = false;
      error_status = false;
  
      checkCategory();
      checkBrand();
      checkName();
      checkPrice();
      checkQuantity();
      checkSupplier();
      checkStatus();
  
      if(error_category == false && error_brand == false && error_name == false && error_price == false && error_quantity == false && error_supplier == false && error_status == false ){
        $.ajax({
          type:"POST",
          data: $('#product_form').serialize(),
          url:"product_action.php",
          dataType:"json",
          success:function(data){
            if (data.status == 'success') {
              $('#form_modal').modal('hide');
              datatable.ajax.reload();
              swal("Success!", data.message, "success");
            }
          },error:function(){
            swal("Oops..!", "Something went wrong.", "error");
          }
        });
      }
    });

    $(document).on('click', '.update_product', function(){
      product_id = $(this).attr('id');
      $('#modal_title').text('Update Product');
      $('#button_action').val('Update');
      $('#action').val('update_product');
      $('#form_modal').modal('show');
      clearField();

      $.ajax({
        type:"POST",
        data: {action:'single_fetch', product_id:product_id},
        url:"product_action.php",
        dataType:"json",
        success:function(data){
          $('#product_id').val(data.product_id);
          $('#category_id').val(data.category_id);
          $('#brand_id').val(data.brand_id);
          $('#name').val(data.product_name);
          $('#description').val(data.product_description);
          $('#price').val(data.product_price);
          $('#quantity').val(data.product_quantity);
          $('#supplier_id').val(data.supplier_id);
          $('#status').val(data.product_status);
        }
      });
    });

    var product_id = '';
    $(document).on('click', '.view_product', function(){
      product_id = $(this).attr('id');
      $.ajax({
        type:"POST",
        data: {action:'single_fetch', product_id:product_id},
        url:"product_action.php",
        dataType:"json",
        success:function(data){
          $('#view_id').text(data['product_id']);
          $('#view_supplier_name').text(data['supplier_full_name']);
          $('#view_category_name').text(data['category_name']);
          $('#view_brand_name').text(data['brand_name']);
          $('#view_name').text(data['product_name']);
          $('#view_description').text(data['product_description']);
          $('#view_price').text(data['product_price']);
          $('#view_quantity').text(data['product_quantity']);
          $('#view_status').text(data['product_status']);
          $('#view_created_by').text(data['product_created_by']);
          $('#view_created_at').text(data['product_created_at']);
          $('#view_last_update_by').text(data['product_last_update_by']);
          $('#view_updated_at').text(data['product_updated_at']);
        }
      });
    });
  });
</script>