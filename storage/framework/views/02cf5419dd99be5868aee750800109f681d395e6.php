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
                                            
                                            <div class="col-lg-2">
                                                <label for="formFile" class="form-label">Plan Type</label>
                                                <select class="form-select mb-3" aria-label="Default select example" name="type">
                                                    <option value="1" <?php if(!empty(@$row) && @$row->type==1): ?>selected <?php endif; ?> >Id Green</option>
                                                    <option value="2" <?php if(!empty(@$row) && @$row->type==2): ?>selected <?php endif; ?> >BV Plan</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Direct Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Direct Income" name="income1" value="<?php echo e(@$row->income1); ?>" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Pair Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Pair Income" name="income2" value="<?php echo e(@$row->income2); ?>" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Downline Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Downline Income" name="income3" value="<?php echo e(@$row->income3); ?>" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Upline Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Upline Income" name="income4" value="<?php echo e(@$row->income4); ?>" required>
                                            </div>

                                            <div class="col-lg-2 hide">
                                                <label class="form-label" for="product-title-input">Rank Bonus Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Rank Bonus Income" name="income5" value="<?php echo e(@$row->income5); ?>" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Id Green BV</label>
                                                <input type="number" class="form-control" placeholder="Enter ID BV Green" name="id_bv" value="<?php echo e(@$row->id_bv); ?>" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Per Day Pair Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Per Day Pair Income" name="per_day_pair_income" value="<?php echo e(@$row->per_day_pair_income); ?>" required>
                                            </div>

                                            

                                            

                                        </div>
                                        <!-- end card -->
                                        <div class="text-center mt-2 mb-3">
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
</html><?php /**PATH /home/shivved1/public_html/resources/views/admin/setting/plansetting-form.blade.php ENDPATH**/ ?>