<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
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
          <h3 class="box-title">New Role</h3>
        </div>
        <form role="form" action="<?php base_url('groups/create') ?>" method="post">
          <div class="main-box-body">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <div class="form-group">
                <a for="group_name">Role Name</a>
                <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter group name">
              </div>
              <div class="form-group">
                <a for="permission">Permission</a>

                <table class="table table-responsive table-striped ">
                  <thead>
                    <tr>
                    <th style="width:20%;"></th>
                      <th>Create</th>
                      <th>Update</th>
                      <th>View</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr>
                      <td>Dashboard</td>
                      <td>-</td>
                      <td> - </td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewDashboard" class="minimal"></td>
                      <td> - </td>
                    </tr>
                    <tr>
                      <td>Users</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createUser" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateUser" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewUser" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteUser" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Groups</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createGroup" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateGroup" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewGroup" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteGroup" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Categories</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createCategory" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateCategory" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewCategory" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteCategory" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Attributes</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createAttribute" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateAttribute" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewAttribute" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteAttribute" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Products</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createProduct" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateProduct" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewProduct" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteProduct" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Brands</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createBrand" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateBrand" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewBrand" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteBrand" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Orders</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createOrder" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateOrder" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewOrder" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteOrder" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Orders Status</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createOrdersStatus" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateOrdersStatus" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewOrdersStatus" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteOrdersStatus" class="minimal"></td>
                    </tr>

                    <tr>
                      <td>Orders Discount</td>
                      <td> - </td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateOrdersDiscount<" class="minimal"></td>
                      <td> - </td>
                      <td> - </td>
                    </tr>

                    <tr>
                      <td>Payments</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createPayment" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updatePayment" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewPayment" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deletePayment" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Discounts</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createDiscount" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateDiscount" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewDiscount" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteDiscount" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Prices</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createPrice" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updatePrice" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewPrice" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deletePrice" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Customers</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createCustomer" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateCustomer" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewCustomer" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteCustomer" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Report Accouting </td>
                      <td> - </td>
                      <td> - </td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewReportAccouting" class="minimal"></td>
                      <td> - </td>
                    </tr>
                    <tr>
                      <td>Report Price List </td>
                      <td> - </td>
                      <td> - </td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewReportPricesList" class="minimal"></td>
                      <td> - </td>
                    </tr>
                    <tr>
                      <td>Report Discounts </td>
                      <td> - </td>
                      <td> - </td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewReportDiscounts" class="minimal"></td>
                      <td> - </td>
                    </tr>
                    <tr>
                      <td>Company</td>
                      <td> - </td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateCompany" class="minimal"></td>
                      <td> - </td>
                      <td> - </td>
                    </tr>
                    <tr>
                      <td>Profile</td>
                      <td> - </td>
                      <td> - </td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewProfile" class="minimal"></td>
                      <td> - </td>
                    </tr>
                    <tr>
                      <td>Setting</td>
                      <td>-</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateSetting" class="minimal"></td>
                      <td> - </td>
                      <td> - </td>
                    </tr>
                    <tr>
                      <td>Log Emails</td>
                      <td>-</td>
                      <td> - </td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewLogEmails" class="minimal"></td>
                      <td> - </td>
                    </tr>
            
                    <tr>
                      <td>Currencies</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createCurrency" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateCurrency" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewCurrency" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteCurrency" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Taxes</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="createTax" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateTax" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewTax" class="minimal"></td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="deleteTax" class="minimal"></td>
                    </tr>
                    <tr>
                      <td>Set status order complete </td>
                      <td>-</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateOrderSetComplete" class="minimal"></td>
                      <td> - </td>
                      <td> - </td>
                    </tr>
                    <tr>
                      <td>Set status order approved </td>
                      <td>-</td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="updateOrderSetApproved" class="minimal"></td>
                      <td> - </td>
                      <td> - </td>
                    </tr>
                    <tr>
                      <td>All orders, customers</td>
                      <td>-</td>
                      <td> - </td>
                      <td><input type="checkbox" name="permission[]" id="permission" value="viewAllOrdersCustomers" class="minimal"></td>
                      <td> - </td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?php echo base_url('groups/') ?>" class="btn btn-warning">Back</a>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#mainGroupNav").addClass('active');
    $("#addGroupNav").addClass('active');

    $('input[type="checkbox"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-green',
      radioClass: 'iradio_minimal-green'
    });
  });
</script>