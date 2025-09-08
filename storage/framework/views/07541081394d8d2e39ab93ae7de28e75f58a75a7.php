<?php echo $__env->make('user.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php ($user = Helpers::get_user_user()); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Dashboard</h1>
      <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Dashboard</li>
      </ol>
    </div>
    
    <!-- Main content -->
    <div class="content"> 
      <!-- Small boxes (Stat box) -->

      <div class="row">
        <div class="col-lg-7 col-xlg-9" style="margin: 0 auto;">
          <div class="info-box">


            <div class="box box-widget widget-user"> 
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-black" style="background: url('<?php echo e(url('public/user/')); ?>/dist/img/user-bg.jpg');">
                <h3 class="widget-user-username"><?php echo e($user->name); ?></h3>
                <h5 class="widget-user-desc "><b class="text-white">Rank: </b>Not upgrade</h5>
              </div>
              <div class="widget-user-image"> <img class="img-circle" src="<?php echo e(Helpers::image_check($user->image,'user.png')); ?>" alt="User Avatar"> </div>
              <div class="box-footer">
                
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <span class="description-text">Total Income</span>
                      <h5 class="description-header"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::all_time_income($user->id))); ?></h5>
                    </div>
                    <!-- /.description-block --> 
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <span class="description-text">Today Income</span>
                      <h5 class="description-header"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::all_time_income($user->id))); ?></h5>
                    </div>
                    <!-- /.description-block --> 
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <span class="description-text">Package</span>
                      <h5 class="description-header">Basic</h5>
                    </div>
                    <!-- /.description-block --> 
                  </div>
                  <!-- /.col --> 
                </div>
                <!-- /.row --> 
              </div>
            </div>


            
            



          
            <p class="clearfix">
              <span class="float-left">My Team</span>
              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e(@\App\Models\MemberModel::totalTeam($user->id)); ?> Members</span>
            </p>
            <p class="clearfix">
              <span class="float-left">My Direct</span>
              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e(@\App\Models\MemberModel::totalDirect($user->id)); ?> Members</span>
            </p>
            <p class="clearfix">
              <span class="float-left">My Matching</span>
              <span class="float-right"><i class="fa fa-rupee-sign"></i>4 Pairs</span>
            </p>
            <p class="clearfix">
              <span class="float-left">Left Paid Members</span>
              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_left_paid); ?></span>
            </p>
            <p class="clearfix">
              <span class="float-left">Right Paid Members</span>
              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_right_paid); ?></span>
            </p>
            <p class="clearfix">
              <span class="float-left">Left Unpaid Members</span>
              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_left_unpaid); ?></span>
            </p>
            <p class="clearfix">
              <span class="float-left">Right Unpaid Members</span>
              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_right_unpaid); ?></span>
            </p>
            <p class="clearfix">
              <span class="float-left">Activation Date</span>
              <span class="float-right"><i class="fa fa-rupee-sign"></i>
                <?php if(!empty($user->activate_date_time)): ?>
                  <?php echo e(date("d D M, Y", strtotime($user->activate_date_time))); ?>

                <?php endif; ?>

                <span class="badge badge-danger">ID Not Active</span>

              </span>
            </p>
            

            
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><h4>All Income</h4></div>
            <div class="card-body row">
              
              <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box bg-darkblue"> 
                  <div class="info-box-content">
                    <h6 class="info-box-text text-white">Direct Income</h6>
                    <h1 class="text-white"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($user->id)->income1)); ?></h1> 
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box bg-darkblue"> 
                  <div class="info-box-content">
                    <h6 class="info-box-text text-white">Pair Income</h6>
                    <h1 class="text-white"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($user->id)->income2)); ?></h1> 
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box bg-darkblue"> 
                  <div class="info-box-content">
                    <h6 class="info-box-text text-white">Downline Income</h6>
                    <h1 class="text-white"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($user->id)->income3)); ?></h1> 
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box bg-darkblue"> 
                  <div class="info-box-content">
                    <h6 class="info-box-text text-white">Upline Income</h6>
                    <h1 class="text-white"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($user->id)->income4)); ?></h1> 
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box bg-darkblue"> 
                  <div class="info-box-content">
                    <h6 class="info-box-text text-white">Rank Bonus Income</h6>
                    <h1 class="text-white"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($user->id)->income5)); ?></h1> 
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="col-lg-12 mt-3">
          <div class="card">
            <div class="card-header"><h4>Joining Links</h4></div>
            <div class="card-body row">
              
              <div class="col-sm-12">
                <div class="info-box"> 
                  <p><?php echo e(url('/registration?sponser_id=6&side=left')); ?></p>
                  <button class="btn btn-success" onclick="copyToClipboard('<?php echo e(url('/registration?sponser_id=6&side=left')); ?>')" >Copy Left Link</button>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="info-box"> 
                  <p><?php echo e(url('/registration?sponser_id=6&side=right')); ?></p>
                  <button class="btn btn-success" onclick="copyToClipboard('<?php echo e(url('/registration?sponser_id=6&side=right')); ?>')">Copy Right Link</button>
                </div>
              </div>
              

            </div>
          </div>
        </div>

        <div class="col-lg-12 mt-3">
          <div class="card">
            <div class="card-header"><h4>Rank List</h4></div>
            <div class="card-body row">
              
              <table class="table table-respinsive">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Rank</th>
                    <th>Target</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Sr. Executive</td>
                    <td>2 ID : 1 ID</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 500/-</td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Star Executive</td>
                    <td>2 Sr. Ex. : 1 Sr. Ex.</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 1500/-</td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Super Star Executive</td>
                    <td>2 Star Ex. : 1 Star Ex.</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 2500/-</td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td>Silver Executive</td>
                    <td>2 Super Satr Ex. : 1 Super Satr Ex.</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 10,000/-</td>
                  </tr>
                  <tr>
                    <td>5</td>
                    <td>Gold Executive</td>
                    <td>3 Silver Ex. : 2 Silver Ex.</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 50,000/-</td>
                  </tr>
                  <tr>
                    <td>6</td>
                    <td>Super Gold Executive</td>
                    <td>3 Gold Ex. : 2 Gold Ex.</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 2,50,000/-</td>
                  </tr>
                  <tr>
                    <td>7</td>
                    <td>Daimond Executive</td>
                    <td>3 Super Gold Ex. : 2 Super Gold Ex.</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 10,00,000,00/-</td>
                  </tr>
                  <tr>
                    <td>8</td>
                    <td>Super Daimond Executive</td>
                    <td>3 Daimond Ex. : 2 Daimond Ex.</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 50,00,000/-</td>
                  </tr>
                  <tr>
                    <td>9</td>
                    <td>Saphire Daimond Executive</td>
                    <td>1 Super Daimond Ex. : 1 Super Daimond</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 1,00,000,00 </td>
                  </tr>
                  <tr>
                    <td>10</td>
                    <td>Crown Daimond Executive</td>
                    <td>1 Saphire Daimond Ex. : 1 Saphire Daimond Ex.</td>
                    <td><i class="fa fa-spinner fa-spin"></i> 2,50,000,00/-</td>
                  </tr>
                </tbody>
              </table>
              

            </div>
          </div>
        </div>

      </div>


     
      
    </div>
  </div>
  <!-- /.content-wrapper -->




<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\projects\ayurvedicmlm\resources\views/user/dashboard/index.blade.php ENDPATH**/ ?>