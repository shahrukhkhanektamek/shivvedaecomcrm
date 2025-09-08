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
       @include('admin.headers.header')        <!-- End Include Header -->
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
                                        <li class="breadcrumb-item active">{{$data['page_title']}}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    @include('admin.user.profile-card')
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="applicationList">
                                <div class="card-header  border-0">
                                    <div class="row">
                                        
                                        <div class="col-md-2">
                                            <select class="form-control status" id="statuschange">
                                               <option value="">Paid/Unpaid</option>
                                               <option value="1">Paid Users</option>
                                               <option value="2">Unpaid Users</option>
                                            </select>
                                         </div>

                                         <div class="col-md-3">
                                            <select class="form-control status select2" id="state">
                                                <option selected="selected" value="">Select State</option>
                                                @php($states = DB::table('states')->get())
                                                @foreach($states as $key=>$value)
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                                
                                            </select>
                                         </div>

                                         <div class="col-md-2">
                                            <select class="form-control limit" id="limit">
                                               <option value="12">12</option>
                                               <option value="24">24</option>
                                               <option value="36">36</option>
                                               <option value="100">100</option>
                                            </select>
                                         </div>
                                         
                                    </div>
                                </div>
                                <div class="card-body pt-0" id="data-list">
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
   var data = '';
   var main_url = "{{$data['back_btn']}}/load_team_reffral_data";

   function get_url_data()
   {
       var user_id = "{{Crypt::encryptString($row->id)}}";
       var state = $("#state").val();
       var status = $("#statuschange").val();
       var order_by = $("#order_by").val();
       var limit = $("#limit").val();
       data = `status=${status}&order_by=${order_by}&state=${state}&limit=${limit}&user_id=${user_id}`;
   }
   get_url_data();
   url = main_url+'?'+data;
   load_table();
   $(document).on("change", "#statuschange,#kycchange, .order_by, .limit, #state",(function(e) {
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


</script>


</body>

</html>