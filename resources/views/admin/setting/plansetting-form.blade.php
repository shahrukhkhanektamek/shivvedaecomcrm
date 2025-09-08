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
                    <form class="needs-validation form_data" action="{{$data['submit_url']}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                        @csrf
                        <input type="hidden" name="id" value="{{Crypt::encryptString(@$row->id)}}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body frm">
                                        <div class="row">
                                            
                                            <div class="col-lg-2">
                                                <label for="formFile" class="form-label">Plan Type</label>
                                                <select class="form-select mb-3" aria-label="Default select example" name="type">
                                                    <option value="1" @if(!empty(@$row) && @$row->type==1)selected @endif >Id Green</option>
                                                    <option value="2" @if(!empty(@$row) && @$row->type==2)selected @endif >BV Plan</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Direct Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Direct Income" name="income1" value="{{@$row->income1}}" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Pair Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Pair Income" name="income2" value="{{@$row->income2}}" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Downline Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Downline Income" name="income3" value="{{@$row->income3}}" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Upline Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Upline Income" name="income4" value="{{@$row->income4}}" required>
                                            </div>

                                            <div class="col-lg-2 hide">
                                                <label class="form-label" for="product-title-input">Rank Bonus Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Rank Bonus Income" name="income5" value="{{@$row->income5}}" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Id Green BV</label>
                                                <input type="number" class="form-control" placeholder="Enter ID BV Green" name="id_bv" value="{{@$row->id_bv}}" required>
                                            </div>

                                            <div class="col-lg-2">
                                                <label class="form-label" for="product-title-input">Per Day Pair Income</label>
                                                <input type="number" class="form-control" placeholder="Enter Per Day Pair Income" name="per_day_pair_income" value="{{@$row->per_day_pair_income}}" required>
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