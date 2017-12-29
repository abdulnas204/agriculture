
<div class="page-content-wrapper">

    <div class="page-content">
      <!-- BEGIN PAGE HEADER-->
      <h3 class="page-title">
      Profile
      </h3>

      <div class="page-bar">
         <?php $admin_data = get_user_type();
         
         if($admin_data['role']==1)
         {
            echo set_breadcrumb();          
         }
           ?>
      </div>
      <!-- END PAGE HEADER-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">

        <div class="col-md-12 ">
          <!-- BEGIN SAMPLE FORM PORTLET-->
          <div class="portlet box green ">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-table"></i> Profile Details
              </div>
            </div>

            <div class="portlet-body form">
    
                <div class="form-body">

  <div class="container-full sellerform">
   <ul class="nav nav-tabs sellertabs">
  <li class="active"><a data-toggle="tab" id="tab1" href="#contact">Contact Information</a></li>

  <li><a data-toggle="tab" href="#service" onclick="tab_view('service','seller/add_service','')" >Seller Services</a></li>

  <li><a data-toggle="tab" href="#photos" onclick="tab_view('photos','seller/add_photos','')">photos</a></li>
</ul>
  </ul>
  <input type="hidden" name="seller_id" value="<?=$editdata['id'];?>">
 
  <div class="tab-content">
    <div id="contact" class="tab-pane fade in active">

    <?php $this->load->view('frontend/seller/contact'); ?>
     
    </div>
    <div id="service" class="tab-pane fade">
     
    </div>
    <div id="photos" class="tab-pane fade">
      
    </div>
    
  </div>
</div>


        </div>
      </div>
      <!-- END PAGE CONTENT -->
    </div>
  </div>
  </div>
</div>
</div>


