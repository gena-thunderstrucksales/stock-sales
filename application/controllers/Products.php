<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->data['page_title'] = 'Products';

        $this->load->model('model_products');
        $this->load->model('model_brands');
        $this->load->model('model_category');
        $this->load->model('model_attributes');
        $this->load->model('model_products_attributes');
        $this->load->model('model_attributes_value');
        $this->load->model('model_currencies');
        $this->load->model('model_prices');
        $this->load->model('model_products_upload_pictures');

        $this->load->library('enumstypestatusobject');
        $this->load->library('helper');
        $this->load->library('enumsproductstatus');
    }

    /* 
    * It only redirects to the manage product page
    */
    public function index()
    {
        if (!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->data['statuses'] = $this->enumsproductstatus->enumsStr;

        $this->render_template('products/index', $this->data);
        $this->initialize_temp_list_picture();
    }

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
    public function fetchProductData()
    {
        $result = array('data' => array());
        $status_publish = $this->input->post('status_publish');

        $data = $this->model_products->getProductDataStatusPublish($status_publish);
   

        foreach ($data as $key => $value) {
            $brand = $this->model_brands->getBrandData($value["brand_id"]);
            $category = $this->model_category->getCategoryData($value["category_id"]);
            $product_id =  $value['id'];
            $status_publish = $value['status_publish'];
            $name_status_publish = $this->enumsproductstatus->enumsStr[$status_publish];
            // button
            $buttons = '';
            if (in_array('updateProduct', $this->permission)) {
                $buttons .= '<button  type="button" onclick=window.location.href="' . base_url('products/update/' . $value['id']) . '"  class="label-base-icon-doc edit-doc"></button>';
            }

            if (in_array('deleteProduct', $this->permission)) {
                $buttons .= ' <button type="button" class="label-base-icon-doc remove-doc" onclick="removeFunc(' . $value['id'] . ')" data-toggle="modal"  data-toggle="modal" data-target="#removeModal"></button>';
            }

            if (in_array('deleteProduct', $this->permission)) {
                $buttons .= '<div class="btn-group">
				<button type="button" class="label-base-icon-doc select-menu" data-toggle="dropdown" data-disabled="true" aria-expanded="true"> </button>';
                $buttons .= '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
               
                if($status_publish == 0){
                    $buttons .= '<li><a onclick="setStatusProduct(' . $value['id'] . ',1 )" >Enable</a></li>';
                } else{
                    $buttons .= '<li><a onclick="setStatusProduct(' . $value['id'] . ',0 )" >Disable</a></li>';
                }
                $buttons .= '</ul>
			</div>';
            }

            $result['data'][$key] = array(
                $product_id,
                $value['name'],
                $brand['name'],
                $category['name'],
                $name_status_publish,
                $buttons
            );
        } // /foreach


        echo json_encode($result);
    }

    public function  setStatusProduct()
    {
        $product_id = $this->input->post('product_id');
        $type_status_id = $this->input->post('type_status_id');
        if ($product_id) {
            $this->model_products->setStatusProduct($product_id,  $type_status_id);
        }
    }

    public function fetchSetFillOptionsTable()
    {
        $result = array('data' => array());
        $product_id = $this->input->post('product_id');
        $active = $this->enumstypestatusobject->enumsNum['active'];

        $product_data = $this->model_products_attributes->getProductsAttributesData($product_id);

        foreach ($product_data as $key_p => $value) {
            $item_attributes  = $this->model_attributes->getAttributeValueById($value['attribute_id']);
            $attribute_value = $this->model_attributes_value->getAttributeValueData($item_attributes['attribute_parent_id']);

            $list_currencies = $this->model_currencies->getActiveCurrencies();
            $price = '';
            foreach ($list_currencies as $key => $currencValue) {
                $current_price = $this->model_prices->getPricesItemWithOptinoData($product_id, $item_attributes['id'], strtotime(date('Y-m-d h:i:s a')), $active,  $currencValue['id']);

                if ($current_price) {
                    $price =  $price . '' . number_format($current_price['price'], 2) . ' ' . $currencValue['name'] . ', ';
                }
            }

            $result['data'][$key_p] = array(
                'attribute_id' =>  $value['attribute_id'],
                'name_attributes'  => $item_attributes['value'],
                'attribute_value'  => $attribute_value,
                'price'  => $price,
            );
        } // /foreach
        echo json_encode($result);
    }

    public function getAllOptions()
    {
        $attribute_data = $this->model_attributes->getActiveAttributeData();

        $attributes_final_data = array();
        foreach ($attribute_data as $k => $v) {
            $attributes_final_data[$k]['attribute_data'] = $v;
            $value = $this->model_attributes->getAttributeValueData($v['id']);
            $attributes_final_data[$k]['attribute_value'] = $value;
        }
        echo json_encode($attributes_final_data);
    }


    public function do_upload_image()
    {
        $config['upload_path']          = 'assets/images/product_image';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2048; //KB


        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            $data = array('array_error' =>   $error, 'product_id');
            $response['success'] = false;
            $response['messages'] = $this->upload->display_errors();
        } else {

            $data = array('upload_data' => $this->upload->data());
            $path = $config['upload_path'] . '/';

            $data_record = array(
                'file_name' => $data['upload_data']['file_name'],
                'file_path' => $path,
                'file_size' => $data['upload_data']['file_size'],
                'file_type' => $data['upload_data']['file_type'],
                'product_id' => -1,
            );

            $this->add_temp_list_picture($data_record);
        }
        echo json_encode($response);
    }

    public function do_drop_drag($product_id)
    {
        $this->product_id = $product_id;

        if (is_array($_FILES)) {
            if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
                $sourcePath = $_FILES['userImage']['tmp_name'];
                $path =  "assets/images/product_image/";
                $targetPath =  $path . $_FILES['userImage']['name'];

                if (move_uploaded_file($sourcePath, $targetPath)) {
                    $data_record = array(
                        'file_name' => $_FILES['userImage']['name'],
                        'file_path' => $path,
                        'file_size' => $_FILES['userImage']['size'] / 100,
                        'file_type' => $_FILES['userImage']['type'],
                        'product_id' => -1,
                    );
                    $this->add_temp_list_picture($data_record);

?>
                    <img src="<?php echo $targetPath; ?>" width="100px" height="100px" hspace=15 />
                <?php
                } else {
                ?>
                    <img src="<?php echo $targetPath; ?>" width="100px" height="100px" hspace=15 />
<?php
                }
            }
        }
    }

    public function remove_all_temp_list_picture()
    {
        $array_pictures = $this->session->userdata('list_tempory_pictures');
        if ($array_pictures) {
            foreach ($array_pictures as $key_p => $value) {
                unset($array_pictures[$key_p]);
                $full_path = $value['file_path'] . $value['file_name'];
                unlink($full_path);
            }
        }

        $this->session->set_userdata('list_tempory_pictures',  array());
    }
    public function remove_temp_list_picture()
    {
        $id_picture = $this->input->post('id_picture');

        $response = array();
        $delete = false;
        if ($id_picture) {
            $array_pictures = $this->session->userdata('list_tempory_pictures');

            foreach ($array_pictures as $key_p => $value) {
                if ($value['file_name'] ==  $id_picture) {
                    unset($array_pictures[$key_p]);
                    $full_path = $value['file_path'] . $value['file_name'];
                    unlink($full_path);
                    $delete = true;
                }
            }
            if ($delete == true) {
                $response['success'] = true;
                $this->session->set_userdata('list_tempory_pictures',  $array_pictures);
            } else {
                $response['success'] = false;
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }
        echo json_encode($response);
    }

    public function initialize_temp_list_picture()
    {
        $array_pictures = array();
        $this->session->set_userdata('list_tempory_pictures',  $array_pictures);
        $this->session->set_userdata('uploated_list_pictures_from_database',  false);
    }

    public function add_temp_list_picture($array_data_record = null)
    {
        if (!$this->session->userdata('list_tempory_pictures')) {
            $array_pictures = array();
        } else {
            $array_pictures = $this->session->userdata('list_tempory_pictures');
        }
        $array_pictures[] =  $array_data_record;
        $this->session->set_userdata('list_tempory_pictures',  $array_pictures);
        echo json_encode('');
    }

    public function setAllPictures()
    {

        if ($this->session->userdata('list_tempory_pictures') == 0) {
            $array_pictures = array();
            $this->session->set_userdata('list_tempory_pictures',  $array_pictures);
        }
        echo json_encode($this->session->userdata('list_tempory_pictures'));
    }
    /*
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
    public function create()
    {
        if (!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required|max_length[64]');
        $this->form_validation->set_rules('brand', 'Brand', 'trim|required');
        $this->form_validation->set_rules('category', 'Category', 'trim|required');


        if ($this->form_validation->run() == TRUE) {
            $data_product = array(
                'name' => $this->input->post('product_name'),
                'description' => $this->input->post('description'),
                'brand_id' => $this->input->post('brand'),
                'category_id' => $this->input->post('category'),
                'status_publish' => $this->input->post('status_publish'),
            );

            $data_list_pictures = $this->session->userdata('list_tempory_pictures');

            $data =  array(
                'data_list_pictures' => $data_list_pictures,
                'data_product' => $data_product,
            );

            $create = $this->model_products->create($data);

            if ($create == true) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('products/update', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('products/create', 'refresh');
            }
        } else {

            $this->remove_all_temp_list_picture();

            $this->data['brands'] = $this->model_brands->getActiveBrands();
            $this->data['category'] = $this->model_category->getActiveCategroy();
            $statuses = $this->enumsproductstatus->enumsStr;
            arsort($statuses);
            $this->data['statuses'] = $statuses;

            $this->render_template('products/create', $this->data);
        }
    }

    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
    public function update($product_id = null)
    {
        if (!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if (!$product_id) {
            redirect('products/', 'refresh');
        }

        $active = $this->enumstypestatusobject->enumsNum['active'];
        if ($this->input->post('attribute_id[]')) {
            $count_product = count($this->input->post('attribute_id[]'));
            if ($count_product > 0) {
                $this->form_validation->set_rules('attribute_id[]', 'Opton name', 'trim|required');
            }
        }

        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        $this->form_validation->set_rules('brand', 'Brand', 'trim|required');
        $this->form_validation->set_rules('category', 'Category', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            $data_product = array(
                'name' => $this->input->post('product_name'),
                'description' => $this->input->post('description'),
                'brand_id' => $this->input->post('brand'),
                'category_id' => $this->input->post('category'),
                'status_publish' => $this->input->post('status_publish'),
            );

            $data_list_pictures = $this->session->userdata('list_tempory_pictures');

            $data =  array(
                'data_list_pictures' => $data_list_pictures,
                'data_product' => $data_product,
            );

            $update = $this->model_products->update($data, $product_id);

            if ($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('products/update', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('products/update/' . $product_id, 'refresh');
            }
        } else {
            $this->remove_all_temp_list_picture();

            $list_pictures =  $this->model_products_upload_pictures->getListItemsById($product_id, $active);
            $this->session->set_userdata('list_tempory_pictures',   $list_pictures);

            $this->data['brands'] = $this->model_brands->getActiveBrands();
            $this->data['category'] = $this->model_category->getActiveCategroy();

            $product_data = $this->model_products->getProductData($product_id);
            $this->data['product_id'] = $product_id;

            $statuses = $this->enumsproductstatus->enumsStr;
            arsort($statuses);
            $this->data['statuses'] = $statuses;

            $this->data['product_data'] = $product_data;
            $this->render_template('products/edit', $this->data);
        }
    }

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */

    public function remove()
    {
        if (!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        $response = array();
        $product_id = $this->input->post('product_id');

        if ($product_id) {
            $arr_check = $this->helper->parse_answer_links($this->model_products->exist_links($product_id));
            if ($arr_check) {
                $response['success'] = false;
                $response['messages'] = "This item has some links for " . $arr_check . ' cannot be removed now!';
            } else {
                $delete = $this->model_products->deactive($product_id);
                if ($delete == true) {
                    $response['success'] = true;
                    $response['messages'] = "Successfully removed";
                } else {
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while removing the product information";
                }
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response);
    }

    public function removePicture()
    {
        if (!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $id_picture = $this->input->post('id_picture');

        $response = array();
        if ($id_picture) {
            $delete = $this->model_products_upload_pictures->deactive($id_picture);
            if ($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed";
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }
        echo json_encode($response);
    }
}
