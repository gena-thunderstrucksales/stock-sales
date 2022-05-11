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
            <h3 class="box-title">New User</h3>
          </div>
          <form role="form" action="<?php base_url('users/create') ?>" method="post">
            <div class="main-box-body">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <h4>GENERAL INFO</h4>
                  <div class="row padding-row">
                    <div class="col-xs-12 col-sm-6 col-lg-6  ">
                      <a>First name</a>
                      <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-lg-6 ">
                      <a>Last name</a>
                      <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" autocomplete="off" maxlength="64">
                    </div>
                  </div>
                  <div class="row padding-row">
                  <div class="ccol-xs-12 col-sm-6 col-lg-4">
                  <a>Role</a>
                      <select class="form-control" id="groups" name="groups">
                        <option value="">Select Groups</option>
                        <?php foreach ($group_data as $k => $v) : ?>
                          <option value="<?php echo $v['id'] ?>"><?php echo $v['group_name'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-lg-4 ">
                      <a>Username</a>
                      <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-lg-4 ">
                      <a>Email</a>
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" maxlength="64">
                    </div>
                  </div>
                  <div class="row padding-row">

                  <div class="col-xs-12 col-sm-6 col-lg-4">
                      <a>Password</a>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                      <a>Confirm password</a>
                      <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off" maxlength="64">
                    </div>
                
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                      <a>Phone</a>
                      <input type="text" class="form-control"  name="phone" min="0"  placeholder="Phone number"  autocomplete="off" maxlength="64">
                    </div>
                  </div>

                  <div class="row padding-row">

                  <div class="col-xs-12 col-sm-6 col-lg-4">
                      <a>Commission Humic%</a>
                      <input type="number" class="form-control"  min="0" step="0.01" onkeypress="validateNumber(event);" placeholder="Commission Andersons" autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                      <a>Commission ESS%</a>
                      <input type="number" class="form-control"  min="0" step="0.01" onkeypress="validateNumber(event);" placeholder="Commission ESS"  autocomplete="off" maxlength="64">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                      <a>GST</a>
                      <input type="text" class="form-control" id="number_gst" name="number_gst" placeholder="GST" autocomplete="off" maxlength="64">
                    </div>
                  </div>
                  </div>
                </div>
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('users/') ?>" class="btn btn-warning">Back</a>
              </div>
          </form>
        </div>
      </div>
  </section>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#groups").select2();

    $("#mainUserNav").addClass('active');
    $("#createUserNav").addClass('active');
  });
</script>