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
                    <div class="box-header" id='box-header'>
                        <h3 class="box-title">ALL CURRENCIES</h3>
                    </div>
                    <div id="add_item">
                        <?php if (in_array('createCurrency', $user_permission)) : ?>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addCurrencyModal">NEW CURRENCY</button>
                            <br /> <br />
                        <?php endif; ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th>No</th>
                                    <th>Name</th>
                                    <?php if (in_array('updateCurrency', $user_permission) || in_array('deleteCurrency', $user_permission)) : ?>
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

<?php if (in_array('createCurrency', $user_permission)) : ?>
    <!-- create currency modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="addCurrencyModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Currency</h4>
                </div>
                <form role="form" action="<?php echo base_url('currencies/create') ?>" method="post" id="createCurrencyForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="currency_name">Currency Name</label>
                            <input type="text" class="form-control" id="currency_name" name="currency_name" placeholder="Enter currency name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="active">Status</label>
                            <select class="form-control" id="active" name="active">
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
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

<?php if (in_array('updateCurrency', $user_permission)) : ?>
    <!-- edit currency modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="editCurrencyModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Currency</h4>
                </div>
                <form role="form" action="<?php echo base_url('currencies/update') ?>" method="post" id="updateCurrencyForm">
                    <div class="modal-body">
                        <div id="messages"></div>
                        <div class="form-group">
                            <label for="edit_currency_name">Currency Name</label>
                            <input type="text" class="form-control" id="edit_currency_name" name="edit_currency_name" placeholder="Enter currency name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="edit_active">Status</label>
                            <select class="form-control" id="edit_active" name="edit_active">
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
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

<?php if (in_array('deleteCurrency', $user_permission)) : ?>
    <!-- remove currency modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeCurrencyModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Remove Currency</h4>
                </div>

                <form role="form" action="<?php echo base_url('currencies/remove') ?>" method="post" id="removeCurrencyForm">
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



<script type="text/javascript">
    var manageTable;

    $(document).ready(function() {

        $("#currencyNav").addClass('active');

        // initialize the datatable 
        manageTable = $('#manageTable').DataTable({
            "pageLength": 25,
            language: {
                search: "",
                sLengthMenu: "_MENU_"
            },
            'ajax': 'fetchCurrencyData',
            'order': []
        });

        $('.dataTables_filter input[type="search"]').attr('placeholder', 'SEARCH').css({
            'width': '250px',
            'display': 'inline-block',
            'border-radius': '50px',
            'color': ' var(--label_login)',
            'border-color': ' var(--background_block_desktop)',
        });

        // submit the create from 
        $("#createCurrencyForm").unbind('submit').on('submit', function() {
            var form = $(this);

            // remove the text-danger
            $(".text-danger").remove();

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form
                    .serialize(), // /converting the form data into array and sending it to server
                dataType: 'json',
                success: function(response) {

                    manageTable.ajax.reload(null, false);

                    if (response.success === true) {
                        $("#messages").html(
                            '<div class="alert alert-success alert-dismissible" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' +
                            response.messages +
                            '</div>');


                        // hide the modal
                        $("#addCurrencyModal").modal('hide');

                        // reset the form
                        $("#createCurrencyForm")[0].reset();
                        $("#createCurrencyForm .form-group").removeClass('has-error').removeClass(
                            'has-success');

                    } else {

                        if (response.messages instanceof Object) {
                            $.each(response.messages, function(index, value) {
                                var id = $("#" + index);

                                id.closest('.form-group')
                                    .removeClass('has-error')
                                    .removeClass('has-success')
                                    .addClass(value.length > 0 ? 'has-error' :
                                        'has-success');

                                id.after(value);

                            });
                        } else {
                            $("#messages").html(
                                '<div class="alert alert-warning alert-dismissible" role="alert">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' +
                                response.messages +
                                '</div>');
                        }
                    }
                }
            });

            return false;
        });


    });

    function editCurrency(id) {
        $.ajax({
            url: 'fetchCurrencyDataById/' + id,
            type: 'post',
            dataType: 'json',
            success: function(response) {

                $("#edit_currency_name").val(response.name);
                $("#edit_active").val(response.active);

                // submit the edit from 
                $("#updateCurrencyForm").unbind('submit').bind('submit', function() {
                    var form = $(this);

                    // remove the text-danger
                    $(".text-danger").remove();

                    $.ajax({
                        url: form.attr('action') + '/' + id,
                        type: form.attr('method'),
                        data: form
                            .serialize(), // /converting the form data into array and sending it to server
                        dataType: 'json',
                        success: function(response) {

                            manageTable.ajax.reload(null, false);

                            if (response.success === true) {
                                $("#messages").html(
                                    '<div class="alert alert-success alert-dismissible" role="alert">' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                    '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' +
                                    response.messages +
                                    '</div>');


                                // hide the modal
                                $("#editCurrencyModal").modal('hide');
                                // reset the form 
                                $("#updateCurrencyForm .form-group").removeClass('has-error')
                                    .removeClass('has-success');

                            } else {

                                if (response.messages instanceof Object) {
                                    $.each(response.messages, function(index, value) {
                                        var id = $("#" + index);

                                        id.closest('.form-group')
                                            .removeClass('has-error')
                                            .removeClass('has-success')
                                            .addClass(value.length > 0 ?
                                                'has-error' : 'has-success');

                                        id.after(value);

                                    });
                                } else {
                                    $("#messages").html(
                                        '<div class="alert alert-warning alert-dismissible" role="alert">' +
                                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                        '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' +
                                        response.messages +
                                        '</div>');
                                }
                            }
                        }
                    });

                    return false;
                });

            }
        });
    }

    function removeCurrency(id) {
        if (id) {
            $("#removeCurrencyForm").on('submit', function() {

                var form = $(this);

                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: {
                        currency_id: id
                    },
                    dataType: 'json',
                    success: function(response) {

                        manageTable.ajax.reload(null, false);

                        if (response.success === true) {
                            $("#messages").html(
                                '<div class="alert alert-success alert-dismissible" role="alert">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' +
                                response.messages +
                                '</div>');


                        } else {

                            $("#messages").html(
                                '<div class="alert alert-warning alert-dismissible" role="alert">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' +
                                response.messages +
                                '</div>');
                        }
                    }
                });
                $("#removeCurrencyModal").modal('hide');
                return false;
            });
        }
    }
</script>