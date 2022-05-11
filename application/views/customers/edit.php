<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
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
            <h3 class="box-title">Edit Customer</h3>
          </div>
        </div>
        <form role="form"  onsubmit="return to_submit();"  action="<?php base_url('customers/update') ?>" method="post">
          <div class="main-box-body">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <div class="form-group">
                <h4>GENERAL INFO</h4>
                <div class="row padding-row">
                <div class="col-xs-12 col-sm-8 col-lg-6">
                    <a>Business Name</a>
                    <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business Name" autocomplete="off" value="<?php echo $customer_data['name']; ?>" maxlength="64">
                  </div>
                  <div class="col-xs-12 col-sm-3 col-lg-2">
                    <a>Type customer</a>
                    <select class="form-control select_group" id="type_customer_id" name="type_customer_id" required ">
                      <?php foreach ($typecustomer as $k => $v) : ?>
                        <option value="<?php echo $k  ?>" <?php if ($customer_data['type_customer_id'] == $k ) {
                                                                                        echo "selected='selected'";
                                                                                    } ?>><?php echo $v ?>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="col-xs-12 col-sm-3 col-lg-2">
                  <a>User</a>
                    <select class="form-control select_group" id="user_id" name="user_id" required onchange="onChangeUser()">
                      <?php foreach ($users as $k => $v) : ?>
                        <option value="<?php echo $v['id'] ?>" <?php if ($customer_data['user_id'] == $v['id']) {
                                                                                        echo "selected='selected'";
                                                                                    } ?>><?php echo $v['username'] ?>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="col-xs-12 col-sm-3 col-lg-2">
                    <a>Tax %</a>
                    <select class="form-control select_group" id="tax_id" name="tax_id" required ">
                      <?php foreach ($taxes as $k => $v) : ?>
                        <option value="<?php echo $v['id'] ?>" <?php if ($customer_data['tax_id'] == $v['id']) {
                                                                                        echo "selected='selected'";
                                                                                    } ?>><?php echo $v['name'] ?>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="row padding-row">
                <div class="col-xs-12 col-sm-4 col-lg-6 ">
                    <a for=" customername">Customer Name</a>
                    <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Customer Name" autocomplete="off" value="<?php echo $customers_data_contact_info['contact_name']; ?>" maxlength="64">
                  </div>
                  <div class="col-xs-6 col-sm-4 col-lg-4  ">
                    <a for=" tax_id">Email</a>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" value="<?php echo $customers_data_contact_info['email']; ?>" maxlength="64">
                  </div>
                  <div class="col-xs-6 col-sm-4 col-lg-2 ">
                    <a>Phone No.</a>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone number" autocomplete="off" value="<?php echo $customers_data_contact_info['phone_number']; ?>" maxlength="64">
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
                    <input type="text" class="form-control" id="bil_address" name="bil_address" placeholder="Address" autocomplete="off" value="<?php echo $customers_addresses_billing_data['address']; ?>" maxlength="64">
                  </div>
                  <div class="col-xs-12 col-sm-5 col-lg-4 group-border-vertical">
                    <div class="col-xs-4">
                      <a>City</a>
                      <input type="text" class="form-control right-border-vertical" id="bil_city" name="bil_city" placeholder="City" autocomplete="off" value="<?php echo $customers_addresses_billing_data['city']; ?>" maxlength="64">
                    </div>
                    <div class="col-xs-4" id="div-state">
                      <a>State/Province</a>
                      <input type="text" class="form-control left-border-vertical right-border-vertical" id="bil_state" name="bil_state" placeholder="State" autocomplete="off" value="<?php echo $customers_addresses_billing_data['state']; ?>" maxlength="64">
                    </div>
                    <div class="col-xs-4" id="div-country">
                      <a>Country</a>
                      <input type="text" class="form-control left-border-vertical" id="bil_country" name="bil_country" placeholder="Country" autocomplete="off" value="<?php echo $customers_addresses_billing_data['country']; ?>" maxlength="64">
                    </div>
                  </div>
                  <div class="col-xs-4 col-sm-3 col-lg-2">
                    <a>Zip/Postal Code</a>
                    <input type="text" class="form-control" id="bil_postal_code" name="bil_postal_code" placeholder="0000000" autocomplete="off" value="<?php echo $customers_addresses_billing_data['postal_code']; ?>" maxlength="64">
                  </div>
                </div>
                <h4>SHIPPING ADDRESS</h4>
                <div class="row padding-row">
                <div class="col-xs-12 col-sm-4 col-lg-6">
                    <a>Address</a>
                    <input type="text" class="form-control" id="ship_address" name="ship_address" placeholder="Address" autocomplete="off" value="<?php echo $customers_addresses_shipping_data['address']; ?>" maxlength="64">
                  </div>
                  <div class="col-xs-12 col-sm-5 col-lg-4 group-border-vertical">
                    <div class="col-xs-4">
                      <a>City</a>
                      <input type="text" class="form-control right-border-vertical" id="ship_city" name="ship_city" placeholder="City" autocomplete="off" value="<?php echo $customers_addresses_shipping_data['city']; ?>" maxlength="64">
                    </div>
                    <div class="col-xs-4" id="div-state">
                      <a>State/Province</a>
                      <input type="text" class="form-control left-border-vertical right-border-vertical" id="ship_state" name="ship_state" placeholder="State" autocomplete="off" value="<?php echo $customers_addresses_shipping_data['state']; ?>" maxlength="64">
                    </div>
                    <div class="col-xs-4" id="div-country">
                      <a>Country</a>
                      <input type="text" class="form-control left-border-vertical" id="ship_country" name="ship_country" placeholder="Country" autocomplete="off" value="<?php echo $customers_addresses_shipping_data['country']; ?>" maxlength="64">
                    </div>
                  </div>
                  <div class="col-xs-4 col-sm-3 col-lg-2">
                    <a>Zip/Postal Code</a>
                    <input type="text" class="form-control" id="ship_postal_code" name="ship_postal_code" placeholder="0000000" autocomplete="off" value="<?php echo $customers_addresses_shipping_data['postal_code']; ?>" maxlength="64">
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" class="form-control" id="user_id_value" name="user_id_value" value="<?php echo $user_id_value ?>" autocomplete="off">
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Save Customer</button>
            <a href="<?php echo base_url('customers/') ?>" class="btn btn-warning">Back</a>
          </div>
        </form>
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
    $(".select_group").select2();
    $("#maincustomerNav").addClass('active');
    $("#createcustomerNav").addClass('active');

    $("#copy_billing_address").unbind('click').bind('click', function() {
      $("#ship_address").val($("#bil_address").val());
      $("#ship_city").val($("#bil_city").val());
      $("#ship_state").val($("#bil_state").val());
      $("#ship_country").val($("#bil_country").val());
      $("#ship_postal_code").val($("#bil_postal_code").val());
      show_message_error("Done");
    });

    document.getElementById("user_id").disabled =  <?php echo (in_array('viewAllOrdersCustomers', $user_permission)) ? 'false' : 'true'; ?>;

    function show_message_error(message) {
      $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + message +
        '</div>');
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    }

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

 function onChangeUser() {
      $("#user_id_value").val($("#user_id").val());
  }

  function to_submit() {
        const array_fields = []
        var error = "";
        array_fields.push('business_name');
        array_fields.push('tax_id');
        array_fields.push('contact_name');
        array_fields.push('email');

        array_fields.push('bil_address');
        array_fields.push('bil_city');
        array_fields.push('bil_state');
        array_fields.push('bil_country');
        array_fields.push('bil_postal_code');

        array_fields.push('ship_address');
        array_fields.push('ship_city');
        array_fields.push('ship_state');
        array_fields.push('ship_country');
        array_fields.push('ship_postal_code');

        array_empty_fields = checkEmptyFields(array_fields);

        if (array_empty_fields.length > 0) {
            error += " \nThere are several empty fields ! (" + array_empty_fields + ")";
        }
        if (!error == '') {
            show_message(error);
            return false;
        }
    }

    function checkEmptyFields(arr_fields) {
        const array_empty_fields = []
        for (const field of arr_fields) {
            value = $("#" + field).val();

            if (value == 0 || value == null || value == '') {
                array_empty_fields.push(field);
            }
        }
        return array_empty_fields;
    }
</script>