<?php
include('include/header.php');
?>

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="index.php">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Manage Brands</li>
  </ol>

  <!-- Brand DataTables -->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-table"></i> Brands Table
      <div class="float-right">
        <button type="button" id="add_brand" class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> Add New Brand</button>
        <a href="print_brand.php" target="_blank" class="btn btn-secondary btn-sm">
          <i class="fas fa-print"></i> print
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="brand_table" width="100%">
          <thead class="p-3 mb-2 bg-light font-weight-bold">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- MODALS -->
  <!-- Add Brand Modal -->
  <div class="modal fade" id="form_modal">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_title"></h5>
          <button class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="brand_form">
            <div class="form-group">
              <label>Brand Name <i class="text-danger">*</i></label>
              <input type="text" id="name" name="name" class="form-control" maxlength="50" autocomplete="off" placeholder="Enter brand name">
              <div id="name_error_message" class="text-danger"></div>
            </div>
            <div class="form-group">
              <label>Status <i class="text-danger"> *</i></label>
              <select name="status" id="status" class="custom-select">
                <option value="" hidden>Status</option>
                <option>Active</option>
                <option>Inactive</option>
              </select>
              <div id="status_error_message" class="text-danger"></div>
            </div>
            <br>
            <div class="modal-footer">
              <input type="hidden" name="brand_id" id="brand_id"/>
              <input type="hidden" name="action" id="action" value="add_brand"/>
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
              <input type="submit" name="button_action" id="button_action" class="btn btn-primary" value="Save" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Add Brand Modal -->

  <!-- View Brand Modal-->
  <div class="modal fade" id="view_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Brand Details</h5>
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
              <th>Name</th>
              <td>
                <div id="view_name"></div>
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
  <!-- End View Brand Modal -->

  <!-- Footer -->
<?php
include("include/footer.php");
?>

<script>

  $(document).ready(function(){
    var datatable = $('#brand_table').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
          url:'brand_action.php',
          type: 'POST',
          data: {action:'brand_fetch'}
      },
      'columns': [
          { data: 'brand_id' },
          { data: 'brand_name'},
          { data: 'brand_status'},
          { data: 'action',"orderable":false}
      ]
    });

    $('#add_brand').click(function(){
      $('#modal_title').text('Add Brand');
      $('#button_action').val('Save');
      $('#action').val('add_brand');
      $('#form_modal').modal('show');
      clearField();
    });

    function clearField() {
      $('#brand_form')[0].reset();
      $("#name_error_message").hide();
      $("#name").removeClass("is-invalid");
      $("#email_error_message").hide();
      $("#email").removeClass("is-invalid");
      $("#status_error_message").hide();
      $("#status").removeClass("is-invalid");
    }

    var error_name = false;
    var error_status = false;

    $("#name").focusout(function() {
      checkName();
    });

    $("#status").focusout(function() {
      checkStatus();
    });

    function checkName() {
      if( $.trim( $('#name').val() ) == '' ){
        $("#name_error_message").html("Brand name is a required field.");
        $("#name_error_message").show();
        $("#name").addClass("is-invalid");
        error_name = true;
      }
      else{
        $("#name_error_message").hide();
        $("#name").removeClass("is-invalid");
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

    $('#brand_form').on('submit', function(event){
      event.preventDefault();

      error_name = false;
      error_status = false;

      checkName();
      checkStatus();

      if(error_name == false && error_status == false ) {
        $.ajax({
          type:"POST",
          data: $('#brand_form').serialize(),
          url:"brand_action.php",
          dataType:"json",
          success:function(data){            
            if (data.status == 'success') {
              $('#form_modal').modal('hide');
              datatable.ajax.reload();
              swal("Success!", data.message, "success");
            } else if (data.status=='error') {
              $("#name_error_message").html("Brand name already exists");
              $("#name_error_message").show();
              $("#name").addClass("is-invalid");
            }
          },error:function(){
            swal("Oops..!", "Something went wrong.", "error");
          }
        });
      }
    });

    $(document).on('click', '.update_brand', function(){
      brand_id = $(this).attr('id');
      $('#modal_title').text('Update Brand');
      $('#button_action').val('Update');
      $('#action').val('update_brand');
      $('#form_modal').modal('show');
      clearField();

      $.ajax({
        type:"POST",
        data: {action:'single_fetch', brand_id:brand_id},
        url:"brand_action.php",
        dataType:"json",
        success:function(data){
          $('#brand_id').val(data.brand_id);
          $('#name').val(data.brand_name);
          $('#status').val(data.brand_status);
        }
      });
    });

    var brand_id = '';
    $(document).on('click', '.view_brand', function(){
      brand_id = $(this).attr('id');
      $.ajax({
        type:"POST",
        data: {action:'single_fetch', brand_id:brand_id},
        url:"brand_action.php",
        dataType:"json",
        success:function(data){
          $('#view_id').text(data['brand_id']);
          $('#view_name').text(data['brand_name']);
          $('#view_status').text(data['brand_status']);
          $('#view_created_by').text(data['brand_created_by']);
          $('#view_created_at').text(data['brand_created_at']);
          $('#view_last_update_by').text(data['brand_last_update_by']);
          $('#view_updated_at').text(data['brand_updated_at']);
        }
      });
    });
  });
</script>