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
                    <form class="mt-2 needs-validation form_data" action="{{$data['submit_url']}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                        @csrf
                        <input type="hidden" name="id" value="{{Crypt::encryptString(@$row->id)}}">
                        <div class="row">
                            <div class="col-lg-6" style="margin:0 auto;">
                                <div class="card">
                                    <div class="card-body frm">
                                        <div class="row">
                                            
                                          
                                            
                                           


                                            <div class="col-lg-12">
                                                <label class="form-label" for="product-title-input">Name</label>
                                                <input type="text" class="form-control" placeholder="Enter Name" name="name" value="{{@explode(' ', $row->name)[0]}}" required>
                                            </div>

                                           


                                            <div class="col-lg-12">
                                                <label class="form-label" for="product-title-input">Email</label>
                                                <input type="text" class="form-control" placeholder="Enter Email" name="email" value="{{@$row->email}}" >
                                            </div>

                                       
                                            
                                            <div class="col-lg-12">
                                                <label class="form-label" for="product-title-input">Phone </label>
                                                <input type="number" class="form-control" placeholder="Enter Phone" name="phone" value="{{@$row->phone}}" >
                                            </div>


                                            @if(empty($row))
                                            <div class="col-lg-12">
                                                <label class="form-label" for="product-title-input">Password </label>
                                                <input type="text" class="form-control" placeholder="Enter Password" name="password" value="{{@$row->password}}" >
                                            </div>        
                                            @endif                                   
                                            
                                            <div class="col-lg-6 hide">
                                                <label for="formFile" class="form-label">Action</label>
                                                <select class="form-select mb-3" aria-label="Default select example" name="status">
                                                    <option value="1" selected >Active</option>
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


    <script>
        function check_sponser()
    {
        pack = $("#package_id").val();
        if(pack!=193130 && pack!=193131 && pack!=193132)
            $("#coupon-div").hide();
        else
            $("#coupon-div").show();

        $("#SignUp").attr('disabled',true);

        $(search_input).parent().find(".alert").remove();
        input_loader(search_input,1);

        var sponser_id = $("#sponser_id").val();
        var package_id = $("#package_id").val();
        var placement = $("#placement").val();
        var form = new FormData();
        form.append("sponser_id", sponser_id);
        form.append("package_id", package_id);
        form.append("placement", placement);
        var settings = {
          "url": "{{url('check-sponser')}}",
          "method": "POST",
          "processData": false,
          "mimeType": "multipart/form-data",
          "headers": {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
           },
          "contentType": false,
          "dataType":"json",
          "data": form
        };
        $.ajax(settings).always(function (response) {
            input_loader(search_input,0);
          
          response = admin_response_data_check(response);
          console.log(response);

          $("#package_id").html(response.package);
          $("#package_id").select2();

          if(response.status==200)
          {
            $("#SignUp").attr('disabled',false);
            print_input_search_success_error(search_input,response.message,1);
            $("#sponser_name").val(response.data.name);
            $("#parent_id").html('<strong>Parent Name: </strong>'+response.parent_id+' '+response.parent_name);
            if(response.parent_id!=0) $("#parent_id").show();
          }
          else
          {
            $("#parent_id").hide();
            if(sponser_id!='')
            {
                print_input_search_success_error(search_input,response.message,2);
            }
            $("#sponser_name").val('');
          }

          if(sponser_id=='') $("#SignUp").attr('disabled',false);

          search_input2 = $("#coupon");
          if($("#coupon").val()!='' && $("#coupon").val()) check_coupon();


        });
    }
    $(document).on("keyup", "#sponser_id",(function(e) {
        search_input = $(this);
        check_sponser();
    }));
    $(document).on("change", "#placement",(function(e) {
        search_input = $(this);
        check_sponser();
    }));
    </script>



</body>
</html>