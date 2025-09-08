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

                    <?php echo $__env->make('admin.user.profile-card', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <!-- end page title -->

                    <form class="mt-2 needs-validation form_data" action="<?php echo e(route('user.change-sponser-action')); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>

                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="id" value="<?php echo e(Crypt::encryptString(@$row->id)); ?>">
                        <input type="hidden" name="pin" id="setPin" value="">

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="card">

                                    <div class="card-body frm">

                                        <div class="row">

                                            

                                            <div class="col-lg-4">

                                                <label class="form-label" for="product-title-input">User Sponser </label>

                                                <select class="form-control kyc" id="user-sponser" disabled>

                                                    <option value="<?php echo e($row->sponser_id); ?>"><?php echo e($row->sponser_id); ?>/<?php echo e($row->sponser_name); ?></option>

                                                </select>

                                            </div>

                                            

                                            <div class="col-lg-4">

                                                <label class="form-label" for="product-title-input">Select New Sponser </label>

                                                <select class="form-control kyc" id="new-sponser" name="sponser_id">

                                                    <option value="">Select</option>

                                                </select>

                                            </div>



                                            <div class="col-lg-4">

                                                <label for="formFile" class="form-label">Placement</label>

                                                <select class="form-select mb-3" aria-label="Default select example" name="placement">

                                                    <option value="0" >Select Placement</option>

                                                    <option value="1" <?php if(@$row->placement==1): ?>selected <?php endif; ?> >Left</option>

                                                    <option value="2" <?php if(@$row->placement==2): ?>selected <?php endif; ?> >Right</option>

                                                </select>

                                            </div>



                                            

                                            

                                        </div>

                                        <!-- end card -->

                                        <div class="text-center mt-5 mb-3">

                                            <button type="button" id="sponser-change-modal-open" class="btn btn-success w-sm">Save</button>
                                            <button type="submit" id="submitBtn" class="btn btn-success w-sm" style="display:none;">Save</button>

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



    <div class="modal fade zoomIn" id="ChangeSponserPinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close" id="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Enter Sponser Change PIN</h4>
                            <input type="number" class="form-control" id="sponser-change-pin">
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-danger "
                            id="change-sponser-confirm">Sponser Change Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- END layout-wrapper -->

    <!-- Start Include Script -->

    <?php echo $__env->make('admin.headers.mainjs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- End Include Script -->










    <script>

        $(document).on("click", "#sponser-change-modal-open",(function(e) {
              $("#ChangeSponserPinModal").modal('show');
        }));
        $(document).on("keyup", "#sponser-change-pin",(function(e) {
              $("#setPin").val($(this).val());
        }));
        $(document).on("click", "#change-sponser-confirm",(function(e) {
            $("#submitBtn").trigger('click');
        }));

        $('#new-sponser').select2({

          ajax: {

            url: "<?php echo e(route('search-sponser')); ?>",

            method:"post",

            "headers": {

            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

           },

            data: function (params) {

              var query = {

                search: params.term,

                type: 'public'

              }



              // Query parameters will be ?search=[term]&type=public

              return query;

            }

          }

        });

    </script>



</body>

</html><?php /**PATH /home/shivved1/public_html/resources/views/admin/user/change-sponser.blade.php ENDPATH**/ ?>