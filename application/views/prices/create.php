<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class=" col-xs-12 col-sm-12 col-lg-12">

                <div id="messages"></div>
                <div id="loader_page">
                    <a>Uploading Products... </a>
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
                        <h3 class="box-title">New Price</h3>
                    </div>
                    <!-- /.box-header -->
                    <form role="form" onsubmit="return to_submit();" action="<?php base_url('prices/create') ?>" method="post" class="form-horizontal">
                        <div class="main-box-body">
                            <div class="box-body">

                                <?php echo validation_errors(); ?>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">General</a></li>
                                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Add Products</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="home">

                                        <h4>GENERAL INFO</h4>
                                        <div class="row padding-row ">
                                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                                <a>Date</a>
                                                <input type="text" class="form-control" disabled id="doc_date" name="date" placeholder="Data" autocomplete="off" value=<?php echo date(' Y-m-d ') ?>>
                                            </div>
                                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                                <a>Time</a>
                                                <input type="text" class="form-control" disabled id="doc_time" name="time" placeholder="Time" autocomplete="off" value=<?php echo date(' H:i:s A') ?>>
                                            </div>
                                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                                <a>Number</a>
                                                <input type="text" class="form-control" disabled id="doc_number" name="number" placeholder="0000000" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="row padding-row ">
                                            <div class=" col-xs-12 col-sm-4 col-lg-8">
                                                <a for=" customername">Price Name</a>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Price Name" autocomplete="off" maxlength="64">
                                            </div>
                                            <div class="col-xs-4 col-sm-4 col-lg-4">
                                                <a for="brands">Currency</a><br>
                                                <select class="form-control select_group" id="currency_id" name="currency_id" required>
                                                    <?php foreach ($currencies as $k => $v) : ?>
                                                        <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="profile">
                                        <!-- /.Select table -->
                                        <div class="">
                                            <h4 class="box-title">ADD PRODUCTS</h4>
                                        </div>
                                        <div class="row padding-row ">
                                            <div class="col-xs-12 col-sm-12 col-lg-3 group-selection padding-row">
                                                <a>Category</a>
                                                <select class="form-control select_group" id="category" name="category">
                                                    <?php foreach ($category as $k => $v) : ?>
                                                        <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-7 col-sm-7 col-lg-2">
                                                <br>
                                                <a id='fill_all_products' onClick="fillDocTableByCategory()" class="btn btn-primary-item-form ">FILL BY CATEGORY</a>
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

                                            <div class="col-xs-12 col-sm-6 col-lg-5 padding-row">

                                                <table id="optionsProductTable" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:7%">ID</th>
                                                            <th>Options</th>
                                                            <th>Part#</th>
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
                                <div class="row padding-row dashed-line ">
                                    <div class="col-xs-5 col-sm-5 col-lg-10">
                                        <h4>APPLY TO SPECIFIC PRODUCT</h4>
                                    </div>
                                    <div class="col-xs-7 col-sm-7 col-lg-2">
                                        <a id='fill_all_products' onClick="fillDocTableAllProducts()" class="btn btn-primary-item-form btn-long">FILL BY ALL PRODUCTS</a>
                                    </div>
                                </div>
                                <!-- /.Select table -->
                            </div>

                            <table id="docTable" class="table table-bordered ">
                                <thead>
                                    <tr class="row">
                                        <th class="hidden-xs col-sm-1 col-lg-1">ID </th>
                                        <th class="hidden-xs col-xs-12 col-sm-5 col-lg-4">Product Name</th>
                                        <th class="hidden-xs col-xs-12 col-sm-2 col-lg-2">Option </th>
                                        <th class="hidden-xs col-xs-6 col-sm-2 col-lg-2">Price </th>
                                        <th class="hidden-xs col-xs-8 col-sm-5 col-lg-3"></th>
                                        <th class="hidden-xs"></th>
                                    </tr>
                                </thead>
                            </table>

                            <a>User name</a><br>
                            <?php echo $username ?>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Save Price</button>
                                <a href="<?php echo base_url('prices/') ?>" class="btn btn-warning">Back</a>
                            </div>
                    </form>
                </div>
            </div>
    </section>
</div>


<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var selecetTable;
    var docTable;
    var idProduct = -1;
    var editor;
    var row_id = 0;
    var dataProducts;
    var it_count = 0;
    var remove_action_row;
    var spinner;

    $(document).ready(function() {
        $('#category').change(function() { //button filter event click
            selecetTable.ajax.reload();
            if (optionsProductTable.data().count() > 0) {
                optionsProductTable.clear();
                optionsProductTable.draw();
            } //just reload table
        });
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
                "url": "<?php echo site_url('prices/fetchAddProductTable') ?>",
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
                "url": "<?php echo site_url('prices/fetchAddAttributeTable') ?>",
                "type": "POST",
                "data": function(data) {
                    data.product_id = idProduct;
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
                    className: "col-xs-12 col-sm-5 col-lg-4 name-product-item-table"
                },
                {
                    className: "col-xs-12 col-sm-2 col-lg-2"
                },
                {
                    className: "col-xs-6 col-sm-2 col-lg-2"
                },
                {
                    className: "col-xs-8 col-sm-5 col-lg-3"
                },
                {
                    className: "hidden-xs"
                }
            ]


        });


        $('#selecetTable tbody').on('click', 'tr', function() {
            dataProducts = selecetTable.row(this).data();
            if (dataProducts) {
                idProduct = dataProducts[0];
                optionsProductTable.ajax.reload();
                $("#selecetTable tbody tr").removeClass('row_selected');
                $(this).addClass('row_selected'); //just reload table
                $("#messages_add_product").hide(); //just reload table
            }
        });

        $('#optionsProductTable tbody').on('dblclick', 'tr', function() {
            var dataOptions = optionsProductTable.row(this).data();
            optionsProductTable.ajax.reload(); //just reload table

            if (dataOptions && dataProducts) {
                var arrayInfoProduct = {
                    'nameProduct': dataProducts[1],
                    'idProduct': dataProducts[0],
                    "nameOption": dataOptions[1],
                    "idOption": dataOptions[0],
                };

                var id_item = arrayInfoProduct['idProduct'] + ',' + arrayInfoProduct['idOption'];
                var full_name = arrayInfoProduct['nameProduct'] + ' ' + arrayInfoProduct['nameOption'];

                var id_product_table = $("#idProduct" + arrayInfoProduct['idProduct']).val();
                var id_option_table = $("#idOption" + arrayInfoProduct['idOption']).val();
                var id_table = id_product_table + ',' + id_option_table;

                if (id_item == id_table) {
                    messages_add_product('This item ' + full_name + ' is already in the table!', 1);
                } else {
                    addRowToTable(arrayInfoProduct);
                    messages_add_product('This item ' + full_name + ' is added in the table!', 0);
                }
                $("#messages_add_product").show();
            } else {
                alert('Something is wrong!');
            }
        });
        $('#docTable tbody').on('click', 'tr', function() {
            idx = docTable.row(this).index();
            if (remove_action_row) {
                docTable.row(idx).remove().draw();
                remove_action_row = false;
            }
        });
    });

    function addRowToTable(dataInfo) {
        var nameProduct = dataInfo['nameProduct'];
        var idProduct = dataInfo['idProduct'];
        var nameOption = dataInfo['nameOption'];
        var idOption = dataInfo['idOption'];
        if (dataInfo['price']) {
            var price = dataInfo['price'];
        } else {
            var price = 0;
        }

        var rowNode = docTable.row.add([
            '<input  id = "idProduct' + idProduct +
            '" type="number" readonly class="form-control" name="product[] "value="' + idProduct +
            '"> ',
            nameProduct,
            '<input type="text" readonly class="form-control" name="option[]" value="' + nameOption +
            '"> ',
            '<input id = "price' + it_count + '"  type="number" min="0" step="0.01" onkeypress="validateNumber(event);"  class="form-control" name="price[]" value="' +
            price + '" step="0.01"> ',
            '<button type="button" class="label-base-icon-doc remove-doc" onclick="removeRow(\'' + it_count +
            '\')" ></button>',
            '<input id = "idOption' + idOption + '" type="hidden" readonly class="form-control" name="attribute_id[]"  value="' + idOption + '">',
        ]).draw().node();

        $(rowNode).addClass("row");
        $(rowNode).attr("id", "row_" + it_count);
        it_count++;
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

    function fillDocTableAllProducts() {
        spinner.show();
        docTable
            .clear()
            .draw();
        $.ajax({
            url: "<?php echo site_url('discounts/fillDocTableAllProducts') ?>",
            type: "POST",
            data: "",
            dataType: 'json',
            error: function(request, error) {
                alert("Something is wrong! ( " + request.responseText + " )");
            },
            success: function(response) {
                var dataArray = response["data"];
                var arrayLength = dataArray.length;
                for (var i = 0; i < arrayLength; i++) {
                    addRowToTable(dataArray[i]);
                }
                show_message("Done", 0);
                spinner.hide();
            }
        });
    }

    function fillDocTableByCategory() {
        var category = $("#category").val();
        spinner.show();
        docTable
            .clear()
            .draw();
        $.ajax({
            url: "<?php echo site_url('discounts/fillDocTableByCategory') ?>",
            type: "POST",
            data: {
                category_id: category,
            },
            dataType: 'json',
            error: function(request, error) {
                alert("Something is wrong! ( " + request.responseText + " )");
            },
            success: function(response) {
                var dataArray = response["data"];
                var arrayLength = dataArray.length;
                for (var i = 0; i < arrayLength; i++) {
                    addRowToTable(dataArray[i]);
                }
                show_message("Done", 0);
                spinner.hide();
            }
        });
    }

    function removeRow(id) {
        remove_action_row = true;
    }

    function to_submit() {
        const array_products = []
        const array_fields = []
        var error = "";
        array_fields.push('name');
        array_fields.push('currency_id');

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

            price = $("#price" + id).val();
            if (price == 0 || price == null || price == '') {
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