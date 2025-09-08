<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
    <meta charset="utf-8" />
    <title>Payout List | {{env("APP_NAME")}}</title>
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
                                <h4 class="mb-sm-0">Payout List </h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Payout List </li>
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
                                                <h5 class="card-title mb-0">Payout List</h5>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <div class="d-flex flex-wrap align-items-start gap-2">
                                                <button type="button" class="btn btn-danger payout-modal-open">Payout All</button>
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
                                            <div class="col-xl-3">
                                                <select class="form-control kyc" id="new-sponser" name="sponser_id">
                                                    <option value="">Select User</option>
                                                </select>
                                            </div>
                                            <!--end col-->
                                            <div class="col-xl-8">
                                                <div class="row g-3">
                                                    <div class="col-sm-3">
                                                        <div class="">
                                                            <input type="date" class="form-control" id="from-date" value="{{date('Y-m-d')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="">
                                                            <input type="date" class="form-control" id="to-date" value="{{date('Y-m-d')}}">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-sm-3">
                                                        <div>
                                                            <select class="form-control" id="statuschange">
                                                                <!-- <option value="0">Income</option> -->
                                                                <option value="2" selected>Unpaid</option>
                                                                <option value="1">Paid</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--end col-->


                                                    <div class="col-sm-3">
                                                        <div>
                                                            <select class="form-control" id="search-type">
                                                                <option value="1">Date Wise</option>
                                                                <option value="2">Balance Wise</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div>
                                                            <select class="form-control" id="kyc">
                                                                <option value="1">Kyc Complete</option>
                                                                <option value="2">Kyc Not Complete</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="">
                                                            <input type="number" class="form-control" id="amount" value="200">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div>
                                                            <button type="button" class="btn btn-primary w-100" id="filter-btn"><i class="ri-equalizer-fill me-2 align-bottom"></i>Filters
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end row-->
                                    </form>
                                </div>
                                <div class="card-body" id="data-list">
                                    
                                </div>
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

    $(document).on("click", ".payout-modal-open",(function(e) {
      $("#PayoutPinModal").modal('show');
   }));
    $(document).on("click", "#payout-confirm",(function(e) {
        var payout_pin = $("#payout-pin").val();

        get_url_data();

        loader('show');
        var form = new FormData();
        form.append("pin",payout_pin);
        var settings = {
          "url": "{{$data['back_btn']}}/payout_submit"+'?'+data,
          "method": "POST",
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
            loader('hide');
            response = admin_response_data_check(response);
            if(response.status==200)
            {
                $("#PayoutPinModal").modal('hide');
                success_message(response.message)
            }
            else
            {
                error_message(response.message)                
            }
        });

   }));

   var data = '';
   var main_url = "{{$data['back_btn']}}/load_data";

   function get_url_data()
   {
       var search_type = $("#search-type").val();
       var kyc = $("#kyc").val();
       var amount = $("#amount").val();
       var status = $("#statuschange").val();
       var order_by = $("#order_by").val();
       var limit = $("#limit").val();
       var user_id = $("#new-sponser").val();
       var from_date = $("#from-date").val();
       var to_date = $("#to-date").val();
       var filter_search_value = $(".search-input").val();
       data = `kyc=${kyc}&search_type=${search_type}&amount=${amount}&user_id=${user_id}&from_date=${from_date}&to_date=${to_date}&status=${status}&order_by=${order_by}&limit=${limit}&filter_search_value=${filter_search_value}`;
   }
    get_url_data();
    url = main_url+'?'+data;
    load_table();
   
   $(document).on("click", "#filter-btn",(function(e) {
      get_url_data();
      url =main_url+"?"+data;
      load_table();
   }));
   
   $(document).on("change", "#statuschange, .order_by, .limit, #new-sponser, #from-date, #to-date, #search-type",(function(e) {
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
          "url": "{{route('payout-history.excel_export')}}?"+data,
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