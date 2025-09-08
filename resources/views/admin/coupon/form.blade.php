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
                                <h4 class="mb-sm-0">Add Coupon</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Add Coupon
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
                                                <div class="mtlicat">
                                                    <label for="formFile" class="form-label">Select Package</label>
                                                    <select class="form-select mb-3" aria-label="Default select example" name="package_id" required>
                                                        <option value=""  >Select</option>
                                                        @foreach(DB::table('package')->get() as $key => $value)
                                                            <option value="{{$value->id}}" @if(@$row->package_id==$value->id)selected @endif >{{$value->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Code</label>
                                                <input type="text" class="form-control" placeholder="Enter Code" name="code" value="{{@$row->code}}" required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Limit</label>
                                                <input type="number" class="form-control" placeholder="Enter Limit" name="limit_use" value="{{@$row->limit_use}}" required>
                                            </div>

                                            <div class="col-lg-4">
                                                <label for="formFile" class="form-label">Type</label>
                                                <select class="form-select mb-3" aria-label="Default select example" name="type">
                                                    <option value="1" @if(@$row->type==1)selected @endif >Flat</option>
                                                    <option value="2" @if(!empty(@$row) && @$row->type==0)selected @endif >Percent</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-4">
                                                <label class="form-label" for="product-title-input">Amount</label>
                                                <input type="number" class="form-control" placeholder="Enter Amount" name="amount" value="{{@$row->amount}}" required>
                                            </div>
                                            
                                           
                                            <div class="col-lg-4">
                                                <label for="formFile" class="form-label">Action</label>
                                                <select class="form-select mb-3" aria-label="Default select example" name="status">
                                                    <option value="1" @if(@$row->status==1)selected @endif >Active</option>
                                                    <option value="0" @if(!empty(@$row) && @$row->status==0)selected @endif >Inactive</option>
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