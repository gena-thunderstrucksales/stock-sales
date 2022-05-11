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

        <div class="box">
          <!-- /.box-header -->
          <div class="box-body">
            <div class="box-header " id='box-header'>
              <h3 class="box-title">ALL PRODUCTS</h3><br></br>
            </div>


            <div id="add_item" class="w-40">
              <?php if (in_array('createProduct', $user_permission)) : ?>

                <select class="form-control select_group" id="status_publish" name="status_publish" onchange="onChangeStatusPublish()">
                  <option value="">All</option>
                  <?php foreach ($statuses as $k => $v) : ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                  <?php endforeach ?>
                </select>
        
                  <a href="<?php echo base_url('products/create') ?>" class="btn btn-primary">NEW PRODUCT</a>
           

            </div>
          <?php endif; ?>

          <table id="manageTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Brand</th>
                <th>Category Name</th>
                <th>Status</th>
                <?php if (in_array('updateProduct', $user_permission) || in_array('deleteProduct', $user_permission)) : ?>
                  <th></th>
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

<?php if (in_array('deleteProduct', $user_permission)) : ?>
  <!-- remove brand modal -->
  <div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Remove Product</h4>
        </div>

        <form role="form" action="<?php echo base_url('products/remove') ?>" method="post" id="removeForm">
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
  var base_url = "<?php echo base_url(); ?>";


  $(document).ready(function() {

    $("#mainProductNav").addClass('active');
    // $(".select_group").select2();

    // initialize the datatable 
    manageTable = $('#manageTable').DataTable({
      "pageLength": 25,
      language: {
        search: "",
        sLengthMenu: "_MENU_",
      },
      'order': [],
      "ajax": {
        "url": "<?php echo site_url('products/fetchProductData') ?>",
        "type": "POST",
        "data": function(data) {
          data.status_publish = $("#status_publish").val();
        }
      },
    });

    $('.dataTables_filter input[type="search"]').attr('placeholder', 'SEARCH').css({
      'width': '250px',
      'display': 'inline-block',
      'border-radius': '50px',
      'color': ' var(--label_login)',
      'border-color': ' var(--background_block_desktop)',
    });
  });

  function onChangeStatusPublish() {
    manageTable.ajax.reload();

  }

  function setStatusProduct(product_id, type_status_id) {
    if (product_id && type_status_id != null) {
      $.ajax({
        url: base_url + 'products/setStatusProduct',
        type: 'post',
        data: {
          product_id: product_id,
          type_status_id: type_status_id,
        },
        dataType: 'json',
        error: function(request, error) {
          manageTable.ajax.reload(null, false);
        },
        success: function(element_info) {
          manageTable.ajax.reload(null, false);
        } // /success
      });
    }
  }

  // remove functions 
  function removeFunc(id) {
    if (id) {
      $("#removeForm").on('submit', function() {

        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: {
            product_id: id
          },
          dataType: 'json',
          error: function(request, error) {
            alert("Something is wrong!  function removeFunc ( " + request.responseText + " )");
          },
          success: function(response) {

            manageTable.ajax.reload(null, false);

            if (response.success === true) {
              $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
                '</div>');
            } else {

              $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
                '</div>');
            }
          }
        });
        $("#removeModal").modal('hide');
        return false;
      });
    }
  }
</script>