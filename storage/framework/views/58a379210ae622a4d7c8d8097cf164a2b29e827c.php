<?php echo $__env->make('user.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php ($user = Helpers::get_user_user()); ?>



<?php ($incomePlan = DB::table('income_plan')->first()); ?>



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



      <?php if($user->is_paid==0): ?>

        <div class="alert alert-warning"><strong>You have no active plan!</strong> Please contact your sponser or <?php echo e(env('APP_NAME')); ?>.



          <?php if($user->total_bv<$incomePlan->id_bv && $user->is_paid==0): ?>

            <p class="m-0">Purchase any products and complete your <?php echo e($incomePlan->id_bv); ?> BV You have <?php echo e($user->total_bv); ?> bv now <a href="<?php echo e(route('user.activation.index')); ?>">click here</a> and activate your Id.</p>

          <?php endif; ?>



        </div>



      <?php elseif($user->kyc_step==0): ?>

        <div class="alert alert-warning"><strong>Your Kyc is pending!</strong> Please update your Kyc.<a href="<?php echo e(route('user.kyc.index')); ?>">Click Here</a></div>

      <?php endif; ?>





      <div class="row">

        <div class="col-lg-9 col-xlg-9" style="margin: 0 auto;">

          <div class="info-box">





            <div class="box box-widget widget-user"> 

              <!-- Add the bg color to the header using any of the bg-* classes -->

              <div class="widget-user-header bg-black" style="background: url('<?php echo e(url('public/user/')); ?>/dist/img/user-bg.jpg');">

                <h3 class="widget-user-username"><?php echo e($user->name); ?></h3>

                <h5 class="widget-user-desc "><b class="text-white">Rank: </b><?php if($user->rank<1): ?> Not upgrade <?php else: ?> <?php echo e(\App\Models\MemberModel::rank($user->rank)); ?> <?php endif; ?></h5>

              </div>

              <div class="widget-user-image"> <img class="img-circle" src="<?php echo e(Helpers::image_check($user->image,'user.png')); ?>" alt="User Avatar"> 

              </div>

              <div class="box-footer text-center">

                <!-- <strong>ID: <?php echo e($user->user_id); ?></strong> -->

                

                <div class="row">

                  <div class="col-sm-4 border-right1" style="margin:0 auto;">

                    <div class="description-block">

                      <span class="description-text">Total Income</span>

                      <h5 class="description-header"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::all_time_income($user->id))); ?></h5>

                    </div>

                    <!-- /.description-block --> 

                  </div>





                  <div class="col-sm-4 border-right1" style="margin: 0 auto;">

                    <div class="description-block">

                      <span class="description-text">ID Number</span>

                      <h5 class="description-header"><?php echo e($user->user_id); ?></h5>

                    </div>

                    <!-- /.description-block --> 

                  </div>







                  <!-- /.col -->

                  <div class="col-sm-4 border-right1" style="margin: 0 auto;">

                    <div class="description-block">

                      <span class="description-text">Today Income</span>

                      <h5 class="description-header"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::today_income($user->id))); ?></h5>

                    </div>

                    <!-- /.description-block --> 

                  </div>

                  <!-- /.col -->

                  <!-- <div class="col-sm-4 hide">

                    <div class="description-block">

                      <span class="description-text">Package</span>

                      <h5 class="description-header"><?php echo e(@\App\Models\MemberModel::active_package($user->id)->package_name); ?></h5>

                    </div>

                  </div> -->

                  <!-- /.col --> 

                </div>

                <!-- /.row --> 

              </div>

            </div>





            

            







          

            <p class="clearfix">

              <span class="float-left">My Team</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e(@\App\Models\MemberModel::totalTeam($user->id)); ?> Partners</span>

            </p>

            <p class="clearfix">

              <span class="float-left">My Direct Paid</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e(@\App\Models\MemberModel::totalDirect($user->id,1)); ?> Partners</span>

            </p>

            <p class="clearfix">

              <span class="float-left">My Direct UnPaid</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e(@\App\Models\MemberModel::totalDirect($user->id,2)); ?> Partners</span>

            </p>

            <p class="clearfix">

              <span class="float-left">My Matching</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_pairs); ?> Pairs</span>

            </p>

            <p class="clearfix">

              <span class="float-left">Total Self BV</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_bv); ?></span>

            </p>



            <p class="clearfix">

              <span class="float-left">Total Left Team BV</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->left_bv); ?></span>

            </p>



            <p class="clearfix">

              <span class="float-left">Total Right Team BV</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->right_bv); ?></span>

            </p>



            <p class="clearfix">

              <span class="float-left">Left Paid Partners</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_left_paid); ?></span>

            </p>

            <p class="clearfix">

              <span class="float-left">Right Paid Partners</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_right_paid); ?></span>

            </p>

            <p class="clearfix">

              <span class="float-left">Left Unpaid Partners</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_left_unpaid); ?></span>

            </p>

            <p class="clearfix">

              <span class="float-left">Right Unpaid Partners</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i><?php echo e($user->total_right_unpaid); ?></span>

            </p>

            <p class="clearfix">

              <span class="float-left">Activation Date</span>

              <span class="float-right"><i class="fa fa-rupee-sign"></i>

                <?php if(!empty($user->activate_date_time)): ?>

                  <?php echo e(date("d D M, Y", strtotime($user->activate_date_time))); ?>


                <?php endif; ?>



                <?php if($user->is_paid!=1): ?>

                  <span class="badge badge-danger">ID Not Active</span>

                <?php endif; ?>

              </span>

            </p>

            



            

          </div>

        </div>

      </div>





      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-header"><h4>All Income</h4></div>

            <div class="card-body row allincome m-0">

              

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

              <div class="col-lg-3 col-sm-6 col-xs-12">

                <div class="info-box bg-darkblue"> 

                  <div class="info-box-content">

                    <h6 class="info-box-text text-white">Repurchase Income</h6>

                    <h1 class="text-white"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($user->id)->income6)); ?></h1> 

                  </div>

                </div>

              </div>



            </div>

          </div>

        </div>



        <div class="col-lg-12 mt-3 joininglinks">

          <div class="card">

            <div class="card-header"><h4>Joining Links</h4></div>

            <div class="card-body row m-0">

              

              <div class="col-sm-12">

                <div class="info-box"> 

                  <p><?php echo e(url('/registration?sponser_id='.$user->user_id.'&side=left')); ?></p>

                  <button class="btn btn-success" onclick="shareBtn('<?php echo e(url('/registration?sponser_id='.$user->user_id.'&side=left')); ?>')" >Share Left Link</button>

                </div>

              </div>



              <div class="col-sm-12">

                <div class="info-box"> 

                  <p><?php echo e(url('/registration?sponser_id='.$user->user_id.'&side=right')); ?></p>

                  <button class="btn btn-success" onclick="shareBtn('<?php echo e(url('/registration?sponser_id='.$user->user_id.'&side=right')); ?>')">Share Right Link</button>

                </div>

              </div>

              



            </div>

          </div>

        </div>



        <div class="col-lg-12 mt-3">

          <div class="card">

            <div class="card-header"><h4>Rank List</h4></div>

            <div class="card-body row m-0">

              

              <table class="table table-responsive">

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

                    <td><?php if($user->rank>=1): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 500/-</td>

                  </tr>

                  <tr>

                    <td>2</td>

                    <td>Star Executive</td>

                    <td>2 Sr. Ex. : 1 Sr. Ex.</td>

                    <td><?php if($user->rank>=2): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 1500/-</td>

                  </tr>

                  <tr>

                    <td>3</td>

                    <td>Super Star Executive</td>

                    <td>2 Star Ex. : 1 Star Ex.</td>

                    <td><?php if($user->rank>=3): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 2500/-</td>

                  </tr>

                  <tr>

                    <td>4</td>

                    <td>Silver Executive</td>

                    <td>2 Super Satr Ex. : 1 Super Satr Ex.</td>

                    <td><?php if($user->rank>=4): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 10,000/-</td>

                  </tr>

                  <tr>

                    <td>5</td>

                    <td>Gold Executive</td>

                    <td>3 Silver Ex. : 2 Silver Ex.</td>

                    <td><?php if($user->rank>=5): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 50,000/-</td>

                  </tr>

                  <tr>

                    <td>6</td>

                    <td>Super Gold Executive</td>

                    <td>3 Gold Ex. : 2 Gold Ex.</td>

                    <td><?php if($user->rank>=6): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 2,50,000/-</td>

                  </tr>

                  <tr>

                    <td>7</td>

                    <td>Daimond Executive</td>

                    <td>3 Super Gold Ex. : 2 Super Gold Ex.</td>

                    <td><?php if($user->rank>=7): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 10,00,000/-</td>

                  </tr>

                  <tr>

                    <td>8</td>

                    <td>Super Daimond Executive</td>

                    <td>3 Daimond Ex. : 2 Daimond Ex.</td>

                    <td><?php if($user->rank>=8): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 50,00,000/-</td>

                  </tr>

                  <tr>

                    <td>9</td>

                    <td>Saphire Daimond Executive</td>

                    <td>1 Super Daimond Ex. : 1 Super Daimond</td>

                    <td><?php if($user->rank>=9): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 1,00,000,00 </td>

                  </tr>

                  <tr>

                    <td>10</td>

                    <td>Crown Daimond Executive</td>

                    <td>1 Saphire Daimond Ex. : 1 Saphire Daimond Ex.</td>

                    <td><?php if($user->rank>=10): ?> <span class="text-bold text-green">Completed</span> <?php else: ?> <i class="fa fa-spinner fa-spin"></i> <?php endif; ?> 2,50,000,00/-</td>

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

<?php /**PATH D:\xamp\htdocs\projects\irshad\shivveda.in\resources\views/user/dashboard/index.blade.php ENDPATH**/ ?>