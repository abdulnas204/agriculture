<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(COREPATH."controllers/Admin_controller.php");
class Seller extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    
    if(!is_logged_in())
      redirect('login');
      
  $this->load->model('seller_model');  
  $this->load->model('country_model');
  $this->load->model('state_model');
  $this->load->model('services_model');
  $this->load->model('photos_model');
  
  }

  
  public function index()
  {
    $this->layout->add_javascripts(array('listing'));
    $this->load->library('listing');

    $this->simple_search_fields = array('first_name' => 'First Name','last_name'=>'Last Name','email'=>'Email','status'=>'Status');
    $this->_narrow_search_conditions = array("start_date");
    $str = '<a href="'.site_url('seller/add/{id}').'" class="btn btn btn-padding"><i class="fa fa-edit"></i></a><a href="'.site_url('seller/delete/{id}').'" class="btn btn btn-padding"><i class="fa fa-remove remove"></i></a>';    
    $this->listing->initialize(array('listing_action' => $str));
    $listing = $this->listing->get_listings('seller_model', 'listing');

     $this->data['btn'] = "";
    $this->data['btn'] ="<a href=".site_url('seller/add')." class='btn green'>Add New <i class='fa fa-plus'></i></a>";
    if($this->input->is_ajax_request())
      $this->_ajax_output(array('listing' => $listing), TRUE);
    $this->data['bulk_actions'] = array('' => 'select', 'delete' => 'Delete');
    $this->data['simple_search_fields'] = $this->simple_search_fields;
    $this->data['search_conditions'] = $this->session->userdata($this->namespace.'_search_conditions');
    $this->data['per_page'] = $this->listing->_get_per_page();
    $this->data['per_page_options'] = array_combine($this->listing->_get_per_page_options(), $this->listing->_get_per_page_options());
    $this->data['search_bar'] = $this->load->view('listing/search_bar', $this->data, TRUE);
    $this->data['listing'] = $listing;
    $this->data['grid'] = $this->load->view('listing/view', $this->data, TRUE);

    $this->layout->view('frontend/seller/index');
  }
  
  public function add($edit_id = 0)
  {
   $admin_data = get_user_type();
    $this->layout->add_javascripts(array('dropzone'));
    $this->layout->add_javascripts(array('jquery.fancybox.min'));
    $this->layout->add_stylesheets(array('dropzone'));
    $this->layout->add_stylesheets(array('jquery.fancybox.min'));
    
    $msg ="";
    
     try
        {
        
          if($admin_data["role"]==1)
          {
            if($this->input->post('edit_id'))
             $editid = $this->input->post('edit_id'); 
          }
          elseif($admin_data["role"]==2)
          {
             $edit_id = $admin_data["id"];
          } 

          $seller_id = $this->input->post('seller_id');
           
          $this->form_validation->set_rules('first_name','First Name','trim|required');
          $this->form_validation->set_rules('last_name','Last Name','trim|required');
          
          //$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[seller.email]');

         $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_check_email['.$edit_id.']');
          
          $this->form_validation->set_rules('address','Address','trim|required');
          
          $this->form_validation->set_rules('city','City','trim|required');
          $this->form_validation->set_rules('status','Status','trim|required');
          $this->form_validation->set_rules('zip', 'Zib','trim|max_length[8]|integer', ['integer'=>'Invalid ZIP']);
          $this->form_validation->set_rules('phone', 'Phone', 'required|regex_match[/^[0-9]{10}$/]');   

          if(!$edit_id){
              $this->form_validation->set_rules('password','Password','trim|required');  
            }

          $this->form_validation->set_error_delimiters('', '');

          if(count($_POST) > 1 && $this->form_validation->run()){
             
              $ins_data = array();
              $ins_data['first_name']              = $this->input->post('first_name');
              $ins_data['last_name']                = $this->input->post('last_name');
              $ins_data['password']           = md5($this->input->post('password')); 
              $ins_data['address']           = $this->input->post('address');  
              $ins_data['email']        = $this->input->post('email');
              $ins_data['address2']               = $this->input->post('address2');
              $ins_data['city']          = $this->input->post('city');
              $ins_data['country_id']          = $this->input->post('country_id');
              $ins_data['state_id']          = $this->input->post('state_id');
              $ins_data['zip']                     = $this->input->post('zip');
              $ins_data['phone']      = $this->input->post('phone');
              $ins_data['status']      = $this->input->post('status');
            
              if($edit_id){
               
                $ins_data['modified_on'] = date('Y-m-d H:i:s');
                $msg                      = 'Record updated successfully';
                $this->seller_model->update(array("id" => $edit_id),$ins_data);
                $status  = 'edit';
              }             
              else
              {    

                $ins_data['created_on'] = date('Y-m-d H:i:s');
                $new_id                   = $this->seller_model->insert($ins_data); 
                $msg                      = 'Seller added successfully';
                $edit_id                  =  $new_id;
                $status  = 'add';
              }
                     
             
          }  
          else
          {
            $edit_data = array();

            $edit_data['first_name']              = '';
            $edit_data['password']                = '';
            $edit_data['last_name']                = '';
            $edit_data['email']           = '';  
            $edit_data['address2']               = '';
            $edit_data['address']          = '';
            $edit_data['state_id']                     = '';
            $edit_data['country_id']                     = '';
            $edit_data['city']                     = '';
            $edit_data['zip']      = '';
            $edit_data['phone']                     = '';
            $edit_data['status']                     = '';
            $edit_data['id'] =      '';
            $status = 'error';
          }
        }
        
        catch (Exception $e)
        {
            $this->data['status']   = 'error';
            $msg  = $e->getMessage();
        }

       if($edit_id){

          $edit_data = $this->seller_model->get_where(array("id" => $edit_id))->row_array();
          

        } 
        
        $country = $this->country_model->get_all('231');

        $country_data[null] = 'Select Country';

        if($country){

           foreach ($country as $key => $value) {

            $country_data[$value->id] = $value->name;

            }
          }
        //print_r($country_data);exit;
        $state =  $this->state_model->get_state_by_country_id(231);

        $state_data[null] = 'Select State';
         if($state){

           foreach ($state as $key => $value) {

            $state_data[$value->id] = $value->name;

            }
          }
        
        
        
        $this->data['editdata']              = $edit_data;

        $this->data['country']              = $country_data;
        
        $this->data['state']                = $state_data;

        if($this->input->is_ajax_request()){
          $output  = $this->load->view('frontend/seller/contact',$this->data,true);
          return $this->_ajax_output(array('status' => $status,'msg'=>$msg, 'output' => $output, 'edit_id' => $edit_id), TRUE);
        } 
        else
        { 
            $this->layout->view('frontend/seller/add');  
        }  
  }

  public function add_service($edit_id = '')
  {
    
      $msg ="";
     try
        {
          
          if($this->input->post('seller_id'))

            $edit_id = $this->input->post('seller_id');
            
       $this->form_validation->set_rules('company_name','Business Name','trim|required');

        $this->form_validation->set_rules('experience','Work Experience','trim|required');

        $this->form_validation->set_rules('primary_service_category','Primary services','trim|required');

        $this->form_validation->set_rules('description','Description','trim|required');

        $this->form_validation->set_error_delimiters('', '');

        if(count($_POST) > 1 && $this->form_validation->run())
        {

              $ins_data = array();
              $ins_data['company_name']          = $this->input->post('company_name');
              $ins_data['website']               = $this->input->post('website');
              $ins_data['description']           = $this->input->post('description');
              $ins_data['experience_type']          = $this->input->post('experience_type');
              $ins_data['experience']            = $this->input->post('experience');
              $ins_data['primary_service_category'] = $this->input->post('primary_service_category');
              $ins_data['other_related_category']   = $this->input->post('other_related_category');
              $ins_data['qualification']         = $this->input->post('qualification');
              $ins_data['seller_id']             = $this->input->post('seller_id');



              if($edit_id){

                 $checkServiceExist      = $this->services_model->checkServiceExist($ins_data); 

                //$checkServiceExist = $this->service_model->checkServiceExist($ins_data);

                if($checkServiceExist){

                    $ins_data['modified_on'] = date('Y-m-d H:i:s');
                    $this->services_model->update_services($edit_id,$ins_data);
                    $msg                      = 'Record updated successfully';
                    $status  = 'edit';
                 
                }else{

                   $ins_data['created_on'] = date('Y-m-d H:i:s');
                   $new_id                   = $this->services_model->insert($ins_data);         
                   $msg                      = 'Services added successfully';
                   $status  = 'add';
                }

              $this->session->set_flashdata('success_msg',$msg,TRUE);
              $status  = 'success';
                
              }
             
              
           }
          
          else
          {
              $edit_data = array();
              $edit_data['company_name'] = '';
              $edit_data['description'] = '';
              $edit_data['experience_type']  = '';
              $edit_data['experience_id']  = '';
              $edit_data['primary_service_category']  = '';
              $edit_data['other_related_category']  = '';
              $edit_data['qualification_id']  = '';
              $edit_data['website']  = '';
              $edit_data['position']  = '';
              $edit_data['farming_industry']  = '';
              $edit_data['far_name']  = '';
              $edit_data['far_address']  = '';
              $edit_data['far_description']  = '';
              $edit_data['land_type']  = '';
              $edit_data['number_of_acreage']  = '';
              $edit_data['cus_far_name']  = '';
              $edit_data['own_acreage']  = '';
              $edit_data['custom_farmer']  = '';
              $edit_data['cus_description']  = '';
              $edit_data['seller_id']  = '';
              $status = 'error'; 
          }

        }
        catch (Exception $e)
        {
            $this->data['status']   = 'error';
            $msg  = $e->getMessage();
        }

        if($edit_id){

          $edit_data = $this->services_model->get_services_by_id($edit_id);
        } 

          
        $this->data['editdata']              = $edit_data;
        
        if($this->input->is_ajax_request()){
         
         $output  = $this->load->view('frontend/services/add',$this->data,true);
          return $this->_ajax_output(array('status' => $status, 'msg'=>$msg, 'output' => $output, 'edit_id' => $edit_id), TRUE);
        } 
        else
        {

            $this->layout->view('frontend/services/add',$this->data);
        }  
  }

  public function add_photos($edit_id = '')
  {
    
      
     try
        {
          if($this->input->post('seller_id'))            
            $edit_id = $this->input->post('seller_id');
           
           $edit_data =array();
           $edit_data ='';
           $editdata['seller_id'] ='';
           $editdata['image_name'] =''; 
           $status = 'error';
           $seller_id = ($_POST['seller_id'])?$_POST['seller_id']:"";
           
        if (!empty($_FILES['file']['name'])) {
            
            $tempFile = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $targetPath = getcwd() . '/uploads/seller/';
            $ins_data['seller_id'] = $seller_id;
            $ins_data['image_name'] = $fileName;
            $ins_data['created_on'] = date('Y-m-d H:i:s'); 
            //$ins_data['seller_image_id']  = $this->input->post('seller_id');
            $targetFile = $targetPath . $fileName ;

            move_uploaded_file($tempFile, $targetFile);
              
              if($edit_id){
                
               $this->photos_model->insert($ins_data); 
               redirect("seller/add");

              }
              else
              {   
                $new_id                   = $this->photos_model->insert($ins_data);         
                $msg                      = 'Photos added successfully';
                //$edit_id                  =  $new_id;
                redirect("seller/add");
                
              }
              $this->session->set_flashdata('success_msg',$msg,TRUE);
              $status  = 'success';
          }
         }
        catch (Exception $e)
        {
            $this->data['status']   = 'error';
            $msg  = $e->getMessage();
        }

        if($edit_id){
           $edit_data = $this->photos_model->get_photos_by_id($edit_id);

        } 
        
        $this->data['editdata']              = $edit_data;
        
        if($this->input->is_ajax_request()){
         $output  = $this->load->view('frontend/photos/add',$this->data,true);
          return $this->_ajax_output(array('status' => $status, 'output' => $output, 'edit_id' => $edit_id), TRUE);
        } 
        else
        {
            $this->layout->view('frontend/photos/add',$this->data);
        } 

  }

   function check_email($mail,$id)
    {
        $where = array();

        if($id)
            $where['id !='] = $id;

        $where['email'] = $mail;

        $result = $this->seller_model->get_where( $where)->num_rows();

        if ($result) {
            $this->form_validation->set_message('check_email', 'Given email already exists!');
            return FALSE;
        }
        return TRUE;
    }

  function delete($del_id)
    {
      $access_data = $this->seller_model->get_where(array("id"=>$del_id),'id')->row_array();
      $service_data =  $this->services_model->get_where(array("seller_id"=>$del_id),'id')->row_array();
      $photo_data =  $this->photos_model->get_where(array("seller_id"=>$del_id),'id')->row_array();
      $output=array();
      if(count($access_data) > 0)
      {
        $this->seller_model->delete(array("id"=>$del_id));
        if(count($service_data) > 0){

          $this->services_model->delete(array("seller_id"=>$del_id));
        }
        if(count($service_data) > 0){

           $this->photos_model->delete(array("seller_id"=>$del_id));
        }
        //$this->services_model->delete(array("seller_id"=>$del_id));
        //$this->photos_model->delete(array("seller_id")=>$del_id);
        $output['message'] ="Record deleted successfuly.";
        $output['status']  = "success";
        redirect("seller");
      }
      else
      {
        $output['message'] ="This record not matched by Seller.";
        $output['status']  = "error";
        redirect("seller");
      }
      $this->_ajax_output($output, TRUE);
    }

    /*
    * This method loads states based on the country
    */
    public function get_state($id){   
        $states = get_state_by_country($id);
        foreach($states as $index=>$state):
            echo '<option value="'.$index.'">'.$state.'</option>';
        endforeach;
        exit;
    }
  /**
  * This method handles to validate phone
  **/
  function form_validation_validate_phone($str){
        $valid_url  = validate_phone($str);
        if(!empty($str)){
          if (!$valid_url){
              $this->form_validation->set_message('form_validation_validate_phone', sprintf($this->lang->line('invalid'), 'Phone'));
              return FALSE;
          }
        }
        
        return TRUE;
    }
  
  /**
  *This method handles to validate url
  **/
  function form_validation_validate_url($str){
        $valid_url  = validate_url($str);
        if(!empty($str)){
          if (!$valid_url){
              $this->form_validation->set_message('form_validation_validate_url', 'Invalid URL');
              return FALSE;
          }
        }
        
        return TRUE;
    }

    public function deleteimage() {
        $deleteid = $this->input->post('image_id');

        if($deleteid ){

          $image_data = $this->photos_model->get_image_by_id($deleteid);
        
           $path = getcwd() . '/uploads/';

          $delete_file_result = delete_file($path, $image_data[0]->image_name);

        }

        $this->db->delete('seller_image', array('id' => $deleteid));

        $verify = $this->db->affected_rows();

      
        echo $verify;
    }
  
}
?>