@include('user.headers.header')
@php($user = Helpers::get_user_user())
@php($incomePlan = DB::table('income_plan')->first())

  
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
        <div class="col-lg-12">
          <div class="card card-outline">
            <!-- <div class="card-header bg-blue">
              <h5 class="text-white m-b-0">Basic Example</h5>
            </div> -->
            <div class="card-body">

                @if($incomePlan->type==1)
                 <div class="wallet mainwallet mb-4" style="margin: 0 auto;">
                          <div class="mainwalleticon">
                           <i class="icon-wallet"></i>
                          </div>
                          <div class="mainwalletcontent">
                             <h2 class="wallet-balance">{{Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome(Session::get('user')['id'])->wallet)}}</h2> 
                          </div>
                 </div>
              @endif

              
              <form class="form row form_data" action="{{$data['submit_url']}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                @csrf


              @if($incomePlan->type==1)

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Member ID.</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="Member ID." type="number" name="member_id" id="sponser_id" required>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Member Name</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-user"></i></div>
                      <input class="form-control" placeholder="Member Name" type="text" disabled id="member_name">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Sponser Name</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-user"></i></div>
                      <input class="form-control" placeholder="Sponser Name" type="text" disabled id="sponser_name">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Sponser ID</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-user"></i></div>
                      <input class="form-control" placeholder="Sponser ID" type="text" disabled id="sponser_id2">
                    </div>
                  </div>
                </div>

                

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Select Package</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-globe"></i></div>
                      <select class="form-select mb-3" aria-label="Default select example" name="package" required>
                          <option value=""  >Select</option>
                          @php($packages = DB::table('package')->get())
                          @foreach($packages as $key => $value)
                              <option value="{{$value->id}}" >{{$value->name}} ({{Helpers::price_formate($value->sale_price)}})</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>

              @endif

                
                
                
                
              </form>


              @if($incomePlan->type==2)

                @if($user->total_bv<$incomePlan->id_bv && $user->is_paid==0)
                  <div class="alert alert-info">
                    <p class="m-0">Purchase any products and complete your {{$incomePlan->id_bv}} BV You have {{$user->total_bv}} bv now and activate your Id.</p>
                  </div>
                @endif

                <div class=" row" id="data-list"></div>
                <div class="col-lg-12 mt-3">
                  <div class="card card-outline">
                    <div class="card-body ">
                        <div class="row">
                          <div class="col-md-4" style="display: flex;align-items: center;">
                            <span>Total BV: <p id="totalBv" style="margin: 0;font-size: 19px;font-weight: 700;"></p></span>
                          </div>
                          <div class="col-md-8">
                            <a href="checkout" class="btn btn-primary checkout" style="display:none;">Checkout</a>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              @endif




             
            </div>
          </div>
        </div>
      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->






  <script>
    function check_sponser()
    {
        $(search_input).parent().find(".alert").remove();
        input_loader(search_input,1);

        var sponser_id = $("#sponser_id").val();
        var form = new FormData();
        form.append("sponser_id", sponser_id);
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

          if(response.status==200)
          {            
            print_input_search_success_error(search_input,response.message,1);
            $("#member_name").val(response.data.name);
            $("#sponser_name").val(response.data.sponser_name);
            $("#sponser_id2").val(response.data.sponser_id);
          }
          else
          {
            if(sponser_id!='')
            {
                print_input_search_success_error(search_input,response.message,2);
            }
            $("#member_name").val('');
            $("#sponser_name").val('');
            $("#sponser_id2").val('');
          }   


        });
    }
    $(document).on("keyup", "#sponser_id",(function(e) {
        search_input = $(this);
        check_sponser();
    }));
  </script>


@if($incomePlan->type==2)
<script>
   var data = '';
   var main_url = "{{route('user.product.list')}}/load_data";

   function get_url_data()
   {
       var status = $("#statuschange").val();
       var order_by = $("#order_by").val();
       var limit = $("#limit").val();
       var filter_search_value = $(".search-input").val();
       data = `status=${status}&order_by=${order_by}&limit=${limit}&filter_search_value=${filter_search_value}`;
   }
   url = main_url+'?'+data;
   load_table();
   $(document).on("change", "#statuschange, .order_by, .limit",(function(e) {
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
            if(response.status==200)
            {
              var data = response.data;
              cartDetail = data.cartDetail; 
              var cartCount = cartDetail.cartCount;
              if(cartCount>0) $(".checkout").show();
              else $(".checkout").hide();

              $("#totalBv").html(data.cartDetail.totalBv);
            }

        });
   }

   var type = 0;
   var p_id = 0;
   var input = 0;
   var qty = 0;
   var cartDetail = '';
   var setBv = '{{$incomePlan->id_bv}}';
   $(document).on("click", ".plus-btn, .devide-btn",(function(e) {
      type = $(this).data("type");
      p_id = $(this).data("id");
      input = $(this).parent().find('input');

      if(type=='2') qty = parseInt($(input).val())+1;
      else qty = parseInt($(input).val())-1;
      if(qty<1) qty = 0;


      $(input).val(qty);

      event.preventDefault();
      loader("show");
        var form = new FormData();
        form.append("id",p_id);
        form.append("qty",qty);
        var settings = {
          "url": "{{route('user.cart.add')}}",
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
            loader("hide");
            response = admin_response_data_check(response);
            if(response.status==200)
            {
              var data = response.data;
              cartDetail = data;
              if(data.cartCount>0) $(".checkout").show();
              else $(".checkout").hide();

              $("#totalBv").html(data.totalBv);

            }
        });
   }));

   $(document).on("click", ".checkout",(function(e) {      
      
      if(cartDetail.totalBv<setBv)
      {
        error_message(`Complete ${setBv} your BV first!`)
        return false;
      }
      
   }));

</script>

@endif


@include('user.headers.footer')