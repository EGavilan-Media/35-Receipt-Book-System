<?php
include('include/header.php');
?>

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="index.php">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Manage Categories</li>
  </ol>

  <!-- Category DataTables -->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-table"></i> Categories Table
      <div class="float-right">
        <button type="button" id="add_category" class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> Add New Category</button>
        <a href="print_category.php" target="_blank" class="btn btn-secondary btn-sm">
          <i class="fas fa-print"></i> print
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="category_table" width="100%">
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
  <!-- Add Category Modal -->
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
          <form id="category_form">
            <div class="form-group">
              <label>Category Name <i class="text-danger">*</i></label>
              <input type="text" id="name" name="name" class="form-control" maxlength="50" autocomplete="off" placeholder="Enter category name">
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
            <input type="hidden" name="category_id" id="category_id"/>
              <input type="hidden" name="action" id="action" value="add_category"/>
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
              <input type="submit" name="button_action" id="button_action" class="btn btn-primary" value="Save" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Add Category Modal -->

  <!-- View Category Modal-->
  <div class="modal fade" id="view_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Category Details</h5>
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
  <!-- End View Category Modal -->

  <!-- Footer -->
<?php
include("include/footer.php");
?>

<script>

  $(document).ready(function(){
    var datatable = $('#category_table').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
          url:'category_action.php',
          type: 'POST',
          data: {action:'category_fetch'}
      },
      'columns': [
          { data: 'category_id' },
          { data: 'category_name'},
          { data: 'category_status'},
          { data: 'action',"orderable":false}
      ]
    });

    $('#add_category').click(function(){
      $('#modal_title').text('Add Category');
      $('#button_action').val('Save');
      $('#action').val('add_category');
      $('#form_modal').modal('show');
      clearField();
    });

    function clearField() {
      $('#category_form')[0].reset();
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
        $("#name_error_message").html("Category name is a required field.");
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

    
    $('#category_form').on('submit', function(event){
      event.preventDefault();

      error_name = false;
      error_status = false;

      checkName();
      checkStatus();

      if(error_name == false && error_status == false ) {
        $.ajax({
          type:"POST",
          data: $('#category_form').serialize(),
          url:"category_action.php",
          dataType:"json",
          success:function(data){ 
            if (data.status == 'success') {
              $('#form_modal').modal('hide');
              datatable.ajax.reload();
              swal("Success!", data.message, "success");
            } else if (data.status=='error') {
              $("#name_error_message").html("Category name already exists");
              $("#name_error_message").show();
              $("#name").addClass("is-invalid");
            }
          },error:function(){
            swal("Oops..!", "Something went wrong.", "error");
          }
        });
      }
    });

    $(document).on('click', '.update_category', function(){
      category_id = $(this).attr('id');
      $('#modal_title').text('Update Category');
      $('#button_action').val('Update');
      $('#action').val('update_category');
      $('#form_modal').modal('show');
      clearField();

      $('#update_category_modal').modal('show');
      $.ajax({
        type:"POST",
        data: {action:'single_fetch', category_id:category_id},
        url:"category_action.php",
        dataType:"json",
        success:function(data){
          $('#category_id').val(data.category_id);
          $('#name').val(data.category_name);
          $('#status').val(data.category_status);
        }
      });
    });

    var category_id = '';
    $(document).on('click', '.view_category', function(){
      category_id = $(this).attr('id');
      $.ajax({
        type:"POST",
        data: {action:'single_fetch', category_id:category_id},
        url:"category_action.php",
        dataType:"json",
        success:function(data){
          $('#view_id').text(data['category_id']);
          $('#view_name').text(data['category_name']);
          $('#view_status').text(data['category_status']);
          $('#view_created_by').text(data['category_created_by']);
          $('#view_created_at').text(data['category_created_at']);
          $('#view_last_update_by').text(data['category_last_update_by']);
          $('#view_updated_at').text(data['category_updated_at']);
        }
      });
    });
  });
</script>