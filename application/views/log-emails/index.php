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

        <div class="box">
          <!-- /.box-header -->
          <div class="box-body">
            <div class="box-header" id='box-header'>
              <h3 class="box-title">Log emails </h3>
            </div>
   
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Order</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Info message</th>
                  <th>User</th>
                  <th>Status</th>
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
      'ajax': base_url + 'logEmails/fetchListItems',
      'order': []
    });
    $('.dataTables_filter input[type="search"]').attr('placeholder', 'SEARCH').css({
      'width': '250px',
      'display': 'inline-block',
      'border-radius': '50px',
      'color': ' var(--label_login)',
      'border-color': ' var(--background_block_desktop)',
    });
  });
</script>