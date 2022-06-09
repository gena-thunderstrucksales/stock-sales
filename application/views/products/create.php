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

        <div class="box-header-over ">
          <div class="box-header">
            <h3 class="box-title">New Product</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" onsubmit="return to_submit();" action="<?php base_url('products/create') ?>" method="post" enctype="multipart/form-data">
            <div class="main-box-body">
              <div class="box-body">
                <?php echo validation_errors(); ?>
                <h3>PRODUCT INFO</h3>
                <div class=" row product info ">
                  <div class="col-xs-12 col-sm-12 col-lg-8">
                    <div class="form-group">
                      <a for="product_name">Product name</a>
                      <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" autocomplete="off" />
                    </div>

                    <div class=" row product info padding-row">
                      <div class="col-xs-12 col-sm-6 col-lg-6 ">
                        <a for="brands">Brand</a>
                        <select class="form-control select_group" id="brand" name="brand">
                          <?php foreach ($brands as $k => $v) : ?>
                            <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                          <?php endforeach ?>
                        </select>
                      </div>

                      <div class="col-xs-12 col-sm-6 col-lg-3 ">
                        <a for="brands">Category</a>
                        <select class="form-control select_group" id="category" name="category">
                          <?php foreach ($category as $k => $v) : ?>
                            <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-lg-3 ">
                        <a for="brands">Status</a>
                        <select class="form-control select_group" id="status_publish" name="status_publish">
                          <?php  foreach ($statuses as $k => $v) : ?>
                            <option value="<?php echo $k ?>"><?php echo $v ?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <a for="description">Description</a>
                      <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter description" autocomplete="off">
                      </textarea>
                    </div>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-lg-4">
                    <div class="form-group">
                      <a for="product_image">Upload Images</a>
                      <div class="block-item ">
                      <div class="block-upload-file">
                          <div class="block-upload-file-circle" id="drop-area">
                            <div class="loader" id='loader'></div>
                          </div></br>

                          <div class=" uploud-imege-label">
                            <label id="title-item-uploud-imege">Drag images over to add or upload from file.</label>
                            <input type="file" class="custom-file-input btn btn-primary-item-form" id="userfile" name="userfile" size="20" />
                               <input type="hidden" name="product_id" value="new product" />
                          </div >
                
                          <div class="block-gallery">
                            <div id="product_carousel" class="carousel slide">
                              <!-- Carousel items -->
                              <div class="carousel-inner" id=carousel-inner>

                              </div>
                              <a class="left carousel-control" href="#product_carousel" data-slide="prev">‹</a>
                              <a class="right carousel-control" href="#product_carousel" data-slide="next">›</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="dashed-line ">
                  <h3>PRODUCT OPTIONS</h3>
                  <div class="block-item">
                    <div>
                      <h4>PRODUCT SIZE</h4>
                      <table id="docTable" class="table table-bordered ">
                        <thead>
                          <tr>
                            <th style="width:5%">No</th>
                            <th style="width:25%">Option value</th>
                            <th style="width:5%"></th>
                            <th style="width:10%">Price</th>
                            <th style="width:5%"></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      <a id="add_row_opton" class="btn btn-primary-form">+ ADD New VALUE</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Save product</button>
              <a href="<?php echo base_url('products/') ?>" class="btn btn-warning">Back</a>
            </div>
          </form>
          <!-- /.box-body -->
        </div>
      </div>
      <!-- /.box -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";
  var product_id = -1;
  var product_name = ""
  var docTable;
  var listAllOptions;
  var row_id = 0;
  var idx = 0;
  var remove_action_row;

  $(document).ready(function() {
    $(".select_group").select2();
    $("#description").wysihtml5();

    docTable = $('#docTable').DataTable({
      "scrollY": 240,
      "scrollX": false,
      "pageLength": 25,
      language: {
        search: "",
        sLengthMenu: "_MENU_",
        searchPlaceholder: "SEARCH"
      },
    });

    $("#userfile").change(function(objEvent) {
      var objFormData = new FormData();
      // GET FILE OBJECT
      var objFile = $(this)[0].files[0];
      // APPEND CSRF TOKEN TO POST DATA
      objFormData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash(); ?>');
      // APPEND FILE TO POST DATA
      objFormData.append('userfile', objFile);
      $.ajax({
        url: "<?= base_url('products/do_upload_image/'); ?>",
        type: 'POST',
        contentType: false,
        data: objFormData,
        //JQUERY CONVERT THE FILES ARRAYS INTO STRINGS.SO processData:false
        processData: false,
        success: function(response) {

          setAllPictures();
        }
      });
    });
    $('#docTable tbody').on('click', 'tr', function() {
      idx = docTable.row(this).index();
      if (remove_action_row) {
        docTable.row(idx).remove().draw();
        remove_action_row = false;
      }
    });
  });


  function setAllPictures() {
    $.ajax({
      url: base_url + '/products/setAllPictures',
      type: 'post',
      data: "",
      dataType: 'json',
      error: function(request, error) {
        alert("Something is wrong! ( " + request.responseText + " )");
      },
      success: function(response) {
        var element = document.getElementById("carousel-inner");
        var html = '';

        if (response != null) {

          var arrayPictures = new Array();

          for(var item in response){
             arrayPictures.push(response[item]);
          }
          var list_pictures = arrayPictures;
          if (list_pictures.length != 0) {

            var count_pacage = 4;
            var count_get_value = 0;
            var count_pictures = list_pictures.length;
            var count_loops = count_pictures / count_pacage;
            var integer_count_loops = parseInt(count_loops);
            if (count_pictures % count_pacage != 0) {
              integer_count_loops = integer_count_loops + 1;
            }

            for (var x = 0; x <= integer_count_loops - 1; x++) {
              if (x == 0) {
                var active_style = 'item active';
              } else {
                var active_style = 'item';
              }

              html += ' <div class=\'' + active_style + '\'>'
              html += '  <div class="row item-picture" >'
              for (var y = 0; y <= count_pacage - 1; y++) {
                if (count_get_value < count_pictures) {
                  var v = list_pictures[count_get_value];
                  var full_path = v['file_path'] + v['file_name'];
                  var id = v['file_name'];

                  html += '<div class="col-md-3 col-xs-3 item-picture-col"><a href="#x" onClick="showModalPicture(\'' + id + '\',\'' + base_url + full_path + '\')">'
                  html += '<img src= \'' + base_url + full_path + '\' alt="Image" class="img-responsive img-responsive-item"></a>'
                  html += '</div>'
                }
                count_get_value++
              }
              html += '</div>'
              html += '</div>'
            }
          }

          if (element) {
            $("#carousel-inner").html(html);
          }
        }
      }
    });
  }

  function showModalPicture(id_picture, path_picture) {
    var name_product = 'new product';
    var html = '';
    html += '<div class="modal fade" id="picture_product" role="dialog">';
    html += '<div class="modal-dialog">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header">';
    html += '<button type="button" class="close" data-dismiss="modal">&times;</button>';
    html += '<h4 class="modal-title">\'' + name_product + '\'</h4>';
    html += '</div>';
    html += '<div class="modal-body">';
    html += '<div class=""><a href="#x"><img src=\'' + path_picture + '\' alt="Image" class="img-responsive"></a>';
    html += '</div>';
    html += '</div>';
    html += '<div class="modal-footer">';
    html += '<button type="button"  onclick="removePicture(\'' + id_picture + '\')" class="btn btn-default  delete-picture" >Delete</button>';
    html += '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';

    var element = document.getElementById("messages");
    if (element) {
      $("#messages").html(html);
    }
    $("#picture_product").modal();
  }

  function removePicture(id_picture) {
    $.ajax({
      url: base_url + '/products/remove_temp_list_picture',
      type: "POST",
      data: {
        id_picture: id_picture
      },
      dataType: 'json',
      success: function(response) {
        if (response.success === true) {
          // hide the modal
          setAllPictures();
          $("#picture_product").modal('hide');
        } else {

          $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
            '</div>');
        }
      }
    });
    return false;
  }

  function show_message_error(message) {
    $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
      '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
      '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + message +
      '</div>');
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }

  function show_message(message, status_message) {
    if (status_message == 0) {
      $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + message +
        '</div>');
    } else {
      $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + message +
        '</div>');
    }
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }

  getAllOptions();

  function fiilOptionsTable(product_id) {
    $.ajax({
      url: "<?php echo site_url('products/fetchSetFillOptionsTable') ?>",
      type: "POST",
      data: {
        product_id: product_id
      },
      processing: true,
      dataType: 'json',
      error: function(request, error) {
        alert("Something is wrong! ( " + request.responseText + " )");
      },
      success: function(response) {
        var dataArray = response["data"];
        addTableOptonRow(dataArray);
      }
    });
  }

  function getAllOptions() {
    console.log("getAllOptions ");
    $.ajax({
      url: base_url + '/products/getAllOptions',
      type: 'post',
      data: "",
      dataType: 'json',
      error: function(request, error) {
        alert("Something is wrong! ( " + request.responseText + " )");
      },
      success: function(response) {
        if (response != null) {
          listAllOptions = response;
          fiilOptionsTable(product_id);
        } else {
          alert('Something is wrong! (getAllOptions)');
        }
      }
    });
  }

  $("#add_row_opton").unbind('click').bind('click', function() {
    var data_options = [];
    var empty_item = {
      attribute_id: 0,
      list_all_options: listAllOptions,
      price: "0"
    };
    data_options.push(empty_item);
    addTableOptonRow(data_options);
  });

  function getOptionId(row_id) {
    var product_id = $("#product_" + row_id).val();
    $("#idOption_" + row_id).val(product_id);
  }

  function addTableOptonRow(data_options) {
    if (data_options != null) {
      for (element of data_options) {
        var htmlOptions = '';

        htmlOptions += '<select class="form-control select_option" data-row-id="' + row_id + '" id="product_' + row_id + '" name="product[]" style="width:100%;" onchange="getOptionId(' + row_id + ')"> required>';
        htmlOptions += '<option value=""></option>';

        var attribute_id = element['attribute_id'];
        var list_all_options = listAllOptions;
        var price = element['price'];

        if (price) {
          price = price;
        } else {
          var price = '0.00';
        }

        for (value of list_all_options) {
          var attribute_data = value['attribute_data'];
          var attribute_data_name = attribute_data['name'];
          var attribute_value = value['attribute_value'];

          for (value of attribute_value) {
            if (attribute_id == value.id) {
              htmlOptions += '<option selected=selected value="' + value.id + '">' + attribute_data_name + " " + value.value + '</option>'
            } else {
              htmlOptions += '<option value="' + value.id + '">' + attribute_data_name + " " + value.value + '</option>'
            }
          }
        }

        htmlOptions += '</select>';

        docTable.row.add([
          '<input id = "idOption_' + row_id + '" type="number" readonly class="form-control" name="attribute_id[]" value="' + attribute_id + '">',
          htmlOptions,
          '<input type="text" readonly class="form-control" name="attribute[]" value="">',
          '<input type="text"  readonly class="form-control" name="price[]" value="' + price + '" step="0.01">',
          '<button type="button" class="label-base-icon-doc remove-doc" onclick="removeRow(\'' + row_id + '\')" ></button>',
        ]).node().id = "row_" + row_id;
        row_id++;
        htmlOptions = '';
        docTable.draw();
        $(".select_option").select2();
      }

    } else {
      alert('Something is wrong! (addTableOptonRow)');
    }
  }


  function removeRow(id) {
    remove_action_row = true;
  }

  /// drop images
  $("#drop-area").on('dragenter', function(e) {
    e.preventDefault();
    $(this).css('background', '#BBD5B8');
  });

  $("#drop-area").on('dragover', function(e) {
    e.preventDefault();
  });

  $("#drop-area").on('drop', function(e) {
    $(this).css('background', '#D8F9D3');
    e.preventDefault();
    var image = e.originalEvent.dataTransfer.files;
    createFormData(image);
  });

  function createFormData(image) {
    var formImage = new FormData();
    formImage.append('userImage', image[0]);
    uploadFormData(formImage);
  }

  function uploadFormData(formData) {
    document.getElementById("loader").style.display = "block";
    $.ajax({
      url: base_url + '/products/do_drop_drag/' + product_id,
      type: "POST",
      data: formData,
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        //location.reload();
        setAllPictures();
        document.getElementById("loader").style.display = "none";
      }
    });
  }


  function to_submit() {
    const array_fields = []
    var error = "";
    array_fields.push('product_name');
    array_fields.push('category');
    array_fields.push('brand');

    array_empty_fields = checkEmptyFields(array_fields);

    if (array_empty_fields.length > 0) {
      error += " \nThere are several empty fields ! (" + array_empty_fields + ")";
    }
    if (!error == '') {
      show_message(error);
      return false;
    }
  }

  function checkEmptyFields(arr_fields) {
    const array_empty_fields = []
    for (const field of arr_fields) {
      value = $("#" + field).val();

      if (value == 0 || value == null || value == '') {
        array_empty_fields.push(field);
      }
    }
    return array_empty_fields;
  }
</script>