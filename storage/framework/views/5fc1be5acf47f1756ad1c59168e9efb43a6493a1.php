
<!DOCTYPE html>
<html lang="en">
<!-- dir="rtl"-->
<head>
    <!-- Required meta tags  -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">    
    <link rel="apple-touch-icon" href="<?php echo e(url('/public/salesman/')); ?>/assets/img/logo-512.png">

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo e(env('APP_NAME')); ?></title>
    <meta name="description" content="">
    <meta name="_token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(url('/public/salesman/')); ?>/assets/img/favicon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --adminuiux-content-font: "Open Sans", serif;
            --adminuiux-content-font-weight: 400;
            --adminuiux-title-font: "Poppins", serif;
            --adminuiux-title-font-weight: 600;
        }
    </style>
    <script defer src="<?php echo e(url('/public/salesman/')); ?>/assets/js/app.js?e5e60e3925d7f9a7cded"></script>
    <link href="<?php echo e(url('/public/salesman/')); ?>/assets/css/app.css?e5e60e3925d7f9a7cded" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo e(url('public')); ?>/toast/saber-toast.css">
    <link rel="stylesheet" href="<?php echo e(url('public')); ?>/toast/style.css">
    <link rel="stylesheet" href="<?php echo e(url('public')); ?>/front_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo e(url('public')); ?>/front_script.js"></script>

</head>

    <body class="main-bg main-bg-opac main-bg-blur roundedui adminuiux-header-standard adminuiux-sidebar-standard adminuiux-mobile-footer-fill-theme adminuiux-header-transparent theme-pista bg-r-gradient adminuiux-sidebar-fill-none scrollup" data-theme="theme-pista" data-sidebarfill="adminuiux-sidebar-fill-none" data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" tabindex="0" data-sidebarlayout="adminuiux-sidebar-standard"
        data-headerlayout="adminuiux-header-standard" data-headerfill="adminuiux-header-transparent" data-bggradient="bg-r-gradient" style="">
        <!-- page loader -->
        <!-- Pageloader -->
            <div class="pageloader">
                <div class="container h-100">
                    <div class="row justify-content-center align-items-center text-center h-100 pb-ios">
                        <div class="col-12 mb-auto pt-4"></div>
                        <div class="col-auto">
                            <img src="<?php echo e(url('/public/salesman/')); ?>/assets/img/logo.png" alt="" class="height-80 mb-3">
                            
                            <p class="display-3 text-theme-1 fw-bold mb-4"><?php echo e(env('APP_NAME')); ?></p>
                            <div class="loader5 mb-2 mx-auto"></div>
                        </div>
                        <div class="col-12 mt-auto pb-4">
                            <p class="small text-secondary">Please wait we are preparing awesome things...</p>
                        </div>
                    </div>
                </div>
            </div> <!-- main content -->
 
                <div class="adminuiux-wrap z-index-0 position-relative">
                    <main class="adminuiux-content">
                        <!--Page body-->
                        <div class="container-fluid">
                            <div class="row gx-3 align-items-center justify-content-center py-3 mt-auto z-index-1 height-dynamic" style="--h-dynamic: calc(100vh - 120px)">
                                <div class="col login-box maxwidth-400">
                                    <img src="<?php echo e(url('/public/salesman/')); ?>/assets/img/logo.png" alt="" class="height-80 mb-3" style="margin: 0 auto;display: block;">
                                    <div class="mb-4">
                                        <h3 class="text-theme-1 fw-normal mb-0">Welcome to,</h3>
                                        <h1 class="fw-bold text-theme-accent-1 mb-0"><?php echo e(env('APP_NAME')); ?></b></h1>
                                    </div>
                                    <form class="account__form form_data" action="<?php echo e(route('salesman.salesman-login-action')); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                                        <?php echo csrf_field(); ?>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="emailadd" placeholder="Enter Username" value="" name="username" required>
                                            <label for="emailadd">Username</label>
                                        </div>
                                        <div class="position-relative">
                                            <div class="form-floating mb-3">
                                                <input type="password" class="form-control" id="passwd" placeholder="Enter your password" name="password" required>
                                                <label for="passwd">Password</label>
                                            </div>
                                            <button class="btn btn-square btn-link text-theme-1 position-absolute end-0 top-0 mt-2 me-2 ">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-lg btn-theme w-100 mb-3">Login</button>
                                    </form>
                                    
                                    
                                    

                                </div>
                            </div>
                        </div>
                    </main>
                </div>

              

<script src="<?php echo e(url('public/')); ?>/toast/saber-toast.js"></script>
<script src="<?php echo e(url('public/')); ?>/toast/script.js"></script>


    
</body>

</html><?php /**PATH D:\xamp\htdocs\projects\irshad\shivvedaecomcrm\resources\views/salesman/login.blade.php ENDPATH**/ ?>