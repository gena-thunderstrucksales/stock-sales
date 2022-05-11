<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">

      <li class="treeview" id="dashboardMainMenu">
        <a href="#">
          <dev class="">
            <h3></h3>
          </dev>
        </a>
      </li>
      <li class="treeview" id="dashboardMainMenu">
        <a href="#">
          <dev class="item_menu">
            <h3>Menu</h3>
          </dev>
        </a>
      </li>

      <?php if (in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
        <li class="treeview" id="mainOrdersNav">
          <a href="#">
            <dev class="item_menu">
              <h4>ORDERS</h4>
            </dev>
            <span class="pull-right-container">
              <i class="fa pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <?php if (in_array('createOrder', $user_permission)) : ?>
              <li id="addOrderNav"><a href="<?php echo base_url('orders/create') ?>"><img src="<?php echo base_url('/assets/images/app/add_element.png'); ?>" width="20" height="20">Create New Order</a></li>
            <?php endif; ?>
            <?php if (in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
              <li id="manageOrdersNav"><a href="<?php echo base_url('orders') ?>"><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20">View All Orders</a></li>
            <?php endif; ?>
            <?php if (in_array('createOrdersStatus', $user_permission)) : ?>
              <li id="addOrderNav"><a href="<?php echo base_url('ordersStatus/create') ?>"><img src="<?php echo base_url('/assets/images/app/add_element.png'); ?>" width="20" height="20">Create New Status Order</a></li>
            <?php endif; ?>
            <?php if (in_array('updateOrdersStatus', $user_permission) || in_array('viewOrdersStatus', $user_permission) || in_array('deleteOrdersStatus', $user_permission)) : ?>
              <li id="manageOrdersStatusNav"><a href="<?php echo base_url('ordersStatus') ?>"><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20">View All Status Orders</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>

      <?php if (in_array('createPayment', $user_permission) || in_array('updatePayment', $user_permission) || in_array('viewPayment', $user_permission) || in_array('deletePayment', $user_permission)) : ?>
        <li class="treeview" id="mainPaymentsNav">
          <a href="#">
            <dev class="item_menu">
              <h4>PAYMENTS</h4>
            </dev>
            <span class="pull-right-container">
              <i class="fa pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <?php if (in_array('createPayment', $user_permission)) : ?>
              <li id="addPaymentsNav"><a href="<?php echo base_url('payments/create') ?>"><img src="<?php echo base_url('/assets/images/app/add_element.png'); ?>" width="20" height="20">Create New Payment</a></li>
            <?php endif; ?>
            <?php if (in_array('updatePayment', $user_permission) || in_array('viewPayment', $user_permission) || in_array('deletePayment', $user_permission)) : ?>
              <li id="managePaymentsNav"><a href="<?php echo base_url('payments') ?>"><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20">View All Payments</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>

      <?php if (in_array('createProduct', $user_permission) || in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)) : ?>
        <li class="treeview" id="mainProductNav">
          <a href="#">
            <dev class="item_menu">
              <h4>PRODUCTS</h4>
              <span class="pull-right-container">
                <i class="fa pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <?php if (in_array('createProduct', $user_permission)) : ?>
              <li id="addProductNav"><a href="<?php echo base_url('products/create') ?>"><img src="<?php echo base_url('/assets/images/app/add_element.png'); ?>" width="20" height="20">Create New Product</a></li>
            <?php endif; ?>
            <?php if (in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)) : ?>
              <li id="manageProductNav"><a href="<?php echo base_url('products') ?>"><img src="<?php echo base_url('/assets/images/app/all_products.png'); ?>" width="20" height="20">View All Products</a></li>
            <?php endif; ?>

            <?php if (in_array('createBrand', $user_permission) || in_array('updateBrand', $user_permission) || in_array('viewBrand', $user_permission) || in_array('deleteBrand', $user_permission)) : ?>
              <li id="brandNav">
                <a href="<?php echo base_url('brands/') ?>">
                  <i class=""></i><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20"><span>View All Brands</span>
                </a>
              </li>
            <?php endif; ?>

            <?php if (in_array('createCategory', $user_permission) || in_array('updateCategory', $user_permission) || in_array('viewCategory', $user_permission) || in_array('deleteCategory', $user_permission)) : ?>
              <li id="brandNav">
                <a href="<?php echo base_url('category/') ?>">
                  <i class=""></i><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20"><span>View All Categories</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (in_array('createAttribute', $user_permission) || in_array('updateAttribute', $user_permission) || in_array('viewAttribute', $user_permission) || in_array('deleteAttribute', $user_permission)) : ?>
              <li id="attributeNav">
                <a href="<?php echo base_url('attributes/') ?>">
                  <i class=""></i><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20"><span>View All Product Options</span>
                </a>
              </li>
            <?php endif; ?>

          </ul>
        </li>
      <?php endif; ?>


      <?php if (in_array('createCustomer', $user_permission) || in_array('updateCustomer', $user_permission) || in_array('viewCustomer', $user_permission) || in_array('deleteCustomer', $user_permission)) : ?>
        <li class="treeview" id="mainCustomerNav">
          <a href="#">
            <dev class="item_menu">
              <h4>CUSTOMERS</h4>
              <span class="pull-right-container">
                <i class="fa pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <?php if (in_array('createCustomer', $user_permission)) : ?>
              <li id="addCustomerNav"><a href="<?php echo base_url('customers/create') ?>"><img src="<?php echo base_url('/assets/images/app/add_element.png'); ?>" width="20" height="20">Create New Customer</a></li>
            <?php endif; ?>
            <?php if (in_array('updateCustomer', $user_permission) || in_array('viewCustomer', $user_permission) || in_array('deleteCustomer', $user_permission)) : ?>
              <li id="manageCustomerNav"><a href="<?php echo base_url('customers') ?>"><img src="<?php echo base_url('/assets/images/app/all_customers.png'); ?>" width="20" height="20">View All Customers</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>
      <?php if (in_array('createPrice', $user_permission) || in_array('updatePrice', $user_permission) || in_array('viewPrice', $user_permission) || in_array('deletePrice', $user_permission)) : ?>
        <li class="treeview" id="mainPricesNav">
          <a href="#">
            <dev class="item_menu">
              <h4>PRICES</h4>

          </a>
          <ul class="treeview-menu">
            <?php if (in_array('createPrice', $user_permission)) : ?>
              <li id="addPriceNav"><a href="<?php echo base_url('prices/create') ?>"><img src="<?php echo base_url('/assets/images/app/add_element.png'); ?>" width="20" height="20">Create New Price</a></li>
            <?php endif; ?>
            <?php if (in_array('updatePrice', $user_permission) || in_array('viewPrice', $user_permission) || in_array('deletePrice', $user_permission)) : ?>
              <li id="managePricesNav"><a href="<?php echo base_url('prices') ?>"><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20">View Manage Prices</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>

      <?php if (false) : ?>
        <li class="treeview" id="mainDiscountsNav">
          <a href="#">
            <dev class="item_menu">
              <h4>DISCOUNTS</h4>
          </a>
          <ul class="treeview-menu">
            <?php if (in_array('createDiscount', $user_permission)) : ?>
              <li id="addDiscountNav"><a href="<?php echo base_url('discounts/create') ?>"><img src="<?php echo base_url('/assets/images/app/add_element.png'); ?>" width="20" height="20">Create New Discount</a></li>
            <?php endif; ?>
            <?php if (in_array('updateDiscount', $user_permission) || in_array('viewDiscount', $user_permission) || in_array('deleteDiscount', $user_permission)) : ?>
              <li id="manageDiscountsNav"><a href="<?php echo base_url('discounts') ?>"><img src="<?php echo base_url('/assets/images/app/all_discounts.png'); ?>" width="20" height="20">View Manage Discounts</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>
      <?php if (in_array('viewReportAccouting', $user_permission)) : ?>
        <li class="treeview " id="mainOrdersNav">
          <a href="#">
            <dev class="item_menu">
              <h4>REPORTS</h4>
            </dev>
            <span class="pull-right-container">
              <i class="fa pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <?php if (in_array('viewReportAccouting', $user_permission)) : ?>
              <li id="addReportAccoutingNav"><a href="<?php echo base_url('reportAccouting/') ?>"><img src="<?php echo base_url('/assets/images/app/report.png'); ?>" width="20" height="20">Accounting Report </a></li>
            <?php endif; ?>
            <?php if (in_array('viewReportAccouting', $user_permission)) : ?>
              <li id="addReportAccoutingNav"><a href="<?php echo base_url('reportSales/') ?>"><img src="<?php echo base_url('/assets/images/app/report.png'); ?>" width="20" height="20">Sales Report </a></li>
            <?php endif; ?>
            <?php if (in_array('viewReportPricesList', $user_permission)) : ?>
              <li id="addReportPricesListNav"><a href="<?php echo base_url('reportPricesList/') ?>"><img src="<?php echo base_url('/assets/images/app/report.png'); ?>" width="20" height="20">Price List Report</a></li>
            <?php endif; ?>
            <?php if (false) : ?>
              <li id="addReportDiscountsListNav"><a href="<?php echo base_url('reportDiscounts/') ?>"><img src="<?php echo base_url('/assets/images/app/report.png'); ?>" width="20" height="20">Discounts Report</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>

      <?php if ($user_permission) : ?>
        <?php if (in_array('createUser', $user_permission) || in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)) : ?>
          <li class="treeview" id="mainUserNav">
            <a href="#">
              <dev class="item_menu">
                <h4>USERS</h4>
                <span class="pull-right-container">
                  <i class="fa pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" style="display: none;">
              <?php if (in_array('createUser', $user_permission)) : ?>
                <li id="createUserNav"><a href="<?php echo base_url('users/create') ?>"><img src="<?php echo base_url('/assets/images/app/add_element.png'); ?>" width="20" height="20">Create New User</a></li>
              <?php endif; ?>

              <?php if (in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)) : ?>
                <li id="manageUserNav"><a href="<?php echo base_url('users') ?>"><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20">Manage Users</a></li>
              <?php endif; ?>

              <?php if (in_array('createGroup', $user_permission) || in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)) : ?>
                <li id="brandNav">
                  <a href="<?php echo base_url('groups/') ?>">
                    <i class=""></i> <img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20"><span>Manage Roles</span>
                  </a>
                </li>
              <?php endif; ?>
            </ul>
          </li>
        <?php endif; ?>

      <?php endif; ?>

      <?php if (in_array('updateSetting', $user_permission)) : ?>
        <li class="treeview" id="mainSettingsNav">
          <a href="#">
            <dev class="item_menu">
              <h4>SETTINGS</h4>
              <span class="pull-right-container">
                <i class="fa pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <?php if (in_array('updateCompany', $user_permission)) : ?>
              <li id="companyNav"><a href="<?php echo base_url('company/') ?>"><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20"> <span>Edit Settings</span></a></li>
            <?php endif; ?>

            <?php if (in_array('viewLogEmails', $user_permission)) : ?>
              <li id="companyNav"><a href="<?php echo base_url('logEmails/') ?>"><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20"> <span>Log Emails</span></a></li>
            <?php endif; ?>
            <?php if (in_array('viewCurrency', $user_permission)) : ?>
              <li id="companyNav"><a href="<?php echo base_url('currencies/') ?>"><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20"> <span>View All Currencies</span></a></li>
            <?php endif; ?>
            <?php if (in_array('viewTax', $user_permission)) : ?>
              <li id="companyNav"><a href="<?php echo base_url('taxes/') ?>"><img src="<?php echo base_url('/assets/images/app/all_elements.png'); ?>" width="20" height="20"> <span>View All Taxes</span></a></li>
            <?php endif; ?>

          </ul>

        </li>

      <?php endif; ?>
    </ul>

  </section>
  <!-- /.sidebar -->
</aside>