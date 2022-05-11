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
            <h3 class="box-title">ALL USERS</h3>
          </div>
          <div id="add_item">
            <?php if (in_array('createUser', $user_permission)) : ?>
              <a href="<?php echo base_url('users/create') ?>" class="btn btn-primary btn.btn-flat ">+ NEW USER</a>
              <br /> <br />
            <?php endif; ?>
          </div>

          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Name</th>
                  <th>Role</th>

                  <?php if (in_array('updateUser', $user_permission) || in_array('deleteUser', $user_permission)) : ?>
                    <th>Action</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php if ($user_data) : ?>
                  <?php foreach ($user_data as $k => $v) : ?>
                    <tr>
                      <td><?php echo $v['user_info']['id']; ?></td>
                      <td><?php echo $v['user_info']['username']; ?></td>
                      <td><?php echo $v['user_info']['email']; ?></td>
                      <td><?php echo $v['user_info']['firstname'] . ' ' . $v['user_info']['lastname']; ?></td>
                      <td><?php echo $v['user_group']['group_name']; ?></td>

                      <?php if (in_array('updateUser', $user_permission) || in_array('deleteUser', $user_permission)) : ?>
                        <td>
                          <?php if (in_array('updateUser', $user_permission)) : ?>
                            <button type="button" onclick=window.location.href="<?php echo base_url('users/edit/' . $v['user_info']['id']) ?>" class="label-base-icon-doc edit-doc"></button>
                          <?php endif; ?>
                          <?php if (in_array('deleteUser', $user_permission)) : ?>
                            <button type="button" class="label-base-icon-doc remove-doc" onclick="removeFunc(<?php echo $v['user_info']['id'] ?>)" data-toggle="modal" data-toggle="modal" data-target="#removeModal"></button>
                          <?php endif; ?>
                        </td>
                      <?php endif; ?>
                    </tr>
                  <?php endforeach ?>
                <?php endif; ?>
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

<?php if (in_array('deleteUser', $user_permission)) : ?>
  <!-- remove brand modal -->
  <div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Remove User</h4>
        </div>

        <form role="form" action="<?php echo base_url('users/delete') ?>" method="post" id="removeForm">
          <div class="modal-body">
            <p>Do you really want to remove?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Remove User</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
<?php endif; ?>
<!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $("#mainUserNav").addClass('active');
    $("#manageUserNav").addClass('active');

    $('#manageTable').DataTable({
      language: {
        search: "",
        sLengthMenu: "_MENU_"
      },
    });
    $('.dataTables_filter input[type="search"]').attr('placeholder', 'SEARCH').css({
      'width': '250px',
      'display': 'inline-block',
      'border-radius': '50px',
      'color': ' var(--label_login)',
      'border-color': ' var(--background_block_desktop)',

    });
    ////customs finish
  });

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
            user_id: id
          },
          dataType: 'json',
          success: function(response) {

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
            manageTable.ajax.reload();
          }
        });
        // hide the modal
        $("#removeModal").modal('hide');
        return false;
      });
    }
  }
</script>