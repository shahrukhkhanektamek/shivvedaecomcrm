<?php echo $__env->make('user.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1 class="text-black"><?php echo e($data['page_title']); ?></h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo e(url('user/dashboard')); ?>">Home</a></li>
        <?php $__currentLoopData = $data['pagenation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li class="sub-bread"><i class="fa fa-angle-right"></i> <?php echo e($value); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ol>
    </div>
    
    <!-- Main content -->
    <div class="content">
      <div class="row">
        <div class="col-lg-12">
          <div class="card card-outline">
            <!-- <div class="card-header bg-blue">
              <h5 class="text-white m-b-0">Basic Example</h5>
            </div> -->
            <div class="card-body">


              <?php if($row->kyc_step==1): ?>
              <div class="alert alert-success show" role="alert"><strong>Success</strong> - Your Kyc Is Approved
              </div>

              <?php elseif($row->kyc_step==0): ?>
              <div class="alert alert-info show" role="alert"><strong>Information</strong> - Update Your Kyc 
              </div>

              <?php elseif($row->kyc_step==2): ?>
              <div class="alert alert-info show" role="alert"><strong>Information</strong> - Your Kyc Is Under Review
              </div>

              <?php elseif($row->kyc_step==3): ?>
              <div class="alert alert-danger show" role="alert"><strong>Warning</strong> - Your Kyc Rejected!
              </div>
              <?php endif; ?>

              
              <form class="form row form_data" action="<?php echo e($data['submit_url']); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                <?php echo csrf_field(); ?>



                <div class="col-md-4">
                  <div class="form-group">
                    <label>Your Name as per Bank Account</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="Name" type="text" name="bank_holder_name" value="<?php echo e(@$kyc->bank_holder_name); ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Name</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="Bank Name" type="text" name="bank_name" value="<?php echo e(@$kyc->bank_name); ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account Number</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="Account Number" type="number" name="account_number" value="<?php echo e(@$kyc->account_number); ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account Type</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <select class="form-select mb-3" aria-label="Default select example" name="account_type" id="placement">
                        <option value="0" >Select Type</option>
                        <option value="Saving" <?php if(@$kyc->account_type=='Saving'): ?> selected <?php endif; ?> >Saving</option>
                        <option value="Current" <?php if(@$kyc->account_type=='Current'): ?> selected <?php endif; ?>>Current</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>IFSC</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="IFSC" type="text" name="ifsc" value="<?php echo e(@$kyc->ifsc); ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>PAN Card</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="PAN Card" type="text" name="pan" value="<?php echo e(@$kyc->pan); ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Registered Mobile</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="Bank Registered Mobile" type="number" name="rg_mobile" value="<?php echo e(@$kyc->rg_mobile); ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Registered Email</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="Bank Registered Email" type="email" name="rg_email" value="<?php echo e(@$kyc->rg_email); ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="Address" type="text" name="address" value="<?php echo e(@$kyc->address); ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Passbook Image</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <!-- <input class="custom-file-input" placeholder="Bank Passbook Image" type="file" name="sponser_id"> -->
                      <label class="custom-file center-block block">
                        <input type="file" id="file" class="custom-file-input" name="passbook_image">
                        <span class="custom-file-control"></span>
                      </label>
                    </div>
                  </div>
                </div>


                
                
                <div class="col-md-12">
                  <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
                </div>
                
              </form>


             
            </div>
          </div>
        </div>
      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->




<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\projects\ayurvedicmlm\resources\views/user/kyc/index.blade.php ENDPATH**/ ?>