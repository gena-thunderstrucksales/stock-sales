<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class=" col-xs-12 col-sm-12 col-lg-12">

                <div id="messages"></div>
                <div id="loader_page">
                    <a>Sending Email... </a>
                </div>
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
                        <div class='row'>
                            <div class="col-xs-12 col-sm-7 col-lg-8">
                                <h3 class="box-title">New Order</h3>
                            </div>
                            <div class="col-xs-12 col-sm-5 col-lg-4">
                                <input type="text" class="form-control text-center" disabled id="status_order" name="status_order" placeholder="status" autocomplete="off" value="ORDER STATUS: <?php echo $status_order ?>"></>
                                <span class="status-order label-panding"></span>
                            </div>
                        </div>

                    </div>
                    <!-- /.box-header -->
                    <form role="form" onsubmit="return to_submit();" action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal">
                        <div class="box-body">
                            <?php echo validation_errors(); ?>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">General</a></li>
                                <li role="presentation"><a href="#addProduct" aria-controls="addProduct" role="tab" data-toggle="tab">Add Products</a></li>
                                <li role="presentation"><a href="#addresses" aria-controls="addresses" role="tab" data-toggle="tab">Addresses</a></li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <h4>GENERAL INFO</h4>
                                    <div class="row padding-row ">
                                        <div class="col-xs-6 col-sm-4 col-lg-4">
                                            <a>Date</a>
                                            <input type="text" class="form-control" disabled id="date" name="date" placeholder="Data" autocomplete="off" value=<?php echo date(' Y-m-d ') ?>>
                                        </div>
                                        <div class="col-xs-6 col-sm-4 col-lg-4">
                                            <a>Time</a>
                                            <input type="text" class="form-control" disabled id="time" name="time" placeholder="Time" autocomplete="off" value=<?php echo date(' H:i:s A') ?>>
                                        </div>
                                        <div class="col-xs-6 col-sm-4 col-lg-4">
                                            <a>Number</a>
                                            <input type="text" class="form-control" disabled id="doc_number" name="doc_number" placeholder="0000000" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="row padding-row">
                                        <div class="col-xs-12 col-sm-8 col-lg-4">
                                            <a for="brands">Business Name</a><br>
                                            <select class="form-control select_group" id="customer_id" name="customer_id" onchange="onChangeCustomer()" required>
                                                <option value="" selected disabled hidden>Select here</option>
                                                <?php foreach ($customers as $k => $v) : ?>
                                                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>

                                        <div class="col-xs-12 col-sm-3 col-lg-2">
                                            <a>Type customer</a>
                                            <input type="text" class="form-control" id="type_customer" name="type_customer" placeholder="" autocomplete="off" disabled>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-lg-2">
                                            <a>Payment Type</a><br>
                                            <select class="form-control select_group" id="type_payment_id" name="type_payment_id" onchange="onChangePaymentType()">
                                                <option value="" selected disabled hidden>Select here</option>
                                                <?php foreach ($type_payments as $k => $v) : ?>
                                                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="col-xs-6 col-sm-4 col-lg-2">
                                            <a>Tax %</a>
                                            <input type="text" class="form-control" id="tax_name" name="tax_name" placeholder="" autocomplete="off" disabled>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-lg-2">
                                            <a for="brands">Currency</a><br>
                                            <select class="form-control select_group" id="currency_id" name="currency_id" required onchange="onChangeCurrency()">
                                                <?php foreach ($currencies as $k => $v) : ?>
                                                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row padding-row">
                                        <div class="col-xs-12 col-sm-4 col-lg-4">
                                            <a for=" customername">Customer Name</a>
                                            <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Customer Name" autocomplete="off" disabled>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-lg-2">
                                            <a>Volume Discount</a>
                                            <select class="form-control select_group" id="dealer_discount" onchange="onChangeDiscount()" name="dealer_discount">

                                                <?php foreach ($type_volume_discount as $k => $v) : ?>
                                                    <option value="<?php echo $v ?>"><?php echo $v . '%' ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-lg-2">
                                            <a>Early Bird Discount</a>
                                            <select class="form-control select_group" id="early_dealer_discount" onchange="onChangeDiscount()" name="early_dealer_discount">

                                                <?php foreach ($type_early_bird_discount as $k => $v) : ?>
                                                    <option value="<?php echo $v ?>"><?php echo $v . '%' ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="col-xs-6 col-sm-4 col-lg-2">
                                            <a for=" ">Email</a>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" disabled>
                                        </div>
                                        <div class="col-xs-6 col-sm-4 col-lg-2">
                                            <a>Phone No.</a>
                                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone number" autocomplete="off" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane " id="addresses">
                                    <h4>BILLING ADDRESS</h4>
                                    <div class="row padding-row">
                                        <div class="col-xs-12 col-sm-4 col-lg-5 ">
                                            <a>Address</a>
                                            <input type="text" class="form-control" id="bil_address" name="bil_address" placeholder="Address" autocomplete="off" disabled>
                                        </div>
                                        <div class="col-xs-12 col-sm-5 col-lg-5 group-border-vertical">
                                            <div class="col-xs-4">
                                                <a>City</a>
                                                <input type="text" class="form-control right-border-vertical" id="bil_city" name="bil_city" placeholder="City" autocomplete="off" disabled>
                                            </div>
                                            <div class="col-xs-4" id="div-state">
                                                <a>State/Province</a>
                                                <input type="text" class="form-control left-border-vertical right-border-vertical" id="bil_state" name="bil_state" placeholder="State" autocomplete="off" disabled>
                                            </div>
                                            <div class="col-xs-4" id="div-country">
                                                <a>Country</a>
                                                <input type="text" class="form-control left-border-vertical" id="bil_country" name="bil_country" placeholder="Country" autocomplete="off" disabled>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-3 col-lg-2">
                                            <a>Zip/Postal Code</a>
                                            <input type="text" class="form-control" id="bil_postal_code" name="bil_postal_code" placeholder="0000000" autocomplete="off" disabled>
                                        </div>
                                    </div>
                                    <h4>SHIPPING ADDRESS</h4>
                                    <div class="row padding-row">
                                        <div class="col-xs-12 col-sm-4 col-lg-5 ">
                                            <a>Address</a>
                                            <input type="text" class="form-control" id="ship_address" name="ship_address" placeholder="Address" autocomplete="off" disabled>
                                        </div>
                                        <div class="col-xs-12 col-sm-5 col-lg-5 group-border-vertical">
                                            <div class="col-xs-4">
                                                <a>City</a>
                                                <input type="text" class="form-control right-border-vertical" id="ship_city" name="ship_city" placeholder="City" autocomplete="off" disabled>
                                            </div>
                                            <div class="col-xs-4" id="div-state">
                                                <a>State/Province</a>
                                                <input type="text" class="form-control left-border-vertical right-border-vertical" id="ship_state" name="ship_state" placeholder="State" autocomplete="off" disabled>
                                            </div>
                                            <div class="col-xs-4" id="div-country">
                                                <a>Country</a>
                                                <input type="text" class="form-control left-border-vertical" id="ship_country" name="ship_country" placeholder="Country" autocomplete="off" disabled>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-3 col-lg-2">
                                            <a>Zip/Postal Code</a>
                                            <input type="text" class="form-control" id="ship_postal_code" name="ship_postal_code" placeholder="0000000" autocomplete="off" disabled>
                                        </div>
                                    </div>

                                    <input type="hidden" class="form-control" id="total_discount_volume_value" name="total_discount_volume_value" autocomplete="off">
                                    <input type="hidden" class="form-control" id="id_contact_info" name="id_contact_info" autocomplete="off">
                                    <input type="hidden" class="form-control" id="id_bill_address" name="id_bill_address" autocomplete="off">
                                    <input type="hidden" class="form-control" id="id_shipping_address" name="id_shipping_address" autocomplete="off">
                                    <input type="hidden" class="form-control" id="tax_id" name="tax_id" autocomplete="off">
                                    <input type="hidden" class="form-control" id="type_customer_id" name="type_customer_id" autocomplete="off">
                                </div>

                                <div role="tabpanel" class="tab-pane" id="addProduct">
                                    <div class="">
                                        <h4 class="box-title">ADD PRODUCTS</h4>
                                    </div>
                                    <div class="row padding-row ">
                                        <div class="col-xs-12 col-sm-12 col-lg-12 group-selection padding-row">
                                            <a>Category</a>
                                            <select class="form-control select_group" id="category" name="category">
                                                <option value="%">All</option>
                                                <?php foreach ($category as $k => $v) : ?>
                                                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row padding-row ">
                                        <div class="col-xs-12 col-sm-6 col-lg-5 padding-row">
                                            <table id="selecetTable" class="table table-bordered ">
                                                <thead>
                                                    <tr>
                                                        <th style="width:7%">ID</th>
                                                        <th>Product Name</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-lg-6 padding-row">
                                            <table id="optionsProductTable" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width:7%">ID</th>
                                                        <th>Options</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>


                                    <div class="add_product">
                                        <div class="col-xs-12 col-sm-6 col-lg-5" id="messages_add_product">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashed-line">
                                <h4>ORDER SUMMARY</h4>
                            </div>

                            <table id="docTable" class="table table-bordered ">
                                <thead>
                                    <tr class="row">
                                        <th class="hidden-xs col-sm-1 col-lg-1">ID </th>
                                        <th class="hidden-xs col-xs-12 col-sm-4 col-lg-4 name-product-item-table">Product Name</th>
                                        <th class="hidden-xs col-xs-6 col-sm-1 col-lg-1">Option </th>
                                        <th class="hidden-xs col-xs-6 col-sm-1 col-lg-1" type="text">Qty.</th>
                                        <th class="hidden-xs col-xs-6 col-sm-1 col-lg-1 text-center" type="text">Unit Cost </th>
                                        <th class="hidden-xs col-xs-6 col-sm-1 col-lg-1" type="text">Discount %</th>
                                        <th class="hidden-xs col-xs-6 col-sm-1 col-lg-1" type="text">Subtotal </th>
                                        <th class="hidden-xs col-xs-6 col-sm-1 col-lg-1" type="text">Total</th>
                                        <th class="hidden-xs col-xs-6 col-sm-1 col-lg-1"></th>
                                        <th class="hidden-xs"></th>
                                        <th class="hidden-xs"></th>
                                        <th class="hidden-xs"></th>
                                        <th class="hidden-xs"></th>
                                        <th class="hidden-xs"></th>
                                        <th class="hidden-xs"></th>
                                        <th class="hidden-xs"></th>
                                    </tr>
                                </thead>
                            </table>
                            <div>



                                <div class="total-order col-xs-12 col-sm-5 col-lg-4">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7 ">
                                            <b>Subtotal</b>
                                        </div>
                                        <div class="col-xs-6 col-sm-5 col-lg-3">
                                            <b> <input type="text" value="0.00" id="sub_total_table_order" class="font-weight-bold" name="sub_total_table_order" disabled></b>
                                            <input type="hidden" class="form-control" id="sub_total_table_order_value" name="sub_total_table_order_value" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7">
                                            <a id="volume_discount">Volume Discount</a>
                                        </div>
                                        <div class="col-xs-6 col-sm-5 col-lg-3">
                                            <input type="text" value="0.00" class="font-weight-bold" id="total_discount_volume" name="total_discount_volume" disabled>
                                            <input type="hidden" class="form-control" id="total_discount_volume_value" name="total_discount_valume_volue" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7">
                                            <b>Subtotal</b>
                                        </div>
                                        <div class="col-xs-6 col-sm-5 col-lg-3">
                                            <b> <input type="text" value="0.00" id="total_table_order" name="total_table_order" disabled></b>
                                            <input type="hidden" class="form-control" id="total_table_order_value" name="total_table_order_value" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7">
                                            <a id="early_bird_discount">Early Bird Discount</a>
                                        </div>
                                        <div class="col-xs-6 col-sm-5 col-lg-3">
                                            <input type="text" value="0.00" id="total_discount_early_bird" name="total_discount_early_bird" disabled>
                                            <input type="hidden" class="form-control" id="total_discount_early_bird_value" name="total_discount_early_bird_value" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7">
                                            <b>Subtotal</b>
                                        </div>
                                        <div class="col-xs-6 col-sm-5 col-lg-3">
                                            <b> <input type="text" value="0.00" id="total_after_discount_early_bird" name="total_after_discount_early_bird" disabled></b>
                                            <input type="hidden" class="form-control" id="total_after_discount_early_bird_value" name="total_after_discount_early_bird_value" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7">
                                            <a>Cash/Cheque Discount</a>
                                        </div>
                                        <div class="col-xs-6 col-sm-5 col-lg-3">
                                            <input type="text" value="0.00" id="total_discount_cash" name="total_discount_cash" disabled>
                                            <input type="hidden" class="form-control" id="total_discount_cash_value" name="total_discount_cash_value" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7">
                                            <b>Subtotal</b>
                                        </div>
                                        <div class="col-xs-6 col-sm-5 col-lg-3">
                                            <b> <input type="text" value="0.00" id="sub_total_order" name="sub_total_order" disabled></b>
                                            <input type="hidden" class="form-control" id="sub_total_order_value" name="sub_total_order_value" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7">
                                            <a>Shipping</a>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-lg-4">
                                            <input type="text" onkeypress="validateNumber(event);" onchange="culculeteTotal()" class="form-control" id="total_shipping_value" name="total_shipping_value" placeholder="0.00" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7">
                                            <a id="tax_rate">Tax</a>
                                        </div>
                                        <div class="col-xs-6 col-sm-5 col-lg-3">
                                            <input type="text" value="0.00" id="tax_order" name="tax_order" disabled>
                                            <input type="hidden" class="form-control" id="tax_order_value" name="tax_order_value" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-5 col-lg-7">
                                            <b>Total</b>
                                        </div>
                                        <div class="col-xs-6 col-sm-5 col-lg-3">
                                            <b> <input type="text" value="0.00" id="total_order" name="total_order" disabled></b>
                                            <input type="hidden" class="form-control" id="total_order_value" name="total_order_value" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a>Comments</a>
                            <input type="text" class="form-control" id="comments" name="comments" autocomplete="off"><br></br>

                            <input type="hidden" name="vat_charge_rate" value="<?php echo $company_data['vat_charge_value'] ?>" autocomplete="off">
                            <button type="submit" class="btn btn-primary">Create Order</button>
                            <a href="<?php echo base_url('orders/') ?>" class="btn btn-warning">Back</a>
                        </div>
                    </form>
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
    var selecetTable;
    var docTable;
    var product_id = -1;
    var option_id = -1;
    var editor;
    var row_id = 0;
    var dataProducts;
    var dataOptions;
    var it_count = 0;
    var current_it_count = 0;
    var currency_id = -1;
    var remove_action_row;
    var spinner;
    var vat_charge = 0;
    var dealer_discount = 0;
    var updateOrdersDiscount = false;
    var manual_cash_discount = 0;
    var early_dealer_discount = 0;

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

    function messages_add_product(message, status_message) {
        if (status_message == 0) {
            $("#messages_add_product").html('<div class="alert alert-info" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + message +
                '</div>');
        } else {
            $("#messages_add_product").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + message +
                '</div>');
        }
    }

    $(document).ready(function() {
        $('#category').change(function() { //button filter event click
            selecetTable.ajax.reload();
            cleanOptionsProductTable();
        });
        vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value'] : 0; ?>;
        $("#tax_rate").text('Tax (' + vat_charge + "%)");

        updateOrdersDiscount = <?php echo (in_array('updateOrdersDiscount', $user_permission)) ? 'false' : 'true'; ?>;
        early_dealer_discount = $("#early_dealer_discount").val();
        dealer_discount = $("#dealer_discount").val();

        document.getElementById("dealer_discount").disabled = updateOrdersDiscount;
        get_cash_discount();
        updateLabelsDiscounts(dealer_discount, early_dealer_discount);

        spinner = $('#loader_page');
        spinner.hide();

        $(".select_group").select2();
        selecetTable = $('#selecetTable').DataTable({
            language: {
                search: "",
                sLengthMenu: "_MENU_",
                searchPlaceholder: "SEARCH"
            },
            "scrollY": 300,
            "scrollX": false,
            "pageLength": 25,
            "order": [], //Initial no order.
            "ajax": {
                "url": "<?php echo site_url('orders/fetchAddProductTable') ?>",
                "type": "POST",
                "data": function(data) {
                    data.category = $("#category").val();
                }
            },
        });

        optionsProductTable = $('#optionsProductTable').DataTable({
            language: {
                search: "",
                sLengthMenu: "_MENU_",
                searchPlaceholder: "SEARCH"
            },
            "scrollY": 300,
            "scrollX": false,
            "pageLength": 25,
            "order": [], //Initial no order.
            "ajax": {
                "url": "<?php echo site_url('orders/fetchAddAttributeTable') ?>",
                "type": "POST",
                "data": function(data) {
                    data.product_id = product_id,
                        data.option_id = option_id,
                        data.currency_id = currency_id;
                }
            },
        });

        docTable = $('#docTable').DataTable({
            "scrollY": 480,
            "scrollX": false,
            "pageLength": 25,
            language: {
                search: "",
                sLengthMenu: "_MENU_",
                searchPlaceholder: "SEARCH"
            },
            "columns": [{
                    className: "hidden-xs col-sm-1 col-lg-1"
                },
                {
                    className: " col-xs-12 col-sm-4 col-lg-4 name-product-item-table"
                },
                {
                    className: "col-xs-4 col-sm-1 col-lg-1"
                },
                {
                    className: "col-xs-4 col-sm-1 col-lg-1"
                },
                {
                    className: "col-xs-4 col-sm-1 col-lg-1 text-center"
                },
                {
                    className: "col-xs-3 col-sm-1 col-lg-1"
                },
                {
                    className: "col-xs-3 col-sm-1 col-lg-1"
                },
                {
                    className: "col-xs-3 col-sm-1 col-lg-1"
                },
                {
                    className: "col-xs-3 col-sm-1 col-lg-1"
                },
                {
                    className: "hidden-xs"
                },
                {
                    className: "hidden-xs"
                },
                {
                    className: "hidden-xs"
                },
                {
                    className: "hidden-xs"
                },
                {
                    className: "hidden-xs"
                },
                {
                    className: "hidden-xs"
                },
                {
                    className: "hidden-xs"
                }
            ]

        });

        $('#selecetTable tbody').on('click', 'tr', function() {
            dataProducts = selecetTable.row(this).data();
            if (dataProducts) {
                product_id = dataProducts[0];
                currency_id = $('#currency_id').val();
                optionsProductTable.ajax.reload();
                $("#selecetTable tbody tr").removeClass('row_selected');
                $(this).addClass('row_selected');
                $("#messages_add_product").hide(); //just reload table
            }
        });

        $('#optionsProductTable tbody').on('dblclick', 'tr', function() {
            dataOptions = optionsProductTable.row(this).data();
            if (dataOptions) {
                option_id = dataOptions[0];

                optionsProductTable.ajax.reload();
                $("#optionsProductTable tbody tr").removeClass('row_selected');
                $(this).addClass('row_selected');

                var arrayInfoProduct = {
                    'nameProduct': dataProducts[1],
                    'idProduct': dataProducts[0],
                    "nameOption": dataOptions[1],
                    "idOption": dataOptions[0],
                };
                var full_name = arrayInfoProduct['nameProduct'] + ' ' + arrayInfoProduct['nameOption'];

                var id_item = arrayInfoProduct['idProduct'] + '_' + arrayInfoProduct['idOption'];
                var id_item_table = $("#col_check_" + id_item).val();

                if (id_item == id_item_table) {
                    messages_add_product('This item ' + full_name + ' is already in the table!', 1);
                    $("#messages_add_product").show();
                } else {
                    var price = dataOptions[2];
                    price = price.replace(/[^\d\.\-]/g, "");
                    var priceFloat = parseFloat(price);
                    getTableProductRow(1, true, priceFloat);
                }
            }
        });

        $('#docTable tbody').on('click', 'tr', function() {
            idx = docTable.row(this).index();
            if (remove_action_row) {
                docTable.row(idx).remove().draw();
                culculeteTotal();
                remove_action_row = false;
            }
        });
    });

    function getTableProductRow(qty, addRow, price) {
        currency_id = $('#currency_id').val();
        let type_payment_id = $("#type_payment_id").val();
        early_dealer_discount = $("#early_dealer_discount").val();
        discount_volume = $("#dealer_discount").val();

        if (price != 0) {
            var info_calculate = culculeteRowTable({
                price: price,
                discount_volume: discount_volume,
                discount_early_bird: early_dealer_discount,
                discount_cash: manual_cash_discount,
                qty: qty
            });
            if (addRow) {
                addRowToTable(info_calculate);
            } else {
                updateteRowTable(info_calculate);
            }
        } else {
            alert("Price is empty!");
        }
    }

    function onChangeCurrency() {
        if (docTable.data().count() > 0) {
            if (confirm('The table will clean, are you sure?')) {
                docTable.clear();
                docTable.draw();
            } else {
                $("#currency_id").val('');
            }
        }
        cleanOptionsProductTable();
    }

    function cleanOptionsProductTable() {
        if (optionsProductTable.data().count() > 0) {
            optionsProductTable.clear();
            optionsProductTable.draw();
        }
    }

    function get_cash_discount() {
        let type_payment_id = $("#type_payment_id").val();
        if (type_payment_id == 0) {
            manual_cash_discount = <?php echo ($company_data['cash_discount'] > 0) ? $company_data['cash_discount'] : 0; ?>;
        } else {
            manual_cash_discount = 0;
        }
        return manual_cash_discount;
    }

    function onChangePaymentType() {
        get_cash_discount();
        onChangeDiscount();
    }

    function updateLabelsDiscounts(dealer_discount, early_dealer_discount) {
        $("#volume_discount").text('Volume Discount (' + dealer_discount + "%)");
        $("#early_bird_discount").text('Early Bird Discount (' + early_dealer_discount + "%)");
    }

    function onChangeDiscount() {
        if (!updateOrdersDiscount) {
            dealer_discount = $("#dealer_discount").val();
            early_dealer_discount = $("#early_dealer_discount").val();
            updateLabelsDiscounts(dealer_discount, early_dealer_discount);

            var tableProductLength = $("#docTable tbody tr").length;
            for (x = 0; x < tableProductLength; x++) {
                var tr = $("#docTable tbody tr")[x];
                if ($(tr).attr('id')) {
                    var count = $(tr).attr('id');
                    id = count.substring(4);
                    price = $("#price_" + id).val();
                    qty = $("#qty_" + id).val();

                    if (early_dealer_discount) {
                        early_dealer_discount = early_dealer_discount;
                    } else {
                        early_dealer_discount = $("#discount_early_bird_" + id).val();
                    }

                    if (dealer_discount) {
                        dealer_discount = dealer_discount;
                    } else {
                        dealer_discount = $("#discount_" + id).val();
                    }

                    var info_calculate = culculeteRowTable({
                        price: price,
                        discount_volume: dealer_discount,
                        discount_early_bird: early_dealer_discount,
                        discount_cash: manual_cash_discount,
                        qty: qty
                    });
                    updateteRowTable(info_calculate);
                }
            }
            culculeteTotal();
        }
    }

    function cleanTable() {
        var flag = false;
        if (docTable.data().count() > 0) {
            if (confirm('The table will clean, are you sure?')) {
                docTable.clear();
                docTable.draw();
                flag = true;
            }
        }
        return flag;
    }

    function addRowToTable(info_calculate) {
        if (info_calculate) {
            sub_total = info_calculate['sub_total'];
            total = info_calculate['total'];
            sum_discount_early_bird = info_calculate['sum_discount_early_bird'];
            sum_discount_cash = info_calculate['sum_discount_cash'];
            sum_discount_volume = info_calculate['sum_discount_volume'];
            discount_volume = info_calculate['discount_volume'];
            price = info_calculate['price'];
            qty = 1;
            let discount_cash = 0;
            let discount_early_bird = 0;

            if (dataOptions && dataProducts) {
                id_product = dataProducts[0];
                name_product = dataProducts[1];
                id_option = dataOptions[0];
                name_option = dataOptions[1];
            } else {
                alert('Something is wrong! (DocTable.row.add)');
            }

            var rowNode = docTable.row.add([
                '<input id= "id_product_' + it_count +
                '" type="number" readonly class="form-control" name="product[]" value="' + id_product + '"> ',

                '<a class="visible-xs">Name product</a>' + name_product,
                '<a class="visible-xs">Option</a>' + name_option,
                '<a class="visible-xs text-center">Qty.</a> <input type="number" min="1" step="0.01" onkeypress="validateNumber(event);" class="form-control" id="qty_' + it_count +
                '" name="qty[]" value="' + qty + '" step="0" onchange="onChangeRowTable(\'' + it_count + '\')"> ',
                '<a class="visible-xs text-center">Unit Cost</a>  <input type="number" readonly class="form-control text-center"  id="price_' + it_count +
                '" name="price[]" value="' + price + '" step="0.01"> ',
                '<a class="visible-xs">Discount</a> <input type="number" readonly class="form-control discount_volume" id="discount_' + it_count +
                '" name="discount_volume[]" value="' + discount_volume + '" step="0.01">',
                '<a class="visible-xs">Subtotal</a> <input type="number" readonly class="form-control" id="sub_total_' + it_count +
                '" name="sub_total[]" " value="' + sub_total + '" step="0.01">',
                '<a class="visible-xs">Total</a> <input type="number" readonly class="form-control" id="total_' + it_count +
                '" name="total[]" " value="' + total + '" step="0.01">',
                '<button type="button" class="label-base-icon-doc remove-doc" onclick="removeRow(\'' + it_count +
                '\')" ></button>',
                '<input type="hidden" readonly class="form-control" id= "id_option_' + it_count +
                '" name="option_item_id[]" value="' + id_option + '">',
                '<input type="hidden" readonly class="form-control" id="sum_discount_early_bird_' + it_count +
                '" name="sum_discount_early_bird[]" value="' + sum_discount_early_bird + '">',
                '<input type="hidden" readonly class="form-control" id="sum_discount_cash_' + it_count +
                '" name="sum_discount_cash[]" value="' + sum_discount_cash + '">',
                '<input type="hidden" readonly class="form-control" id="sum_discount_volume_' + it_count +
                '" name="sum_discount_volume[]" value="' + sum_discount_volume + '">',
                '<input type="hidden" readonly class="form-control" id="discount_cash_' + it_count +
                '" name="discount_cash[]" value="' + discount_cash + '">',
                '<input type="hidden" readonly class="form-control" id="discount_early_bird_' + it_count +
                '" name="discount_early_bird[]" value="' + discount_early_bird + '">',
                '<input type="hidden" readonly class="form-control" id="col_check_' + id_product + '_' + id_option +
                '" name="col_check_[]" value="' + id_product + '_' + id_option + '">',
            ]).draw().node();

            $(rowNode).addClass("row");
            $(rowNode).attr("id", "row_" + it_count);
            it_count++;
            culculeteTotal();
            messages_add_product('This item ' + name_product + ' ' + name_option + ' is added in the table!', 0);
            $("#messages_add_product").show();

        } else {
            alert("Something is wrong! (Prices)");
        }
    }

    function onChangeRowTable(i) {
        current_it_count = i;
        var qty = $("#qty_" + i).val();
        product_id = $("#id_product_" + i).val();
        option_id = $("#id_option_" + i).val();
        let price = $("#price_" + i).val();
        if (qty) {
            getTableProductRow(qty, false, price);
        }
    }

    function updateteRowTable(info_calculate) {
        if (info_calculate) {
            sub_total = info_calculate['sub_total'];
            total = info_calculate['total'];
            sum_discount_early_bird = info_calculate['sum_discount_early_bird'];
            sum_discount_cash = info_calculate['sum_discount_cash'];
            sum_discount_volume = info_calculate['sum_discount_volume'];
            discount_volume = info_calculate['discount_volume'];

            var elms = document.querySelectorAll('.discount_volume');

            for (var i = 0; i < elms.length; i++) {
                elms[i].value = discount_volume; //  
            }

            $("#sum_discount_early_bird_" + current_it_count).val(sum_discount_early_bird);
            $("#sum_discount_cash_" + current_it_count).val(sum_discount_cash);
            $("#sum_discount_volume_" + current_it_count).val(sum_discount_volume);

            $("#sub_total_" + current_it_count).val(sub_total);
            $("#total_" + current_it_count).val(total);
        } else {
            alert("Something is wrong! (Prices)");
        }
        culculeteTotal();
    }

    function culculeteRowTable(data_row_table) {
        var price = parseFloat(data_row_table["price"]);
        var qty = parseFloat(data_row_table["qty"]);
        var discount_cash = parseFloat(data_row_table["discount_cash"]);
        var discount_early_bird = parseFloat(data_row_table["discount_early_bird"]);
        var discount_volume = parseFloat(data_row_table["discount_volume"]);

        var sum_discount_early_bird = 0;
        var sum_discount_cash = 0;
        var sum_discount_volume = 0;
        var sub_total = 0;
        var total = 0;

        if (discount_cash == 0 && discount_early_bird == 0 && discount_volume == 0) {
            sub_total = qty * price;
            total = sub_total;
        } else {
            sub_total = qty * price;

            if (discount_volume != 0) {
                sum_discount_volume = sub_total * discount_volume / 100;
            }
            if (discount_early_bird != 0) {
                sum_discount_early_bird = sub_total * discount_early_bird / 100;
            }
            if (discount_cash != 0) {
                sum_discount_cash = sub_total * discount_cash / 100;
            }
            total = sub_total - sum_discount_volume;
        }

        sub_total = (sub_total).toFixed(2);
        total = (total).toFixed(2);
        sum_discount_cash = (sum_discount_cash).toFixed(2);
        sum_discount_early_bird = (sum_discount_early_bird).toFixed(2);
        discount_volume = discount_volume.toFixed(2);
        price = (price).toFixed(2);
        return {
            sub_total: sub_total,
            total: total,
            sum_discount_early_bird: sum_discount_early_bird,
            sum_discount_cash: sum_discount_cash,
            sum_discount_volume: sum_discount_volume,
            discount_volume: discount_volume,
            price: price,
        };
    }

    // calculate the total amount of the order
    function culculeteTotal() {

        var total_discount_early_bird = 0;
        var total_discount_cash = 0;
        var total_discount_volume = 0;
        var total_table_order = 0;
        var sub_total_table_order = 0;
        var sub_total_order = 0;
        var total_order = 0;
        var total_after_discount_early_bird = 0;

        var tableProductLength = $("#docTable tbody tr").length;
        for (x = 0; x < tableProductLength; x++) {
            var tr = $("#docTable tbody tr")[x];
            if ($(tr).attr('id')) {
                var count = $(tr).attr('id');
                id = count.substring(4);

                total_discount_volume = Number(total_discount_volume) + parseFloat($("#sum_discount_volume_" + id).val());
                sub_total_table_order = Number(sub_total_table_order) + parseFloat($("#sub_total_" + id).val());
                total_table_order = Number(total_table_order) + parseFloat($("#total_" + id).val());
            } else {
                total_discount_volume = 0;
                sub_total_table_order = 0;
                total_table_order = 0;
            }
        }


        ////////////////////// discount volume ////////////////////////
        var sub_total_after_volume_discount = sub_total_table_order - total_discount_volume;

        ////////////////////// early dealer discount ////////////////////////
        var total_discount_early_bird = sub_total_after_volume_discount * Number(early_dealer_discount) / 100;
        var total_after_discount_early_bird = sub_total_after_volume_discount - total_discount_early_bird;

        ////////////////////// cash discount ////////////////////////
        var total_discount_cash = total_after_discount_early_bird * Number(manual_cash_discount) / 100;
        var sub_total_order = total_after_discount_early_bird - total_discount_cash;

        ////////////////////// total ////////////////////////
        total_shipping = parseFloat($("#total_shipping_value").val()) ? parseFloat($("#total_shipping_value").val()) : 0;
        var sub_total_before_tax = Number(sub_total_order) + Number(total_shipping);
        tax_order = Number(sub_total_before_tax) * Number(vat_charge) / 100;
        total_order = Number(sub_total_before_tax) + Number(tax_order);

        ////////////////////// format ////////////////////////
        total_discount_cash = (total_discount_cash).toFixed(2);
        total_discount_volume = (total_discount_volume).toFixed(2);
        total_discount_early_bird = (total_discount_early_bird).toFixed(2);
        sub_total_table_order = (sub_total_table_order).toFixed(2);

        total_table_order = (total_table_order).toFixed(2);
        tax_order = (tax_order).toFixed(2);
        total_order = (total_order).toFixed(2);
        sub_total_order = (sub_total_order).toFixed(2);

        total_after_discount_early_bird = (total_after_discount_early_bird).toFixed(2);

        //labels

        $("#sub_total_table_order").val(sub_total_table_order);
        $("#total_discount_volume").val(total_discount_volume);
        $("#total_table_order").val(total_table_order);

        $("#total_discount_early_bird").val(total_discount_early_bird);
        $("#total_after_discount_early_bird").val(total_after_discount_early_bird);

        $("#total_discount_cash").val(total_discount_cash);
        $("#sub_total_order").val(sub_total_order);

        $("#tax_order").val(tax_order);
        $("#total_order").val(total_order);

        //values
        $("#sub_total_table_order_value").val(sub_total_table_order);
        $("#total_discount_volume_value").val(total_discount_volume);
        $("#total_table_order_value").val(total_table_order);

        $("#total_discount_early_bird_value").val(total_discount_early_bird);
        $("#total_after_discount_early_bird_value").val(total_after_discount_early_bird);

        $("#total_discount_cash_value").val(total_discount_cash);
        $("#sub_total_order_value").val(sub_total_order);


        $("#total_shipping_value").val(total_shipping);
        $("#tax_order_value").val(tax_order);
        $("#total_order_value").val(total_order);
    }

    function removeRow(id) {
        remove_action_row = true;
    }

    // get the product information from the server
    function onChangeCustomer() {
        var array_select_customer = document.getElementById("customer_id");
        if (array_select_customer && array_select_customer.selectedOptions.length > 0) {
            customer_id = array_select_customer.selectedOptions[0].value;
            setContactIfoCustomer(customer_id, array_select_customer);
        }
    }


    function setContactIfoCustomer(customer_id, array_select_customer) {

        $.ajax({
            url: base_url + 'orders/getCustomerContactInfoValueById',
            type: 'post',
            data: {
                customer_id: customer_id
            },
            dataType: 'json',
            error: function(request, error) {
                alert("Something is wrong! ( " + request.responseText + " )");
            },
            success: function(element_info) {
                if (element_info['data']) {
                    var flag = true
                    current_type_id = $("#type_customer_id").val();
                    if (current_type_id != element_info['data']['type_customer_id']) {
                        if (docTable.data().count() > 0) {
                            if (confirm('The table will clean, are you sure?')) {
                                docTable.clear();
                                docTable.draw();
                                flag = true;
                            } else {
                                flag = false;
                                array_select_customer.value = '';
                            }
                        }
                    }

                    if (flag) {
                        $("#tax_id").val(element_info['data']['tax_info']['id']);
                        $("#tax_name").val(element_info['data']['tax_info']['name']);
                        $("#contact_name").val(element_info['data']['contact_info']['contact_name']);
                        $("#email").val(element_info['data']['contact_info']['email']);
                        $("#phone_number").val(element_info['data']['contact_info']['phone_number']);
                        $("#id_contact_info").val(element_info['data']['contact_info']['id']);
                        $("#type_customer").val(element_info['data']['type_customer']);
                        $("#type_customer_id").val(element_info['data']['type_customer_id']);


                        vat_charge = Number((element_info['data']['tax_info']['rate']));
                        $("#tax_rate").text('Tax (' + vat_charge + "%)");
                        culculeteTotal();

                        var billing = 0;
                        var shipping = 1;

                        var i;
                        for (i = 0; i < element_info['data']['addresses_info'].length; i++) {
                            element_address = element_info['data']['addresses_info'][i];

                            if (element_address['type_address_id'] == billing) {
                                $("#id_bill_address").val(element_address['id']);
                                $("#bil_address").val(element_address['address']);
                                $("#bil_city").val(element_address['city']);
                                $("#bil_state").val(element_address['state']);
                                $("#bil_country").val(element_address['country']);
                                $("#bil_postal_code").val(element_address['postal_code']);
                            } else if (element_address['type_address_id'] == shipping) {
                                $("#id_shipping_address").val(element_address['id']);
                                $("#ship_address").val(element_address['address']);
                                $("#ship_city").val(element_address['city']);
                                $("#ship_state").val(element_address['state']);
                                $("#ship_country").val(element_address['country']);
                                $("#ship_postal_code").val(element_address['postal_code']);
                            }
                        }
                    }

                }
            } // /success
        });
    }

    function to_submit() {
        const array_products = []
        const array_fields = []
        var error = "";
        array_fields.push('customer_id');
        array_fields.push('currency_id');
        array_fields.push('type_payment_id');


        var tableProductLength = $("#docTable tbody tr").length;
        if (!docTable.data().any()) {
            error += "Table is empty!";
            show_message(error);
            return false;
        }
        for (x = 0; x < tableProductLength; x++) {
            var tr = $("#docTable tbody tr")[x];
            var count = $(tr).attr('id');
            id = count.substring(4);
            var row = docTable.row('#' + count).data();

            val = $("#qty_" + id).val();
            if (val == 0 || val == null || val == '') {
                array_products.push(row[1]);
            }
        }

        if (array_products.length > 0) {
            error += "Table has some empty fields! (" + array_products + ") ";
        }

        array_empty_fields = checkEmptyFields(array_fields);

        if (array_empty_fields.length > 0) {
            error += " \nThere are several empty fields ! (" + array_empty_fields + ")";
        }
        if (!error == '') {
            show_message(error);
            return false;
        }
        spinner.show();
    }

    function checkEmptyFields(arr_fields) {
        const array_empty_fields = []
        for (const field of arr_fields) {
            value = $("#" + field).val();

            if (value == null || value == '') {
                array_empty_fields.push(field);
            }
        }
        return array_empty_fields;
    }
</script>