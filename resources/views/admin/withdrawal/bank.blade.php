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
                                <h4 class="mb-sm-0">Add Premium</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Add Premium
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <form class="needs-validation form_data" action="{{$data['submit_url']}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                        @csrf
                        <input type="hidden" name="id" value="{{Crypt::encryptString(@$row->id)}}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body frm">
                                        <div class="row">
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Email</label>
                                                <input type="text" class="form-control" placeholder="Enter Package Name" name="email" value="{{@$row->email}}" required>
                                            </div>

                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Name</label>
                                                <input type="text" class="form-control" placeholder="Enter Package Name" name="name" value="{{@$row->name}}" required>
                                            </div>                                            
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Package</label>
                                                <input type="text" class="form-control" placeholder="Enter Package Name" name="name" value="{{@$row->name}}" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Sponser ID</label>
                                                <input type="text" class="form-control" placeholder="Enter Package Name" name="sponser_id" value="{{@$row->sponser_id}}" required readonly>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Sponser Name</label>
                                                <input type="text" class="form-control" placeholder="Enter Package Name" name="sponser_name" value="{{@$row->sponser_name}}" required readonly>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Address </label>
                                                <input type="text" class="form-control" placeholder="Enter Package Name" name="address" value="{{@$row->address}}" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">State </label>
                                                <input type="text" class="form-control" placeholder="Enter Package Name" name="city" value="{{@$row->city}}" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">City </label>
                                                <input type="text" class="form-control" placeholder="Enter Package Name" name="state" value="{{@$row->state}}" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Phone </label>
                                                <input type="text" class="form-control" placeholder="Enter Package Name" name="phone" value="{{@$row->phone}}" required>
                                            </div>
                                            
                                           
                                            <div class="col-lg-4">
                                                <label for="formFile" class="form-label">Picture</label>
                                                <label>
                                                    <input class="form-control upload-single-image" type="file" name="inner_image" data-target="offer_image2">
                                                    <img class="upload-img-view img-thumbnail mt-2 mb-2 offer_image2" id="viewer"
                                                        onerror="this.src='{{asset('storage/app/public/upload/default.jpg')}}'"
                                                        src="{{asset('storage/app/public/upload/')}}/{{@$row->offer_image2}}" alt="banner image"/>
                                                </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="formFile" class="form-label">Action</label>
                                                <select class="form-select mb-3" aria-label="Default select example" name="status">
                                                    <option value="1" @if(@$row->status==1)selected @endif >Active</option>
                                                    <option value="0" @if(@$row->status==0)selected @endif >Inactive</option>
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