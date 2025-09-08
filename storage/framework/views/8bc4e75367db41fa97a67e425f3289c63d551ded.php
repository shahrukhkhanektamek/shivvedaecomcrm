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

    <style>
        .form-control {
            font-size: 15px;
            font-weight: 900;
        }
    </style>

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
                    <?php echo $__env->make('admin.user.profile-card', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <!-- end page title -->
                    <form class="mt-2 needs-validation form_data" action="<?php echo e($data['submit_url']); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e(Crypt::encryptString(@$row->id)); ?>">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body frm">
                                        <div class="row">

                                            <?php if(!empty($row->bank_holder_name1)): ?>
                                                <h1>First KYC Detail</h1>
                                            <?php endif; ?>

                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Your Name as per Bank Account </label>
                                                <input type="text" class="form-control" placeholder="Enter Name" name="bank_holder_name" value="<?php echo e(@$row->bank_holder_name); ?>" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Bank Name</label>
                                                <input type="text" class="form-control" placeholder="Enter Bank Name" name="bank_name" value="<?php echo e(@$row->bank_name); ?>" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Account Number</label>
                                                <input type="text" class="form-control" placeholder="Enter Account Number" name="account_number" value="<?php echo e(@$row->account_number); ?>" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Account Type </label>
                                                <?php echo e($row->account_type); ?>

                                                <select class="form-select mb-3" aria-label="Default select example" name="account_type">
                                                    <option value="Saving" <?php if(@$row->account_type=='Saving'): ?>selected <?php endif; ?> >Saving</option>
                                                    <option value="Current" <?php if(@$row->account_type=='Current'): ?>selected <?php endif; ?> >Current</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">IFSC </label>
                                                <input type="text" class="form-control" placeholder="Enter IFSC" name="ifsc" value="<?php echo e(@$row->ifsc); ?>" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">PAN Card</label>
                                                <input type="text" class="form-control" placeholder="Enter Pan Card" name="pan" value="<?php echo e(@$row->pan); ?>" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Bank Registered Mobile</label>
                                                <input type="text" class="form-control" placeholder="Enter Mobile No." name="rg_mobile" value="<?php echo e(@$row->rg_mobile); ?>" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Bank Registered Email</label>
                                                <input type="text" class="form-control" placeholder="Enter Email" name="rg_email" value="<?php echo e(@$row->rg_email); ?>" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Address </label>
                                                <input type="text" class="form-control" placeholder="Enter Address" name="address" value="<?php echo e(@$row->address); ?>" required>
                                            </div>
                                            
                                            
                                            
                                            
                                            <div class="col-lg-6">
                                                <label for="formFile" class="form-label">Bank Passbook Image</label>
                                                <label style="display:block">
                                                    <input class="form-control upload-single-image" type="file" name="passbook_image" data-target="passbook_image">
                                                </label>
                                                    <img class="upload-img-view img-thumbnail mt-2 mb-2 passbook_image big-image" id="viewer" 
                                                        src="<?php echo e(Helpers::image_check($row->passbook_image)); ?>" alt="banner image" style="width: 150px;height: 150px;" />
                                            </div>






                                            <div class="col-lg-12">
                                                <label for="formFile" class="form-label">KYC Change Image</label>
                                                <label style="display:block" class="mt-2">
                                                    <input class="form-control upload-single-image" type="file" name="kyc_change_image" data-target="kyc_change_image">
                                                </label>
                                                <textarea class="form-control" ><?php echo e(@$row->kyc_change_message); ?></textarea>
                                                <img class="upload-img-view img-thumbnail mt-2 mb-2 kyc_change_image big-image" id="viewer"
                                                    onerror="this.src='<?php echo e(asset('storage/app/public/upload/default.jpg')); ?>'"
                                                    src="<?php echo e(asset('storage/app/public/upload/')); ?>/<?php echo e(@$row->kyc_change_image); ?>" alt="banner image" style="width: 150px;height: 150px;" />
                                            </div>
                                            



                                            <div class="col-lg-6">
                                                <label for="formFile" class="form-label">Kyc Status</label>
                                                <select class="form-select mb-3" aria-label="Default select example" name="kyc_step">
                                                    <option value="1" <?php if(@$row->kyc_step==1): ?>selected <?php endif; ?> >Approve</option>
                                                    <option value="2" <?php if(@$row->kyc_step==2): ?>selected <?php endif; ?> >New</option>
                                                    <option value="3" <?php if(@$row->kyc_step==3): ?>selected <?php endif; ?> >Reject</option>
                                                    <option value="4" <?php if(@$row->kyc_step==4): ?>selected <?php endif; ?> >Change Request</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label" for="product-title-input">Kyc Message </label>
                                                <select class="form-control" name="kyc_message" >
                                                    <option value="">Select</option>
                                                    <?php ($all_option = \App\Models\KycOption::all_option()); ?>
                                                    <?php $__currentLoopData = $all_option; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($value->name); ?>" <?php if($row->kyc_message==$value->name): ?> selected <?php endif; ?> ><?php echo e($value->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            
                                           
                                            
                                            
                                            
                                        </div>
                                        <!-- end card -->
                                        <div class="text-center mt-5 mb-3">
                                            <button type="submit" class="btn btn-success w-sm">Save</button>
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
</html><?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/admin/bank/form.blade.php ENDPATH**/ ?>