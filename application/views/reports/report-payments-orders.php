<!-- Content Wrapper. Contains page content -->
<div class="report-page content-wrapper">
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class=" col-xs-12 col-sm-12 col-lg-12">

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

        <div class="box-header-over">
          <div class="box-header">
            <h3 class="box-title">Accounting Report</h3>
          </div>
          <!-- /.box-header -->

          <h4>SETTINGS</h4>
          <form class="form" action="<?php echo base_url('ReportAccouting/generateReport') ?>" method="POST">
            <div class="main-box-body">
              
              <div class="box-body">
                <div class="row padding-row">
                  <div class="col-xs-6 col-sm-6 col-lg-3">
                    <a for="brands">Customer</a>
                    <select class="form-control select_group" id="customer_id" name="customer_id" required>
                      <option value="'%'">All</option>
                      <?php foreach ($customers as $k => $v) : ?>
                        <option value="<?php echo $v['id'] ?>" <?php if ($data_filter['customer_id'] == $v['id']) {
                                                                  echo "selected='selected'";
                                                                } ?>><?php echo $v['name'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>

                  <div class="col-xs-6 col-sm-6 col-lg-3 ">
                    <a for="brands">User</a>
                    <select class="form-control select_group" id="user_id" name="user_id" required>
                      <option value="'%'">All</option>
                      <?php foreach ($users as $k => $v) : ?>
                        <option value="<?php echo $v['id'] ?>" <?php if ($data_filter['user_id'] == $v['id']) {
                                                                  echo "selected='selected'";
                                                                } ?>><?php echo $v['firstname'] . ' ' . $v['lastname']  ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="col-xs-6 col-sm-6 col-lg-3">
                    <a for=" tax_id">Start Date</a>
                    <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date" autocomplete="off" value=<?php echo $data_filter['start_date'] ?>>
                  </div>
                  <div class="col-xs-6 col-sm-6 col-lg-3">
                    <a>End Date</a>
                    <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date" autocomplete="off" value=<?php echo $data_filter['end_date'] ?>>
                  </div>
                </div>
                <div class="row padding-row ">
                  <div class="col-xs-6 col-sm-6 col-lg-3">
                    <a for="brands">Currency</a><br>
                    <select class="form-control select_group" id="currency_id" name="currency_id" required>
                      <option value="" selected disabled hidden>Select here</option>
                      <?php foreach ($currencies as $k => $v) : ?>
                        <option value="<?php echo $v['id'] ?>" <?php if ($data_filter['currency_id'] == $v['id']) {
                                                                  echo "selected='selected'";
                                                                } ?>><?php echo  $v['name']  ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn.btn-flat">Create Report</button>
          </form>
          <br /> <br />
        </div>
        <!-- /.box -->
        <div class="box-header-over">
          <div class="box-header ">
            <h3 class="box-title">Report Data</h3>
          </div>
          <a class="">Download Report</a>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="tableReport" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Customer / Order</th>
                  <th>Amount order</th>
                  <th>Amount payment</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($results)) {
                  $counter = 1;
                ?>

                  <?php foreach ($results['data_customers'] as $k => $c) :
                  ?>
                    <tr>
                      <td class='customer-report'><?php echo $counter++; ?></td>
                      <td class='customer-report'><?php echo $c['customer_name']; ?></td>
                      <td class='customer-report'><?php
                                                  echo  $c['total_order'];
                                                  ?></td>
                      <td class='customer-report'><?php
                                                  echo  $c['total_payment'];
                                                  ?></td>
                      <td class='customer-report'><?php
                                                  echo  $c['balance'];
                                                  ?></td>
                    </tr>

                    <?php foreach ($results['data_orders'] as $k => $o) : ?>
                      <?php if ($o['customer_id'] == $c['customer_id']) { ?>
                        <tr>
                          <td><?php echo $counter++; ?></td>
                          <td><?php echo '' . $o['order_id'] . ' /  ' .  date('Y-m-d', $o['data_order']); ?></td>
                          <td><?php
                              echo  $o['total_order'];
                              ?></td>
                          <td><?php
                              echo  $o['total_payment'];
                              ?></td>
                          <td><?php
                              echo $o['balance'];
                              ?></td>
                        </tr>
                      <?php } ?>
                    <?php endforeach ?>
                  <?php endforeach ?>
                <?php } ?>
                <?php if (isset($results)) {  ?>
                  <?php foreach ($results['data_total'] as $k => $o) : ?>
                    <tr>
                      <th class='total-report'> Total</th>
                      <th class='total-report'> </th>
                      <td class='total-report '><?php
                                                echo  $o['total_order'];
                                                ?></td>
                      <td class='total-report '><?php
                                                echo  $o['total_payment'];
                                                ?></td>
                      <td class='total-report '><?php
                                                echo $o['balance'];
                                                ?></td>
                    </tr>
                  <?php endforeach ?>
                <?php } ?>
              </tbody>
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
  var base_url = "<?php echo base_url(); ?>";

  $(document).ready(function() {
    $("#reportNav").addClass('active');
    $(".select_group").select2();

    manageTable = $('#tableReport').DataTable({
      language: {
        search: "",
        sLengthMenu: "_MENU_",
        searchPlaceholder: "SEARCH"
      },
      "scrollY": 500,
      "scrollX": false,
      "pageLength": 25,
      "order": [], //Initial no order.
      dom: 'Bfrtip',
      buttons: [
        'csv', 'excel',
      ],
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