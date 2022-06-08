<header class="main-header">
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">

      <a href="<?php echo base_url('/dashboard'); ?>" style='cursor: pointer;'>
        <div class="top-menu row">
          <div class="col-xs-3 col-sm-6 col-lg-8">
            <img src="<?php echo base_url('/assets/images/app/logo.png'); ?>" width="100" height="80">
      </a>
      </div>

      <div class="col-xs-6 col-sm-4 col-lg-3 login-menu-top text-right">
        <div class="row welcome-user">
          <div class="account-links">
            <a class="background-desktop">Welcome</a>
          </div>
          <div class="account-links">
            <a class="user-top-menu" href="<?php echo base_url('users/setting/') ?>"><?php echo $this->session->userdata('username'); ?></a><br>
          </div>
          <div class="account-links">
            <div class="userCircle"><?php echo ucfirst(substr($this->session->userdata('username'), 0, 1)); ?></div>
          </div>
          <div class="account-links">
              <!-- user permission info -->
              <a class="user-top-menu" href="<?php echo base_url('auth/logout') ?>"><span>Sign Out</span></a>
            </div>
        </div>
      </div>
      </div>
  </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->