<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
    <meta charset="utf-8" />
    <title>{{$data['page_title']}} | {{env("APP_NAME")}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Start Include Css -->
    @include('admin.headers.maincss')
    <!-- End Include Css -->
</head>
<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- Start Include Header -->
        @include('admin.headers.header')
        <!-- End Include Header -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">{{$data['page_title']}}</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                                        <li class="breadcrumb-item active">{{$data['page_title']}}
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <form class="row g-3 form_data" action="{{$data['submit_url']}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>

                        @csrf
                        <input type="hidden" name="id" value="{{Crypt::encryptString(@$row->id)}}">
                        
                        <!--end col-->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    
                                </div><!-- end card header -->

                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row g-3">


                                            <div class="col-lg-12">
                                                <label class="form-label" for="product-title-input">Excel</label>

                                                <div class="col-lg-12">
                                                    <input class="form-control" type="file" name="file" >
                                                </div>
                                            </div>
                                            
                                           

                                            <div class="col-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success btn-label"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </form>
                </div>
            </div>
            <!-- End Page-content -->
            <!-- Start Include Footer -->
            @include('admin.headers.footer')
            <!-- End Include Footer -->
        </div>
    </div>
    <!-- END layout-wrapper -->
    <!-- Start Include Script -->
    @include('admin.headers.mainjs')
    <!-- End Include Script -->
</body>
</html>