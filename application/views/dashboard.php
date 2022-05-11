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

        <div class='row select-currency'>
          <div class='select'>
            <div class="col-xs-12 col-sm-12 col-lg-2">
              <a for="brands">Currency</a><br>
              <select class="form-control select_group" id="currency_id" name="currency_id" required onchange="onChangeCurrency()">
                <?php foreach ($currencies as $k => $v) : ?>
                  <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
        </div>
        <div class="box">
              <!-- /.box-header -->
              <div class="box-body">
                <div class='table-report'>
                  <div class="box-header " id='box-header'>
                    <h3 class="box-title">SALES BY BRAND</h3>
                  </div>

                  <table id="tableSelesByBrand" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Total</th>
                      </tr>
                    </thead>

                  </table>
                </div>
              </div>
            </div>


        <div class='row'>
          <div class="col-xs-12 col-sm-6 col-lg-4">
            <div class="box">
              <!-- /.box-header -->
              <div class="box-body">
                <div class='table-report'>
                  <div class="box-header " id='box-header-total'>
                    <h3 class="box-title">TOTAL SALES</h3>
                  </div>

                  <table id="tableTotalSales" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th></th>
                      </tr>
                    </thead>

                  </table>
                  <a class="last-update">Last Updated <?php echo date("M j, Y") ?> </a>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>

          <div class="  col-xs-12 col-sm-6 col-lg-8">
          <div class="box">
          <!-- /.box-header -->
          <div class="box-body">
            <div class='table-report'>
              <div class="box-header " id='box-header'>
                <h3 class="box-title">TOP PERFORMING PRODUCTS</h3>
              </div>

              <table id="tableTopPerformingProduct" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Total</th>
                  </tr>
                </thead>

              </table>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
            <!-- /.box-body -->
          </div>
        </div>

        <div class="box">
          <!-- /.box-header -->
          <div class="box-body">
            <div class='table-report'>
              <div class="box-header " id='box-header'>
                <h3 class="box-title">TOP PERFORMING TEAM MEMBERS</h3>
              </div>

              <table id="tableTopPerformingMembers" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Month Total</th>
                  </tr>
                </thead>

              </table>
            </div>
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

<script type="text/javascript">
  var topPerformingProduct;
  var base_url = "<?php echo base_url(); ?>";
  var tableTopPerformingProduct;
  $(".select_group").select2();

  $(document).ready(function() {
    // initialize the datatable 
    tableTopPerformingProduct = $('#tableTopPerformingProduct').DataTable({
      "bLengthChange": false,
      language: {
        search: "",
        sLengthMenu: "_MENU_"
      },
      'ajax': base_url + 'Dashboard/getTopPerformingProduct',
      "order": [2, "desc"]
    });

    tableSelesByBrand = $('#tableSelesByBrand').DataTable({
      "bLengthChange": false,
      language: {
        search: "",
        sLengthMenu: "_MENU_"
      },
      'ajax': base_url + 'Dashboard/getSelesByBrand',
      "order": [1, "desc"]
    });

    tableTopPerformingMembers = $('#tableTopPerformingMembers').DataTable({
      "bLengthChange": false,
      language: {
        search: "",
        sLengthMenu: "_MENU_"
      },
      'ajax': base_url + 'Dashboard/getTopPerformingMembers',
      "order": [2, "desc"]
    });

    tableTotalSales = $('#tableTotalSales').DataTable({
      "bLengthChange": false,
      language: {
        search: "",
        sLengthMenu: "_MENU_"
      },
      'ajax': base_url + 'Dashboard/getTableTotalSales',
      "order": []
    });


    $('.dataTables_filter input[type="search"]').attr('placeholder', 'SEARCH').css({
      'display': 'none',
    });


  });

  function onChangeCurrency() {
      var array_select = document.getElementById("currency_id");
      if (array_select && array_select.selectedOptions.length > 0) {
        currency_id = array_select.selectedOptions[0].value;
        setChangeCurrency(currency_id);
      }
    }

    function setChangeCurrency(currency_id) {
      $.ajax({
        url: base_url + 'Dashboard/onChangeCurrency',
        type: 'post',
        data: {
          currency_id: currency_id
        },
        dataType: 'json',
        error: function(request, error) {
          alert("Something is wrong! ( " + request.responseText + " )");
        },
        success: function(element_info) {
         tableTopPerformingProduct.ajax.reload(null, false);
         tableSelesByBrand.ajax.reload(null, false);
         tableTopPerformingMembers.ajax.reload(null, false);
         tableTotalSales.ajax.reload(null, false);
    
        } // /success
      });
    }

</script>