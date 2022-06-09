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
            <h3 class="box-title">Price List Report </h3>
          </div>
          <!-- /.box-header -->

          <h4>SETTINGS</h4>
          <form class="form" action="<?php echo base_url('ReportPricesList/generateReport') ?>" method="POST">
            <div class="main-box-body">
              <div class="box-body">

                <div class="row padding-row">
                  <div class="col-xs-12 col-sm-6 col-lg-3 ">
                    <a for="brands">Product</a>
                    <select class="form-control select_group" id="product_id" name="product_id" required>
                      <option value="'%'">All</option>
                      <?php foreach ($products as $k => $v) : ?>
                        <option value="<?php echo $v['id'] ?>" <?php if ($data_filter['product_id'] == $v['id']) {
                                                                  echo "selected='selected'";
                                                                } ?>><?php echo $v['name'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
               
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
                  <div class="col-xs-12 col-sm-6 col-lg-3 ">
                    <a>End Date</a>
                    <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date" autocomplete="off" value=<?php echo $data_filter['end_date'] ?>>
                  </div>

                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn.btn-flat">Create Report</button>
          </form>
          <br /> <br />
          <!-- /.box-body -->
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
                  <th>No Doc.</th>
                  <th>Product name</th>
                  <th>Option name</th>
                  <th>Price</th>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($results)) {
                  $counter = 1;
                ?>
                  <?php foreach ($results as $k => $c) :
                  ?>
                    <tr>
                      <td class=''><?php echo $counter++; ?></td>
                      <td class=''><?php echo $c['price_id']; ?></td>
                      <td class=''><?php echo $c['product_name']; ?></td>
                      <td class=''><?php
                                    echo  $c['option_name'];
                                    ?></td>
                      <td class=''><?php
                                    echo  '<sup>$</sup>'.number_format($c['price'],2);
                                    ?></td>

                    <?php endforeach ?>
                  <?php } ?>
              </tbody>

              <tbody>
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