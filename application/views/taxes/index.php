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
                        <h3 class="box-title">ALL TAXES</h3>
                    </div>
                    <div id="add_item">
                        <?php if (in_array('createTax', $user_permission)) : ?>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addTaxModal">NEW TAX</button>
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
                                    <th>Rate%</th>
                                    <?php if (in_array('updateTax', $user_permission) || in_array('deleteTax', $user_permission)) : ?>
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

<?php if (in_array('createTax', $user_permission)) : ?>
    <!-- create tax modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="addTaxModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Tax</h4>
                </div>
                <form role="form" action="<?php echo base_url('taxes/create') ?>" method="post" id="createTaxForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tax_name">Name</label>
                            <input type="text" class="form-control" id="tax_name" name="tax_name" placeholder="Enter tax name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="edit_rate">Rate%</label>
                            <input type="text" onkeypress=validateNumber(event); class="form-control" id="tax_rate" name="tax_rate" placeholder="Enter rate name" autocomplete="off">
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

<?php if (in_array('updateTax', $user_permission)) : ?>
    <!-- edit tax modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="editTaxModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Tax</h4>
                </div>
                <form role="form" action="<?php echo base_url('taxes/update') ?>" method="post" id="updateTaxForm">
                    <div class="modal-body">
                        <div id="messages"></div>
                        <div class="form-group">
                            <label for="edit_tax_name">Name</label>
                            <input type="text" class="form-control" id="edit_tax_name" name="edit_tax_name" placeholder="Enter tax name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="edit_rate">Rate%</label>
                            <input type="text" onkeypress=validateNumber(event); class="form-control" id="edit_rate" name="edit_rate" placeholder="Enter tax rate" autocomplete="off">
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

<?php if (in_array('deleteTax', $user_permission)) : ?>
    <!-- remove tax modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeTaxModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Remove Tax</h4>
                </div>

                <form role="form" action="<?php echo base_url('taxes/remove') ?>" method="post" id="removeTaxForm">
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

        $("#taxNav").addClass('active');

        // initialize the datatable 
        manageTable = $('#manageTable').DataTable({
            "pageLength": 25,
            language: {
                search: "",
                sLengthMenu: "_MENU_"
            },
            'ajax': 'fetchTaxData',
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
        $("#createTaxForm").unbind('submit').on('submit', function() {
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
                        $("#addTaxModal").modal('hide');

                        // reset the form
                        $("#createTaxForm")[0].reset();
                        $("#createTaxForm .form-group").removeClass('has-error').removeClass(
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

    function editTax(id) {
        $.ajax({
            url: 'fetchTaxesDataById/' + id,
            type: 'post',
            dataType: 'json',
            success: function(response) {

                var item = response[0];
                $("#edit_tax_name").val(item.name);
                $("#edit_rate").val(item.rate);
                $("#edit_active").val(item.active);

                // submit the edit from 
                $("#updateTaxForm").unbind('submit').bind('submit', function() {
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
                                $("#editTaxModal").modal('hide');
                                // reset the form 
                                $("#updateTaxForm .form-group").removeClass('has-error')
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

    function removeTax(id) {
        if (id) {
            $("#removeTaxForm").on('submit', function() {

                var form = $(this);

                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: {
                        tax_id: id
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
                $("#removeTaxModal").modal('hide');
                return false;
            });
        }
    }
</script>