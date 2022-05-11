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
            <h3 class="box-title">Edit Status Order</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" onsubmit="return to_submit();" action="<?php base_url('ordersStatus/create') ?>" method="post" class="form-horizontal">
            <div class="main-box-body">
              <div class="box-body">

                <?php echo validation_errors(); ?>
                <h4>GENERAL INFO</h4>
                <div class="row padding-row ">
                <div class="col-xs-6 col-sm-4 col-lg-4">
                    <a>Date</a>
                    <input type="text" class="form-control" disabled id="doc_date" name="doc_date[]" placeholder="Data" autocomplete="off" value="<?php echo $orders_status_header['doc_date']; ?>">
                  </div>
                  <div class="col-xs-6 col-sm-4 col-lg-4">
                    <a>Time</a>
                    <input type="text" class="form-control" disabled id="doc_time" name="doc_time[]" placeholder="Time" autocomplete="off" value="<?php echo $orders_status_header['doc_time']; ?>">
                  </div>
                  <div class="col-xs-6 col-sm-4 col-lg-4">
                  <a>Number</a>
                   <input type="text" class="form-control" disabled id="doc_number" name="doc_number[]" placeholder="0000000" autocomplete="off" value="<?php echo $orders_status_header['id']; ?>">
                  </div>
                </div>
                <input type="hidden" class="form-control" id="doc_date_doc_time" name="doc_date_doc_time" value="<?php echo $orders_status_header['doc_date_doc_time']; ?>" autocomplete="off">
                <h4>SELECT OPTIONS</h4>
                <div class="row padding-row ">
                <div class="col-xs-12 col-sm-12 col-lg-8">
                    <a for="brands">Business Name</a><br>
                    <select class="form-control select_group" id="customer_id" name="customer_id" onchange="getCustomerData()"  required>
                      <option value="" selected disabled hidden>Choose here</option>
                      <?php foreach ($customers as $k => $v) : ?>
                        <option value="<?php echo $v['id'] ?>" <?php if ($orders_status_header['customer_id'] == $v['id']) {
                                                                  echo "selected='selected'";
                                                                } ?>><?php echo $v['name'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-lg-4">
                    <a>Order Status</a><br>
                    <select class="form-control select_group" id="type_status_id" name="type_status_id">
                      <option value="" selected disabled hidden>Select here</option>
                      <?php foreach ($type_orders_status as $k => $v) : ?>
                        <option value="<?php echo $k ?>" <?php if ($orders_status_header['type_status_id'] == $k) {
                                                            echo "selected='selected'";
                                                          } ?>><?php echo $v ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="row padding-row ">
                <div class="col-xs-12 col-sm-6 col-lg-8">
                    <a>Order</a><br>
                    <select class="form-control select_group" id="order_id" name="order_id" onchange="onChangeOrder()" required>>
                      <option value="" selected disabled hidden>Select here</option>
                      <?php foreach ($list_orders as $k => $v) : ?>
                        <option value="<?php echo $v['id'] ?>" <?php if ($orders_status_header['order_id'] == $v['id']) {
                                                                  echo "selected='selected'";
                                                                } ?>><?php echo '' . $v['id'] . ' / ' .  date('Y-m-d', $v['date_time']) . ' / ' . $v['total_order']. ' $ ' ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>

              </div>
              <a>User name</a><br>
               <?php echo $orders_status_header['username']?>
            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Save Order Status</button>
              <a href="<?php echo base_url('ordersStatus/') ?>" class="btn btn-warning">Back</a>
            </div>
        </div>
      </div>
    </div>
</div>
</section>


<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";
  var current_status;
  var updateOrderSetComplete = false;
  var updateOrderSetApproved = false;
  var spinner;

  function getCustomerData() {
    var array_select_customer = document.getElementById("customer_id");
    if (array_select_customer && array_select_customer.selectedOptions.length > 0) {
      customer_id = array_select_customer.selectedOptions[0].value;
      setListOrders(customer_id);
    }
  }

  function setListOrders(customer_id) {
    $.ajax({
      url: base_url + 'ordersStatus/setListOrders',
      type: 'post',
      data: {
        customer_id: customer_id
      },
      dataType: 'json',
      error: function(request, error) {
        alert("Something is wrong! ( " + request.responseText + " )");
      },
      success: function(array_orders) {
        var html = '';
        if (array_orders) {
          for (const element of array_orders) {
            var d = new Date(element['date_time'] * 1000);

            date_time =
              d.getFullYear() + "-" +
              ("00" + (d.getMonth() + 1)).slice(-2) + "-" +
              ("00" + d.getDate()).slice(-2) ;

            details_order = '' + element['id'] + ' / ' + date_time + ' / ' + element['total_order']+' $';
            html += '<option value=' + element['id'] + '>' + details_order + ' </option>';
          }
        }
        $("#order_id").html(html);
      } // /success
    });
  }

  function onChangeOrder() {
    var order_id = $('#order_id').val();
    $.ajax({
      url: base_url + 'ordersStatus/onChangeOrder',
      type: 'post',
      data: {
        order_id: order_id
      },
      dataType: 'json',
      error: function(request, error) {
        alert("Something is wrong! ( " + request.responseText + " )");
      },
      success: function(array_orders) {
        if (array_orders) {
            current_status = array_orders['order_status'];
            updateOrderSetComplete =  array_orders['updateOrderSetComplete'];
            updateOrderSetApproved =  array_orders['updateOrderSetApproved'];
        }
      } // /success
    });
  }

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


  $(document).ready(function() {
    $(".select_group").select2();

    spinner = $('#loader_page');
    spinner.hide();
  });

  function to_submit() {
    const status_complete = 3;
    const status_approved = 1;
    const type_status_id = $('#type_status_id').val();

    if (current_status == status_complete && !updateOrderSetComplete || type_status_id == status_complete && !updateOrderSetComplete) {
      spinner.hide();
      show_message("You don't have enough permissions!");
      return false;
    } else if (current_status == status_approved && !updateOrderSetApproved || type_status_id == status_approved && !updateOrderSetApproved) {
      spinner.hide();
      show_message("You don't have enough permissions!");
      return false;
    } else {
        if (type_status_id == 1) {
          spinner.show();
        }
      return true;
    }

  }
</script>