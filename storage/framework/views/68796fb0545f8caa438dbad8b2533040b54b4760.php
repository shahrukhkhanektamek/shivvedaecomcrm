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



              
              <form class="form row form_data" action="<?php echo e($data['submit_url']); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                <?php echo csrf_field(); ?>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                      <input class="form-control" placeholder="Enter Password" type="password">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                      <input class="form-control" placeholder="Confirm Password" type="confirm_password">
                    </div>
                  </div>
                </div>
                
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
                
              </form>


             
            </div>
          </div>
        </div>
      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->




<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/user/change-password/index.blade.php ENDPATH**/ ?>