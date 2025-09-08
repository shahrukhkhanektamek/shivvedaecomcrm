@include('user.headers.header')


  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1 class="text-black">{{$data['page_title']}}</h1>
      <ol class="breadcrumb">
        <li><a href="{{url('user/dashboard')}}">Home</a></li>
        @foreach($data['pagenation'] as $key => $value)
          <li class="sub-bread"><i class="fa fa-angle-right"></i> {{$value}}</li>
        @endforeach
      </ol>
    </div>
    
    <!-- Main content -->
    <div class="content">
      <div class="row">

        <div class="col-lg-12 mt-3">
          <div class="card card-outline">
            <div class="card-header bg-blue">
              <h5 class="text-white m-b-0">{{$data['title']}} @if(!empty($data['level'])) {{$data['level']}} @endif History</h5>
            </div>
            <div class="card-body" id="data-list">
                         
            </div>
          </div>
        </div>


      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->






<script>
   var data = '';
   var main_url = "{{$data['back_btn']}}/load_data";
   var type = '';
   function get_url_data()
   {       
       var filter_search_value = $(".search-input").val();
       var from_date = $("#from_date").val();
       var to_date = $("#to_date").val();
       data = `type=${type}&from_date=${from_date}&to_date=${to_date}&level={{$data['level']}}`;
   }
   get_url_data()
   url = main_url+'?'+data;
   load_table();
   $(document).on("change", "#from_date, #to_date",(function(e) {
      get_url_data();
      url =main_url+"?"+data;
      load_table();
   }));
   $(document).on("click", ".type-btn",(function(e) {
      type = $(this).data('type');
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
   $(document).on("click", ".transfer-amount",(function(e) {      
      event.preventDefault();
      loader("show");
        var form2 = new FormData();
        var settings = {
          "url": "{{route('user.earning.transfer')}}",
          "method": "POST",
          "timeout": 0,
          "processData": false,
          "headers": {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
           },
          "mimeType": "multipart/form-data",
          "contentType": false,
          "dataType": "json",
          "data": form2
        };
        $.ajax(settings).always(function (response) {
            loader("hide");
            response = admin_response_data_check(response);
        });
   }));


</script>






@include('user.headers.footer')