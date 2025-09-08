<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
    <meta charset="utf-8" />
    <title><?php echo e($data['page_title']); ?> | <?php echo e(env("APP_NAME")); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Start Include Css -->
    <?php echo $__env->make('admin.headers.maincss', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- End Include Css -->
</head>
<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- Start Include Header -->
        <?php echo $__env->make('admin.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- End Include Header -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0"><?php echo e($data['page_title']); ?></h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Home</a></li>
                                        <li class="breadcrumb-item active"><?php echo e($data['page_title']); ?>

                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <form class="needs-validation form_data" action="<?php echo e($data['submit_url']); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e(Crypt::encryptString(@$row->id)); ?>">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body frm">
                                        <div class="row">



                                            <div class="col-6">
                                                <h4>User Detail</h4>
                                                <p><strong>User ID.</strong> <?php echo e($user->user_id); ?></p>
                                                <p><strong>Name</strong> <?php echo e($user->name); ?></p>
                                                <p><strong>Email</strong> <?php echo e($user->email); ?></p>
                                                <p><strong>Phone</strong> <?php echo e($user->phone); ?></p>
                                            </div>

                                            <div class="col-6">
                                                <h4>Shipping Detail</h4>
                                                <p><strong>Order ID.</strong> #<?php echo e($row->order_id); ?></p>
                                                <p><strong>Name</strong> <?php echo e($row->name); ?></p>
                                                <p><strong>Email</strong> <?php echo e($row->email); ?></p>
                                                <p><strong>Phone</strong> <?php echo e($row->phone); ?></p>
                                                <p><strong>State</strong> <?php echo e($row->state); ?></p>
                                                <p><strong>City</strong> <?php echo e($row->city); ?></p>
                                                <p><strong>Address</strong> <?php echo e($row->address); ?></p>
                                            </div>


                                           <div class="col-12">
                                               <?php ($orderProducts = DB::table("order_products")->where("order_id",$row->order_id)->get()); ?>
                                                <table class="table">
                                                    <thead class="bg-success text-white">
                                                        <tr>                    
                                                            <th scope="col">#</th>
                                                            <th scope="col">Product name</th>
                                                            <th scope="col">Price</th>
                                                            <th scope="col">QTY</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                      <?php $__currentLoopData = $orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                          <td><?php echo e($key+1); ?></td>
                                                          <td><?php echo e($value->name); ?></td>
                                                          <td><?php echo e(Helpers::price_formate($value->price)); ?></td>
                                                          <td><?php echo e($value->qty); ?></td>
                                                        </tr>
                                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                                      <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <th>Sub Total</th>
                                                        <th><?php echo e(Helpers::price_formate($row->amount)); ?></th>
                                                      </tr>
                                                      <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <th>Gst</th>
                                                        <th>+<?php echo e(Helpers::price_formate($row->gst)); ?></th>
                                                        <!-- <th>Included</th> -->
                                                      </tr>
                                                      <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <th>Total Amount</th>
                                                        <th><?php echo e(Helpers::price_formate($row->final_amount)); ?></th>
                                                      </tr>
                                                      <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <th>Wallet Amount</th>
                                                        <th>-<?php echo e(Helpers::price_formate($row->wallet_amount)); ?></th>
                                                      </tr>

                                                      <tr>
                                                        <th>Total BV: <?php echo e($row->bv); ?></th>
                                                        <td></td>
                                                        <th>Payable Amount</th>
                                                        <th><?php echo e(Helpers::price_formate($row->final_amount-$row->wallet_amount)); ?></th>
                                                      </tr>

                                                    </tbody>
                                                </table>
                                           </div>

                                            <div class="col-6">
                                                <img src="<?php echo e(Helpers::image_check($row->screenshot,'default.jpg')); ?>" class="img-responsive img-thumbnail big-image" alt="User" style="width: 100%;cursor: pointer;">
                                            </div>
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="formFile" class="form-label">Select Branch</label>
                                                        <select class="form-select mb-3" aria-label="Default select example" name="branch" required>
                                                            <option value="" <?php if(@$row->branch==0): ?>selected <?php endif; ?> >Select Branch</option>
                                                            <?php ($branch = DB::table('branch')->get()); ?>
                                                            <?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($value->id); ?>" <?php if(@$row->branch==$value->id): ?>selected <?php endif; ?> ><?=$value->name ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>

                                                    
                                                    <div class="col-lg-6">
                                                        <label for="formFile" class="form-label">Update Status</label>
                                                        <select class="form-select mb-3" aria-label="Default select example" name="status">
                                                            <option value="0" <?php if(@$row->status==0): ?>selected <?php endif; ?> >New</option>
                                                            <option value="1" <?php if(@$row->status==1): ?>selected <?php endif; ?> >Proccess</option>
                                                            <option value="2" <?php if(@$row->status==2): ?>selected <?php endif; ?> >Shipped</option>
                                                            <option value="3" <?php if(@$row->status==3): ?>selected <?php endif; ?> >Delivered</option>
                                                            <option value="4" <?php if(@$row->status==4): ?>selected <?php endif; ?> >Cancel</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>

                                           
                                                                                  
                                           
                                            
                                            
                                            
                                        </div>
                                        <!-- end card -->
                                        <div class="text-center mt-5 mb-3">
                                            <button type="submit" class="btn btn-success w-sm">Update</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Page-content -->
            <!-- Start Include Footer -->
            <?php echo $__env->make('admin.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!-- End Include Footer -->
        </div>
    </div>
    <!-- END layout-wrapper -->
    <!-- Start Include Script -->
    <?php echo $__env->make('admin.headers.mainjs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- End Include Script -->
</body>
</html><?php /**PATH D:\xamp\htdocs\projects\irshad\shivveda.in\resources\views/admin/order/view.blade.php ENDPATH**/ ?>