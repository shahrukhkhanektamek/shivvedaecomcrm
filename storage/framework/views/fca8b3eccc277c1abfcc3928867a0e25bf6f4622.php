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
        <div class="col-lg-4" style="margin: 0 auto;">
          <div class="user-profile-box m-b-3">
            <div class="box-profile text-white"> <img class="profile-user-img img-responsive img-circle m-b-2" src="<?php echo e(Helpers::image_check($row->image,'user.png')); ?>" alt="User profile picture">
              <h3 class="profile-username text-center"><?php echo e($row->name); ?></h3>
            </div>
          </div>
          
        </div>
        <div class="col-lg-12">
          <div class="info-box">
            <form class="form row form_data" action="<?php echo e($data['submit_url']); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                <?php echo csrf_field(); ?>


                

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Name</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-user"></i></div>
                      <input class="form-control" placeholder="Name" type="text" name="name" value="<?php echo e($row->name); ?>" required>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                      <input class="form-control" placeholder="Email" type="email" name="email" value="<?php echo e($row->email); ?>" required>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Select Country</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-globe"></i></div>
                      <select class="form-select mb-3" aria-label="Default select example" name="country" required>
                          <option value=""  >Select</option>
                          <?php ($countries = DB::table('countries')->get()); ?>
                          <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($value->id); ?>" <?php if($row->country==$value->id): ?>selected <?php endif; ?> ><?php echo e($value->name); ?> (+<?php echo e($value->phonecode); ?>)</option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-8">
                  <div class="form-group">
                    <label>Phone</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                      <input class="form-control" placeholder="Phone" type="number" name="phone" value="<?php echo e($row->phone); ?>" required disabled>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>State</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-circle-o"></i></div>
                      <select class="form-select mb-3" aria-label="Default select example" name="state" required>
                          <option value=""  >Select</option>
                          <?php ($states = DB::table('states')->get()); ?>
                          <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($value->id); ?>" <?php if($row->state==$value->id): ?> selected <?php endif; ?> ><?php echo e($value->name); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>City</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-circle-o"></i></div>
                      <input class="form-control" placeholder="City" type="text" name="city" value="<?php echo e($row->city); ?>" required>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Address</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                      <input class="form-control" placeholder="Address" type="text" name="address" value="<?php echo e($row->address); ?>" required>
                    </div>
                  </div>
                </div>

                
                
                <div class="col-md-6">
                  <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Update</button>
                </div>

              </form>
          </div>
        </div>
      </div>
      <!-- Main row --> 
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->

<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/user/profile/index.blade.php ENDPATH**/ ?>