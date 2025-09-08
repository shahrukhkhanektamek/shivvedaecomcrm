<?php echo $__env->make("salesman/include/header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



        <!-- content -->
        <div class="container mt-3 mt-lg-4 mt-xl-5" id="main-content">
           


            <div class="row gx-3 align-items-center justify-content-center py-3 mt-auto z-index-1 height-dynamic" >
                <div class="col login-box maxwidth-400">
                    
                    <form class="account__form form_data" action="<?php echo e($data['submit_url']); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                        <?php echo csrf_field(); ?>
                        <div class="position-relative">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="checkstrength" placeholder="Enter your new password" required>
                                <label for="checkstrength">New Password</label>
                            </div>
                            <button type="button" class="btn btn-square btn-link text-theme-1 position-absolute end-0 top-0 mt-2 me-2 " onclick="togglePasswordVisibility(this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        
                        <div class="position-relative">
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="passwdconfirm" placeholder="Confirm your new password" required>
                                <label for="passwdconfirm">Confirm Password</label>
                            </div>
                            <button type="button" class="btn btn-square btn-link text-theme-1 position-absolute end-0 top-0 mt-2 me-2 " onclick="togglePasswordVisibility(this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <button class="btn btn-lg btn-theme w-100 mb-4">Change Now</button>
                    </form>

                    
                </div>
            </div>

        
        </div>        

 <?php echo $__env->make("salesman/include/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\htdocs\projects\irshad\shivvedaecomcrm\resources\views/salesman/change-password/index.blade.php ENDPATH**/ ?>