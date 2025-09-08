
<!DOCTYPE html>
<html lang="en">
<!-- dir="rtl"-->
<head>
    <!-- Required meta tags  -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="_token" content="{{csrf_token()}}">
    <meta name="application-name" content="">
    <meta name="apple-mobile-web-app-title" content="">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <link rel="apple-touch-icon" href="{{url('/public/salesman/')}}/assets/img/favicon.png">

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>
    
    <link rel="icon" type="image/png" href="{{url('/public/salesman/')}}/assets/img/favicon.png">

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
    <script defer src="{{url('/public/salesman/')}}/assets/js/app.js"></script>
    <link href="{{url('/public/salesman/')}}/assets/css/app.css" rel="stylesheet">


    


    <link rel="stylesheet" href="{{url('public')}}/toast/saber-toast.css">
    <link rel="stylesheet" href="{{url('public')}}/toast/style.css">
    <link rel="stylesheet" href="{{url('public')}}/front_css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{url('public')}}/front_script.js"></script>

    <link rel="stylesheet" href="{{url('public')}}/upload-multiple/style.css">
    <script src="{{url('public')}}/upload-multiple/script.js"></script>

    <link rel="stylesheet" href="{{url('public/')}}/assetsadmin/select2/css/select2.min.css">

<style>
.add-cart-btn-group {
    width: 100%;
    display: block;
}
.add-cart-btn-group {
    width: 100%;
    display: flex;
}
.plus-btn {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.devide-btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.qty{
    border-radius: 0;
    text-align: center;
}


.select2-container {
    display: block;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
  background-color: #71519d;
  border: 1px solid #71519d;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
  color: white;
}
.select2-container .select2-selection--single
{
  height: calc(3.25rem + 2px);
}
.select2-container--default .select2-selection--single {
    padding: 5px 5px;
    padding-top: 6px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
  top: 100%;
}
.select2-container--default .select2-selection--single {
  border: 1px solid #ced4da;
  border-radius: 1.5rem;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
}



</style>


</head>

    <body class="main-bg main-bg-opac main-bg-blur roundedui adminuiux-header-standard adminuiux-sidebar-standard adminuiux-mobile-footer-fill-theme adminuiux-header-transparent theme-pista bg-r-gradient adminuiux-sidebar-fill-none scrollup" data-theme="theme-pista" data-sidebarfill="adminuiux-sidebar-fill-none" data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" tabindex="0" data-sidebarlayout="adminuiux-sidebar-standard"
        data-headerlayout="adminuiux-header-standard" data-headerfill="adminuiux-header-transparent" data-bggradient="bg-r-gradient" style="">
        <!-- Pageloader -->
<div class="pageloader">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center text-center h-100 pb-ios">
            <div class="col-12 mb-auto pt-4"></div>
            <div class="col-auto">
                <img src="{{url('/public/salesman/')}}/assets/img/logo.png" alt="" class="height-80 mb-3">
                <p class="display-3 text-theme-1 fw-bold mb-4">{{env('APP_NAmE')}}</p>
                <div class="loader5 mb-2 mx-auto"></div>
            </div>
            <div class="col-12 mt-auto pb-4">
                <p class="small text-secondary">Please wait we are preparing awesome things...</p>
            </div>
        </div>
    </div>
</div>
<!-- standard header -->
<header class="adminuiux-header">
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <!-- main sidebar toggle -->
            <button class="btn btn-link btn-square sidebar-toggler" type="button" onclick="initSidebar()">
                <i class="sidebar-svg" data-feather="menu"></i>
            </button>
            <!-- logo -->
            <a class="navbar-brand" href="ecommerce-dashboard.html">
                <img data-bs-img="light" src="{{url('/public/salesman/')}}/assets/img/logo.png" alt="" class="avatar avatar-100">
                <img data-bs-img="dark" src="{{url('/public/salesman/')}}/assets/img/logo.png" alt="" class="avatar avatar-100">
            </a>

            <!-- right icons button -->
            <div class="ms-auto">
                
                <!-- dark mode -->
                <button class="btn btn-link btn-square btnsunmoon btn-link-header" id="btn-layout-modes-dark-page">
                    <i class="sun mx-auto" data-feather="sun"></i>
                    <i class="moon mx-auto" data-feather="moon"></i>
                </button>
                
              
            </div>
        </div>
    </nav>

</header>


<div class="adminuiux-wrap">
    <!-- Standard sidebar -->
    <!-- Standard sidebar -->
    <div class="adminuiux-sidebar">
        <div class="adminuiux-sidebar-inner">
            <ul class="nav flex-column menu-active-line mt-3">
               
                <li class="nav-item">
                    <a href="dashboard.html" class="nav-link">
                        <i class="menu-icon bi bi-columns-gap"></i>
                        <span class="menu-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="customers.html" class="nav-link">
                        <i class="menu-icon bi bi-people"></i>
                        <span class="menu-name">Customers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="orders.html" class="nav-link">
                        <i class="menu-icon bi bi-bag-check"></i>
                        <span class="menu-name">Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{url('/salesman/product')}}" class="nav-link">
                        <i class="menu-icon bi bi-boxes"></i>
                        <span class="menu-name">Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{url('/salesman/change-password')}}" class="nav-link">
                        <i class="menu-icon" data-feather="eye"></i>
                        <span class="menu-name">Change Password</span>
                    </a>
                </li>              
            </ul>
            <div class=" mt-auto "></div>
            <div class="px-3 not-iconic">
               
                <div class="card adminuiux-card shadow-sm border-0">
                    <div class="card-body p-2">
                        <div class="row gx-2">
                            <div class="col-12 d-flex justify-content-between">
                                
                                <a class="btn btn-square btn-link logout">
                                    Logout
                                    <i class="menu-icon" data-feather="arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <main class="adminuiux-content has-sidebar" onclick="contentClick()">