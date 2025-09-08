<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
    <meta charset="utf-8" />
    <title>Income Report | {{env("APP_NAME")}}</title>
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
                                <h4 class="mb-sm-0">Income Report </h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Income Report </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0">Income Report</h5>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <div class="d-flex flex-wrap align-items-start gap-2">
                                                <!-- <button type="button" class="btn btn-info">Copy</button> -->
                                                <button type="button" class="btn btn-success" id="excel-export">Excel</button>
                                                <button type="button" class="btn btn-danger">PDF</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body border-bottom-dashed border-bottom">
                                    <form>
                                        <div class="row g-3">

                                            <div class="col-md-1">
                                                <select class="form-control limit" id="limit">
                                                   <option value="12">12</option>
                                                   <option value="24">24</option>
                                                   <option value="36">36</option>
                                                   <option value="100">100</option>
                                                </select>
                                             </div> 
                                             <div class="col-md-2">
                                                <select class="form-control type" id="type">
                                                   <option value="">All</option>
                                                   <option value="1">Direct Income</option>
                                                   <option value="2">Pair Income</option>
                                                   <option value="3">Downline Income</option>
                                                   <option value="4">Upline Income</option>
                                                   <option value="5">Rank Bonus Income</option>
                                                   <option value="6">Repurchase Income</option>
                                                </select>
                                             </div>     




                                            <div class="col-xl-2">
                                                <select class="form-control kyc" id="new-sponser" name="sponser_id">
                                                    <option value="">Select User</option>
                                                    <?php if(!empty($user_id)) { ?>
                                                        <option value="<?=$user_id ?>" selected><?=$user_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <!--end col-->
                                            <div class="col-xl-7">
                                                <div class="row g-3">
                                                    <div class="col-sm-4">
                                                        <div class="">
                                                            <input type="date" class="form-control" id="from-date">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="">
                                                            <input type="date" class="form-control" id="to-date">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                  
                                                    <!--end col-->
                                                    <div class="col-sm-4">
                                                        <div>
                                                            <button type="button" class="btn btn-primary w-100 search"> <i
                                                                    class="ri-equalizer-fill me-2 align-bottom"></i>Filters</button>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end row-->
                                    </form>
                                </div>
                                <div class="card-body" id="data-list"></div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <!-- Start Include Footer -->
            @include('admin.headers.footer')
            <!-- End Include Footer -->
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Start Include Script -->
    @include('admin.headers.mainjs')
    <!-- End Include Script -->



<script>
   var data = '';
   var main_url = "{{$data['back_btn']}}/load_data";

   function get_url_data()
   {
       var type = $("#type").val();
       var status = $("#statuschange").val();
       var order_by = $("#order_by").val();
       var limit = $("#limit").val();
       var user_id = $("#new-sponser").val();
       var from_date = $("#from-date").val();
       var to_date = $("#to-date").val();
       var filter_search_value = $(".search-input").val();
       data = `type=${type}&user_id=${user_id}&from_date=${from_date}&to_date=${to_date}&status=${status}&order_by=${order_by}&limit=${limit}&filter_search_value=${filter_search_value}`;
   }
   get_url_data()
   url = main_url+'?'+data;
   load_table();
   $(document).on("change", "#type, #statuschange, .order_by, .limit, #new-sponser, #from-date, #to-date",(function(e) {
      get_url_data();
      url =main_url+"?"+data;
      load_table();
   }));
   $(document).on("click", ".search",(function(e) {
      get_url_data();
      url =main_url+"?"+data;
      load_table();
   }));
   $(document).on("click", ".pagination a",(function(e) {      
      event.preventDefault();
      get_url_data()
      url = $(this).attr("href")+'&'+data;
      load_table();
   }));

   function load_table()
   {
        data_loader("#data-list",1);
        var form = new FormData();
        var settings = {
          "url": url,
          "method": "GET",
          "timeout": 0,
          "processData": false,
          "headers": {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
           },
          "mimeType": "multipart/form-data",
          "contentType": false,
          "dataType": "json",
          "data": form
        };
        $.ajax(settings).always(function (response) {
            data_loader("#data-list",0);
            response = admin_response_data_check(response);
            $("#data-list").html(response.data.list);

        });
   }



   $(document).on("click", "#excel-export",(function(e) {
      excel_export();
   }));
   function excel_export()
   {
        get_url_data();
        loader("show");
        var form = new FormData();
        var settings = {
          "url": "{{$data['excel_export']}}?"+data,
          "method": "GET",
          "timeout": 0,
          "processData": false,
          "headers": {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
           },
          "mimeType": "multipart/form-data",
          "contentType": false,
          "dataType": "json",
          "data": form
        };
        $.ajax(settings).always(function (response) {
            loader("hide");
            response = admin_response_data_check(response);
            if(response.status==200)
            {
                window.location.href=response.url;
            }
            // $("#data-list").html(response.data.list);

        });
   }


</script>
    <script>
        $('#new-sponser').select2({
          ajax: {
            url: "{{route('search-sponser')}}",
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
</html>