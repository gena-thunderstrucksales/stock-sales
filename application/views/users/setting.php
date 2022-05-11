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
            <h3 class="box-title">Setting User</h3>
          </div>
          <form role="form" action="<?php base_url('users/create') ?>" method="post">
          <div class="main-box-body">
              <div class="box-body">

              <?php echo validation_errors(); ?>

              <div class="form-group">
                <h4>GENERAL INFO</h4>
                <div class="row padding-row">
                  <div class="col-xs-12 col-sm-6 col-lg-6">
                    <a>First name</a>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" value="<?php echo $user_data['firstname'] ?>" autocomplete="off">
                  </div>
                  <div class="col-xs-12 col-sm-6 col-lg-6">
                    <a>Last name</a>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" value="<?php echo $user_data['lastname'] ?>" autocomplete="off">
                  </div>
                </div>
                <div class="row padding-row">
                  <div class="col-xs-12 col-sm-6 col-lg-6">
                    <a>Username</a>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $user_data['username'] ?>" autocomplete="off">
                  </div>
                  <div class="col-xs-12 col-sm-6 col-lg-6">
                    <a>Email</a>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $user_data['email'] ?>" autocomplete="off">
                  </div>
                </div>
                <div class="row padding-row">
       
                  <div class="col-xs-12 col-sm-6 col-lg-6">
                    <a>Password</a>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
                  </div>
                  <div class="col-xs-12 col-sm-6 col-lg-6">
                    <a>Confirm password</a>
                    <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off">
                  </div>
                </div>
                <div class="form-group">
                  <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Leave the password field empty if you don't want to change.
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              <a href="<?php echo base_url('users/') ?>" class="btn btn-warning">Back</a>
            </div>
          </form>
        </div>
      </div>

    </div>
    <!-- /.row -->


  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->