<?php
include('include/header.php');
include('connection.php');
?>

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="index.php">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Manage Invoices</li>
  </ol>

  <!-- Product DataTables -->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-table"></i> Invoices Table
      <div class="float-right">
        <a href="create_invoice.php" class="btn btn-primary btn-sm" role="button" aria-pressed="true"><i class="fas fa-plus"></i> Add New Invoice</a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="invoice_table" width="100%">
          <thead class="p-3 mb-2 bg-light font-weight-bold">
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Sub Total</th>
              <th>Tax Amount</th>
              <th>Grand Total</th>
              <th>Created Date</th>
              <th width="15%">Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- Footer -->
<?php
include("include/footer.php");
?>

<script>
  $(document).ready(function(){
    var datatable = $('#invoice_table').DataTable({
      'processing': true,
      'serverSide': true,
      'ajax': {
        url:'invoice_action.php',
        type: 'POST',
        data: {action:'item_fetch'}
      },
      'columns': [
        { data: 'order_id' },
        { data: 'customer_full_name',"orderable":false},
        { data: 'order_total_before_tax'},
        { data: 'order_total_tax'},
        { data: 'order_total_after_tax'},
        { data: 'order_created_at'},
        { data: 'view',"orderable":false}
      ]
    });
  });
</script>