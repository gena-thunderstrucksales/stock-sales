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
            <h3 class="box-title">Edit Settings</h3>
          </div>
          <form role="form" action="<?php base_url('company/update') ?>" method="post">
            <div class="main-box-body">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <h4>GENERAL INFO</h4>
                  <div class="row padding-row">
                    <div class="col-xs-12 col-sm-8 col-lg-6">
                      <a>Business Name</a>
                      <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business Name" autocomplete="off" value="<?php echo $company_data['business_name']; ?>" maxlength="64">
                    </div>
                    <div class="col-xs-6 col-sm-8 col-lg-2">
                    <a for="brands">Currency</a><br>
                      <select class="form-control select_group" id="currency_id" name="currency_id"  required>
                        <option value="" selected disabled hidden>Choose here</option>
                        <?php foreach ($currencies as $k => $v) : ?>
                          <option value="<?php echo $v['id'] ?>" <?php if ($company_data['currency_id'] == $v['id']) {
                                                                    echo "selected='selected'";
                                                                  } ?>><?php echo $v['name'] ?>
                          </option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-1">
                      <a>Dealer Discount%</a>
                      <input type="text" class="form-control" id="dealer_discount" value="<?php echo $company_data['dealer_discount']; ?>" name="dealer_discount" placeholder="0.00" autocomplete="off" maxlength="5">
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-1">
                      <a>Cash Discount%</a>
                      <input type="text" class="form-control" id="cash_discount" value="<?php echo $company_data['cash_discount']; ?>" name="cash_discount" placeholder="0.00" autocomplete="off" maxlength="5">
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-2">
                      <a>Vat%</a>
                      <input type="text" class="form-control" id="vat_charge_value" name="vat_charge_value" placeholder="0.00" autocomplete="off" value="<?php echo $company_data['vat_charge_value']; ?>" maxlength="5">
                    </div>
                  </div>
                  <div class="row padding-row">
                    <div class="col-xs-12 col-sm-4 col-lg-6">
                      <a for=" customername">Company Name</a>
                      <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Customer Name" autocomplete="off" value="<?php echo $company_data_contact_info['contact_name']; ?>" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-3">
                      <a for=" tax_id">Email</a>
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" value="<?php echo $company_data_contact_info['email']; ?>" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-3">
                      <a>Phone No.</a>
                      <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone number" autocomplete="off" value="<?php echo $company_data_contact_info['phone_number']; ?>" maxlength="64">
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
                      <input type="text" class="form-control" id="bil_address" name="bil_address" placeholder="Address" autocomplete="off" value="<?php echo $company_addresses_billing_data['address']; ?>" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-5 col-lg-4 group-border-vertical">
                      <div class="col-xs-4">
                        <a>City</a>
                        <input type="text" class="form-control right-border-vertical" id="bil_city" name="bil_city" placeholder="City" autocomplete="off" value="<?php echo $company_addresses_billing_data['city']; ?>" maxlength="64">
                      </div>
                      <div class="col-xs-4" id="div-state">
                        <a>State/Province</a>
                        <input type="text" class="form-control left-border-vertical right-border-vertical" id="bil_state" name="bil_state" placeholder="State" autocomplete="off" value="<?php echo $company_addresses_billing_data['state']; ?>" maxlength="64">
                      </div>
                      <div class="col-xs-4" id="div-country">
                        <a>Country</a>
                        <input type="text" class="form-control left-border-vertical" id="bil_country" name="bil_country" placeholder="Country" autocomplete="off" value="<?php echo $company_addresses_billing_data['country']; ?>" maxlength="64">
                      </div>
                    </div>
                    <div class="col-xs-4 col-sm-3 col-lg-2">
                      <a>Zip/Postal Code</a>
                      <input type="text" class="form-control" id="bil_postal_code" name="bil_postal_code" placeholder="0000000" autocomplete="off" value="<?php echo $company_addresses_billing_data['postal_code']; ?>" maxlength="64">
                    </div>
                  </div>
                  <h4>SHIPPING ADDRESS</h4>
                  <div class="row padding-row">
                    <div class="col-xs-12 col-sm-4 col-lg-6">
                      <a>Address</a>
                      <input type="text" class="form-control" id="ship_address" name="ship_address" placeholder="Address" autocomplete="off" value="<?php echo $company_addresses_shipping_data['address']; ?>" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-5 col-lg-4 group-border-vertical">
                      <div class="col-xs-4">
                        <a>City</a>
                        <input type="text" class="form-control right-border-vertical" id="ship_city" name="ship_city" placeholder="City" autocomplete="off" value="<?php echo $company_addresses_shipping_data['city']; ?>" maxlength="64">
                      </div>
                      <div class="col-xs-4" id="div-state">
                        <a>State/Province</a>
                        <input type="text" class="form-control left-border-vertical right-border-vertical" id="ship_state" name="ship_state" placeholder="State" autocomplete="off" value="<?php echo $company_addresses_shipping_data['state']; ?>" maxlength="64">
                      </div>
                      <div class="col-xs-4" id="div-country">
                        <a>Country</a>
                        <input type="text" class="form-control left-border-vertical" id="ship_country" name="ship_country" placeholder="Country" autocomplete="off" value="<?php echo $company_addresses_shipping_data['country']; ?>" maxlength="64">
                      </div>
                    </div>
                    <div class="col-xs-4 col-sm-3 col-lg-2">
                      <a>Zip/Postal Code</a>
                      <input type="text" class="form-control" id="ship_postal_code" name="ship_postal_code" placeholder="0000000" autocomplete="off" value="<?php echo $company_addresses_shipping_data['postal_code']; ?>" maxlength="64">
                    </div>
                  </div>


                  <div class="dashed-line ">
                    <h3 class="box-title">NOTIFICATION SETTINGS</h3>
                    <h4>SMTP </h4>
                    <div class="row padding-row">

                      <div class="col-xs-6 col-sm-4 col-lg-4">
                        <a>Email</a>
                        <input type="text" class="form-control" id="smtp_user" name="smtp_user" placeholder="Smtp email" autocomplete="off" value="<?php echo $company_data['smtp_user']; ?>" maxlength="128">
                      </div>
                      <div class="col-xs-6 col-sm-4 col-lg-4">
                        <a>Password</a>
                        <input type="password" class="form-control" id="smtp_pass" name="smtp_pass" placeholder="Smtp password" autocomplete="off" value="<?php echo $company_data['smtp_pass']; ?>" maxlength="128">
                      </div>
                      <div class="col-xs-6 col-sm-4 col-lg-4">
                        <a>Host</a>
                        <input type="text" class="form-control" id="smtp_host" name="smtp_host" placeholder="Smtp host" autocomplete="off" value="<?php echo $company_data['smtp_host']; ?>" maxlength="128">
                      </div>
                      <div class="col-xs-6 col-sm-4 col-lg-4">
                        <a>Port</a>
                        <input type="text" class="form-control" id="smtp_port" name="smtp_port" placeholder="Smtp port" autocomplete="off" value="<?php echo $company_data['smtp_port']; ?>" maxlength="128">
                      </div>
                      <div class=" send-email col-xs-4 col-sm-4 col-lg-1">
                        <input type="checkbox" name="send_email" <?php if ($company_data['send_email'] == 1) {
                                                                    echo "checked='checked'";
                                                                  } ?> id="send_email" value="<?php echo $company_data['send_email']; ?>" class="minimal">
                        <a>Send email</a>
                      </div>
                    </div>

                    <br>
                    <div class="block-item">
                      <h4>NOTIFICATION EMAIL</h4>
                      <div>
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
                        <a id="add_row_email" class="btn btn-primary-form">+ ADD New EMAIL</a>
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
  var base_url = "<?php echo base_url(); ?>";
  var row_id = 0;
  var listAllTypesNotification = [];
  var remove_action_row;

  $(document).ready(function() {

    $("#companyNav").addClass('active');
    $("#message").wysihtml5();

    getTypesNotification();

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
          className: "col-xs-12 col-sm-5 col-lg-3"
        },
        {
          className: "col-xs-12 col-sm-5 col-lg-3"
        },
        {
          className: "col-xs-12 col-sm-2 col-lg-3"
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


    $("#add_row_email").unbind('click').bind('click', function() {
      var data_options = [];
      var empty_item = {
        type_notification_id: 0,
        id: "",
        email: ""
      };
      data_options.push(empty_item);
      addTableOptonRow(data_options);
    });

    function getTypesNotification() {
      $.ajax({
        url: base_url + '/company/getTypesNotification',
        type: 'post',
        data: "",
        dataType: 'json',
        error: function(request, error) {
          alert("Something is wrong! ( " + request.responseText + " )");
        },
        success: function(response) {
          if (response != null) {
            listAllTypesNotification = response["data"];
            getNotificationSettings();
          } else {
            alert('Something is wrong! (TypesNotification)');
          }
        }
      });
    }

    function addTableOptonRow(data_options) {
      if (data_options != null) {
        for (element of data_options) {
          var htmlOptions = '';
          htmlOptions += '<select class="form-control select_group" data-row-id="' + row_id + '" id="settigs_' + row_id + '" name="settigs[]" style="width:100%;" onchange="getSettingsId(' + row_id + ')"> required>';
          htmlOptions += '<option value=""></option>';

          var type_notification_id = element['type_notification_id'];
          var id = element['id'];
          var email = element['email'];

          for (value of listAllTypesNotification) {
            if (type_notification_id == value.id) {
              htmlOptions += '<option selected=selected value="' + value.id + '">' + value.value + '</option>'
            } else {
              htmlOptions += '<option value="' + value.id + '">' + value.value + '</option>'
            }
          }
          htmlOptions += '</select>';

          docTable.row.add([
            htmlOptions,
            '<input  id = "notification_email_' + row_id + '" type="text"  class="form-control" name="notification_email[]" value="' + email + '">',
            '<button type="button" class="label-base-icon-doc remove-doc" onclick="removeRow(\'' + row_id + '\')" ></button>',
            '<input type="hidden" readonly class="form-control" id= "type_notification_id' + row_id + '" name="type_notification_id[]" value="' + type_notification_id + '">',
          ]).node().id = "row_" + row_id;
          docTable.draw();
          row_id++;
        }
        $(".select_group").select2();
      } else {
        alert('Something is wrong! (add Table Row)');
      }
    }

    $("#copy_billing_address").unbind('click').bind('click', function() {
      $("#ship_address").val($("#bil_address").val());
      $("#ship_city").val($("#bil_city").val());
      $("#ship_state").val($("#bil_state").val());
      $("#ship_country").val($("#bil_country").val());
      $("#ship_postal_code").val($("#bil_postal_code").val());
      show_message("Done", 0);
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

    function getNotificationSettings() {
      $.ajax({
        url: base_url + '/company/getNotificationSettings',
        type: 'post',
        data: "",
        dataType: 'json',
        error: function(request, error) {
          alert("Something is wrong! ( " + request.responseText + " )");
        },
        success: function(response) {
          if (response != null) {
            var dataArray = response["data"];
            addTableOptonRow(dataArray);
          } else {
            alert('Something is wrong! (NotificationSettings)');
          }
        }
      });
    }

    $('#docTable tbody').on('click', 'tr', function() {
      idx = docTable.row(this).index();
      if (remove_action_row) {
        docTable.row(idx).remove().draw();
        remove_action_row = false;
      }
    });
  });

  function getSettingsId(row_id) {
    var product_id = $("#settigs_" + row_id).val();
    $("#type_notification_id" + row_id).val(product_id);
    row_id--;
  }

  function removeRow(id) {
    remove_action_row = true;
  }
</script>