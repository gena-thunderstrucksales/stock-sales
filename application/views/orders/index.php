<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class=" col-xs-12 col-sm-12 col-lg-12">
        <div id="messages"></div>

        <?php if ($this->session->flashdata('success')) : ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif ($this->session->flashdata('error')) : ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>

        <div class="box datatable-box">
          <!-- /.box-header -->
          <div class="box-body">
            <div class="box-header " id='box-header'>
              <h3 class="box-title">ALL ORDERS</h3>
            </div>
            <div id="add_item">
              <?php if (in_array('createOrder', $user_permission)) : ?>
                <a href="<?php echo base_url('orders/create') ?>" class="btn btn-primary btn.btn-flat ">+ NEW ORDER</a>
                <br /> <br />
              <?php endif; ?>
            </div>

            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th >Date</th>
                  <th>Business Name</th>
                  <th>Order Status</th>
                  <th>Currency</th>
                  <th>Total</th>
                  <th>User</th>
                  <?php if (in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
                    <th>Action</th>
                  <?php endif; ?>
                </tr>
              </thead>

            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php if (in_array('deleteOrder', $user_permission)) : ?>
  <!-- remove brand modal -->
  <div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Remove Order</h4>
        </div>

        <form role="form" action="<?php echo base_url('orders/remove') ?>" method="post" id="removeForm">
          <div class="modal-body">
            <p>Do you really want to remove?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <?php endif; ?>

  <?php if (in_array('createPayment', $user_permission)) : ?>
  <!-- remove brand modal -->
  <div class="modal fade" tabindex="-1" role="dialog" id="makePaymentModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Create Payment</h4>
        </div>

        <form role="form" action="<?php echo base_url('payments/create') ?>" method="post" id="makePaymentForm">
          <div class="modal-body">
            <p>Amount</p>
          <input id="amount_payment" type="number" min="0" step="0.01" onkeypress="validateNumber(event);" class="form-control " name="amount_payment" step="0.01" value="0.00">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <?php endif; ?>


<script type="text/javascript">
  var manageTable;
  var base_url = "<?php echo base_url(); ?>";

  $(document).ready(function() {
    $("#mainOrdersNav").addClass('active');
    $("#manageOrdersNav").addClass('active');

    // initialize the datatable 
    manageTable = $('#manageTable').DataTable({
      "pageLength": 25,
      language: {
        search: "",
        sLengthMenu: "_MENU_"
      },
      'ajax': base_url + 'orders/fetchOrdersData',
      'order': []
    });
    $('.dataTables_filter input[type="search"]').attr('placeholder', 'SEARCH').css({
      'width': '250px',
      'border-radius': '50px',
      'color': ' var(--label_login)',
      'border-color': ' var(--background_block_desktop)',
    });
  });

  function setStatusOrder(order_id, type_status_id, customer_id) {
    if (order_id && type_status_id != null && customer_id != null) {
      $.ajax({
            url: base_url + 'OrdersStatus/create',
            type: 'post',
            data: {
              order_id: order_id,
              type_status_id: type_status_id,
              customer_id: customer_id,
            },
            dataType: 'json',
            error: function(request, error) {
               // alert("Something is wrong! ( " + request.responseText + " )");
               manageTable.ajax.reload(null, false);
            },
            success: function(element_info) {
              manageTable.ajax.reload(null, false);
            } // /success
        });
    }
  }

  // remove functions 
  function removeFunc(id) {
    if (id) {
      $("#removeForm").on('submit', function() {
        var form = $(this);
        // remove the text-danger
        $(".text-danger").remove();
        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: {
            order_id: id
          },
          dataType: 'json',
          error: function(request, error) {
                    alert("Something is wrong!  function removeFunc ( " + request.responseText + " )");
                },
          success: function(response) {

            manageTable.ajax.reload(null, false);

            if (response.success === true) {
              $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
                '</div>');

            } else {

              $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
                '</div>');
            }
          }
        });
        // hide the modal
        $("#removeModal").modal('hide');
        return false;
      });
    }
  }

  function makePayment(order_id, customer_id) {
    if (order_id ) {
      $("#makePaymentForm").on('submit', function() {
        let total_payment = $("#amount_payment").val();
        let type_payment_id = 3;
        var form = $(this);
        // remove the text-danger
        $(".text-danger").remove();
        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: {
            order_id: order_id,
            customer_id: customer_id,
            total_payment: total_payment,
            type_payment_id: type_payment_id,
          },
          dataType: 'json',
          error: function(request, error) {
            location.reload();
                },
          success: function(response) {

            location.reload();

            if (response.success === true) {
              $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
                '</div>');

            } else {

              $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
                '</div>');
            }
          }
        });
        // hide the modal
        $("#makePaymentModal").modal('hide');
        return false;
      });
    }
  }
</script>