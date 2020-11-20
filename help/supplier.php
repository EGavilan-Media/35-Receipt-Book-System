<?php
include('include/header.php');
?>

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="index.php">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Manage Suppliers</li>
  </ol>

  <!-- Supplier DataTables -->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-table"></i> Suppliers Table
      <div class="float-right">
        <button type="button" id="add_supplier" class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> Add New Supplier</button>
        <a href="print_supplier.php" target="_blank" class="btn btn-secondary btn-sm">
          <i class="fas fa-print"></i> print
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="supplier_table" width="100%">
          <thead class="p-3 mb-2 bg-light font-weight-bold">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Telephone</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- MODALS -->
  <!-- Add Supplier Modal -->
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
          <form id="supplier_form">
            <div class="form-group">
              <label>Full Name <i class="text-danger">*</i></label>
              <input type="text" id="full_name" name="full_name" class="form-control" maxlength="50" autocomplete="off" placeholder="Enter supplier full name">
              <div id="full_name_error_message" class="text-danger"></div>
            </div>
            <div class="form-group">
              <label>E-mail </i></label>
              <input type="text" id="email" name="email" class="form-control" maxlength="100" autocomplete="off" placeholder="Enter supplier E-mail">
              <div id="email_error_message" class="text-danger"></div>
            </div>
            <div class="form-group">
              <label>Address </i></label>
              <textarea id="address" name="address" class="form-control" rows="2" maxlength="500" autocomplete="off" placeholder="Enter supplier address"></textarea>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Telephone </i></label>
                <input type="text" id="telephone" name="telephone" class="form-control" maxlength="100" autocomplete="off" placeholder="Enter supplier telephone">
              </div>
              <div class="form-group col-md-6">
                <label>Status <i class="text-danger"> *</i></label>
                <select name="status" id="status" class="custom-select">
                  <option value="" hidden>Status</option>
                  <option>Active</option>
                  <option>Inactive</option>
                </select>
                <div id="status_error_message" class="text-danger"></div>
              </div>
            </div>
            <br>
            <div class="modal-footer">
              <input type="hidden" name="supplier_id" id="supplier_id"/>
              <input type="hidden" name="action" id="action" value="add_supplier"/>
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
              <input type="submit" name="button_action" id="button_action" class="btn btn-primary" value="Save" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Add Supplier Modal -->

  <!-- View Supplier Modal-->
  <div class="modal fade" id="view_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Supplier Details</h5>
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
              <th>Full Name</th>
              <td>
                <div id="view_full_name"></div>
              </td>
            </tr>
            <tr>
              <th>E-Mail</th>
              <td>
                <div id="view_email"></div>
              </td>
            </tr>
            <tr>
              <th>Address</th>
              <td>
                <div id="view_address"></div>
              </td>
            </tr>
            <tr>
              <th>Telephone</th>
              <td>
                <div id="view_telephone"></div>
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
  <!-- End View Supplier Modal -->

  <!-- Footer -->
<?php
include("include/footer.php");
?>

<script>
  $(document).ready(function(){
    var datatable = $('#supplier_table').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
          url:'supplier_action.php',
          type: 'POST',
          data: {action:'supplier_fetch'}
      },
      'columns': [
          { data: 'supplier_id' },
          { data: 'supplier_full_name'},
          { data: 'supplier_email'},
          { data: 'supplier_telephone'},
          { data: 'supplier_status'},
          { data: 'action',"orderable":false}
      ]
    });

    $('#add_supplier').click(function(){
      $('#modal_title').text('Add Supplier');
      $('#button_action').val('Save');
      $('#action').val('add_supplier');
      $('#form_modal').modal('show');
      clearField();
    });

    function clearField() {
      $('#supplier_form')[0].reset();
      $("#full_name_error_message").hide();
      $("#full_name").removeClass("is-invalid");
      $("#email_error_message").hide();
      $("#email").removeClass("is-invalid");
      $("#status_error_message").hide();
      $("#status").removeClass("is-invalid");
    }

    var error_full_name = false;
    var error_email = false;
    var error_status = false;

    $("#full_name").focusout(function() {
      checkFullName();
    });

    $("#email").focusout(function() {
      checkEmail();
    });

    $("#status").focusout(function() {
      checkStatus();
    });

    function checkFullName() {
      if( $.trim( $('#full_name').val() ) == '' ){
        $("#full_name_error_message").html("Full name is a required field.");
        $("#full_name_error_message").show();
        $("#full_name").addClass("is-invalid");
        error_full_name = true;
      }
      else{
        $("#full_name_error_message").hide();
        $("#full_name").removeClass("is-invalid");
      }

    }

    function checkEmail() {
      var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);

      if ($.trim($('#email').val()) == '') {
        $("#email_error_message").hide();
        $("#email").removeClass("is-invalid");
      } else if (!(pattern.test($("#email").val()))) {
        $("#email_error_message").html("Invalid email address");
        $("#email_error_message").show();
        error_email = true;
        $("#email").addClass("is-invalid");
      } else {
        $("#email_error_message").hide();
        $("#email").removeClass("is-invalid");
      }
    }

    function checkStatus() {
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

    $('#supplier_form').on('submit', function(event){
      event.preventDefault();

      error_full_name = false;
      error_email = false;
      error_status = false;

      checkFullName();
      checkEmail();
      checkStatus();

      if(error_full_name == false && error_email == false && error_status == false ) {
        $.ajax({
          type:"POST",
          data: $('#supplier_form').serialize(),
          url:"supplier_action.php",
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

    $(document).on('click', '.update_supplier', function(){
      supplier_id = $(this).attr('id');      
      $('#modal_title').text('Update Supplier');
      $('#button_action').val('Update');
      $('#action').val('update_supplier');
      $('#form_modal').modal('show');
      clearField();

      $.ajax({
        type:"POST",
        data: {action:'single_fetch', supplier_id:supplier_id},
        url:"supplier_action.php",
        dataType:"json",
        success:function(data){
          $('#supplier_id').val(data.supplier_id);
          $('#full_name').val(data.supplier_full_name);
          $('#email').val(data.supplier_email);
          $('#address').val(data.supplier_address);
          $('#telephone').val(data.supplier_telephone);
          $('#status').val(data.supplier_status);
        }
      });
    });

    var supplier_id = '';
    $(document).on('click', '.view_supplier', function(){
      supplier_id = $(this).attr('id');
      $.ajax({
        type:"POST",
        data: {action:'single_fetch', supplier_id:supplier_id},
        url:"supplier_action.php",
        dataType:"json",
        success:function(data){
          $('#view_id').text(data['supplier_id']);
          $('#view_full_name').text(data['supplier_full_name']);
          $('#view_email').text(data['supplier_email']);
          $('#view_address').text(data['supplier_address']);
          $('#view_telephone').text(data['supplier_telephone']);
          $('#view_status').text(data['supplier_status']);
          $('#view_created_by').text(data['supplier_created_by']);
          $('#view_created_at').text(data['supplier_created_at']);
          $('#view_last_update_by').text(data['supplier_last_update_by']);
          $('#view_updated_at').text(data['supplier_updated_at']);
        }
      });
    });
  });
</script>