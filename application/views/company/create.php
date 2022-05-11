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

        <div class="box-header-over">
          <div class="box-header">
            <h3 class="box-title">New Settings</h3>
          </div>
          <form role="form" action="<?php base_url('company/create') ?>" method="post">
            <div class="main-box-body">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <h4>GENERAL INFO</h4>
                  <div class="row padding-row">
                    <div class="col-xs-12 col-sm-8 col-lg-8">
                      <a>Business Name</a>
                      <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business Name" autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-4 col-sm-4 col-lg-2">
                      <a for="brands">Currency</a><br>
                      <select class="form-control select_group" id="currency_id" name="currency_id" required ">
                        <?php foreach ($currencies as $k => $v) : ?>
                          <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-1">
                      <a>Dealer Discount%</a>
                      <input type="text" class="form-control" id="dealer_discount" name="dealer_discount" placeholder="0.00" autocomplete="off" maxlength="5">
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-1">
                      <a>Cash Discount%</a>
                      <input type="text" class="form-control" id="cash_discount" name="cash_discount" placeholder="0.00" autocomplete="off" maxlength="5">
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-2">
                      <a>Vat%</a>
                      <input type="text" class="form-control" id="vat_charge_value" name="vat_charge_value" placeholder="0.00" autocomplete="off" maxlength="5">
                    </div>

                  </div>
                  <div class="row padding-row">
                    <div class="col-xs-12 col-sm-4 col-lg-6">
                      <a for=" customername">Customer Name</a>
                      <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Customer Name" autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-3">
                      <a for=" tax_id">Email</a>
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-3">
                      <a>Phone No.</a>
                      <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone number" autocomplete="off" maxlength="64">
                    </div>
                  </div>
                  <div class="row padding-row">
                    <div class="col-xs-5 col-sm-5 col-lg-4">
                      <h4>BILLING ADDRESS </h4>
                    </div>
                    <div class="col-xs-7 col-sm-7 col-lg-8">
                      <a id='copy_billing_address' class="btn btn-primary-item-form btn-long">USE AS SHIPPING ADDRESS</a>
                    </div>
                  </div>
                  <div class="row padding-row">
                    <div class="col-xs-12 col-sm-4 col-lg-6">
                      <a>Address</a>
                      <input type="text" class="form-control" id="bil_address" name="bil_address" placeholder="Address" autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-4 group-border-vertical">
                      <div class="col-xs-12 col-sm-5 col-lg-4 group-border-vertical">
                        <a>City</a>
                        <input type="text" class="form-control right-border-vertical" id="bil_city" name="bil_city" placeholder="City" autocomplete="off" maxlength="64">
                      </div>
                      <div class="col-xs-4" id="div-state">
                        <a>State/Province</a>
                        <input type="text" class="form-control left-border-vertical right-border-vertical" id="bil_state" name="bil_state" placeholder="State" autocomplete="off" maxlength="64">
                      </div>
                      <div class="col-xs-4" id="div-country">
                        <a>Country</a>
                        <input type="text" class="form-control left-border-vertical" id="bil_country" name="bil_country" placeholder="Country" autocomplete="off" maxlength="64">
                      </div>
                    </div>
                    <div class="col-xs-4 col-sm-3 col-lg-2">
                      <a>Zip/Postal Code</a>
                      <input type="text" class="form-control" id="bil_postal_code" name="bil_postal_code" placeholder="0000000" autocomplete="off" maxlength="64">
                    </div>
                  </div>
                  <h4>SHIPPING ADDRESS</h4>
                  <div class="row padding-row">
                    <div class="col-xs-12 col-sm-4 col-lg-6">
                      <a>Address</a>
                      <input type="text" class="form-control" id="ship_address" name="ship_address" placeholder="Address" autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-5 col-lg-4 group-border-vertical">
                      <div class="col-xs-4">
                        <a>City</a>
                        <input type="text" class="form-control right-border-vertical" id="ship_city" name="ship_city" placeholder="City" autocomplete="off" maxlength="64">
                      </div>
                      <div class="col-xs-4" id="div-state">
                        <a>State/Province</a>
                        <input type="text" class="form-control left-border-vertical right-border-vertical" id="ship_state" name="ship_state" placeholder="State" autocomplete="off" maxlength="64">
                      </div>
                      <div class="col-xs-4" id="div-country">
                        <a>Country</a>
                        <input type="text" class="form-control left-border-vertical" id="ship_country" name="ship_country" placeholder="Country" autocomplete="off" maxlength="64">
                      </div>
                    </div>
                    <div class="col-xs-4 col-sm-3 col-lg-2">
                      <a>Zip/Postal Code</a>
                      <input type="text" class="form-control" id="ship_postal_code" name="ship_postal_code" placeholder="0000000" autocomplete="off" maxlength="64">
                    </div>
                  </div>

                  <div class="dashed-line ">
                    <h3 class="box-title">NOTIFICATION SETTINGS</h3>
                    <h4>SMTP </h4>
                    <div class="row padding-row">
                      <div class="col-xs-6 col-sm-4 col-lg-4">
                        <a>Email</a>
                        <input type="text" class="form-control" id="smtp_user" name="smtp_user" placeholder="Smtp email" autocomplete="off" maxlength="64">
                      </div>
                      <div class="col-xs-6 col-sm-4 col-lg-4">
                        <a>Password</a>
                        <input type="password" class="form-control" id="smtp_pass" name="smtp_pass" placeholder="Smtp password" autocomplete="off" maxlength="64">
                      </div>
                      <div class="col-xs-6 col-sm-4 col-lg-4">
                        <a>Host</a>
                        <input type="text" class="form-control" id="smtp_host" name="smtp_host" placeholder="Smtp host" autocomplete="off" maxlength="64">
                      </div>
                      <div class="col-xs-6 col-sm-4 col-lg-4">
                        <a>Port</a>
                        <input type="text" class="form-control" id="smtp_port" name="smtp_port" placeholder="Smtp port" autocomplete="off" maxlength="64">
                      </div>
                      <div class=" send-email col-xs-4 col-sm-4 col-lg-1">
                        <input type="checkbox" name="send_email" id="send_email" value="0" class="minimal">
                        <a>Send email</a>
                      </div>
                    </div>
                    <div class="block-item">
                      <h4>NOTIFICATION EMAIL</h4>
                      <div class=" table-white">
                        <table id="docTable" class="  table table-bordered table-settings-notifications">
                          <thead>
                            <tr class=" row">
                              <th>Type</th>
                              <th>Recipients</th>
                              <th></th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>
                        <a onClick="show_message('Need to record before add some emails!')" id="add_row_email" class="btn btn-primary-form">+ ADD New EMAIL</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
          </form>
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
  $(document).ready(function() {
    var row_id = 0;

    $("#companyNav").addClass('active');
    $("#message").wysihtml5();

    docTable = $('#docTable').DataTable({
      "scrollY": 240,
      "scrollX": false,
      "pageLength": 25,
      language: {
        search: "",
        sLengthMenu: "_MENU_",
        searchPlaceholder: "SEARCH"
      },
      "columns": [{
          className: "col-md-3 col-xs-12"
        },
        {
          className: "col-md-3 col-xs-12"
        },
        {
          className: "col-md-3 col-xs-12"
        },
        {
          className: ""
        },

      ]
    });

    $('#send_email').on('click', function() {
      if ($(this).is(':checked')) {
        this.value = 1;
      } else {
        this.value = 0;
      }
    });

    $("#copy_billing_address").unbind('click').bind('click', function() {
      $("#ship_address").val($("#bil_address").val());
      $("#ship_city").val($("#bil_city").val());
      $("#ship_state").val($("#bil_state").val());
      $("#ship_country").val($("#bil_country").val());
      $("#ship_postal_code").val($("#bil_postal_code").val());
      show_message("Done", 0);
    });
  });

  function show_message(message, status_message) {
    if (status_message == 0) {
      $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + message +
        '</div>');
    } else {
      $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + message +
        '</div>');
    }
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
</script>