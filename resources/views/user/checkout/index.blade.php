@include('user.headers.header')
@php($cartDetail = \App\Models\MemberModel::cartDetail(@$row->id))
@php($setting = json_decode(DB::table('setting')->where('name','main')->first()->data))
@php($upi = @$setting->upi)
@php($user = Helpers::get_user_user())
  
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
          


          @if($user->is_paid)
            <div class="col-lg-12">
              <div class="card card-outline">
                <div class="card-body">


                  <div class="wallet" style="margin: 0 auto;">
                    <i class="icon-wallet"></i>
                    <h2 class="wallet-balance">{{Helpers::price_formate(@\App\Models\MemberModel::repurchase_wallet(Session::get('user')['id']))}}</h2>
                  </div>
                  
          
                </div>
              </div>
            </div>
          @endif
              

      <form class="form row form_data m-0 p-0" style="overflow: auto;" action="{{route('user.checkout.place_order')}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                    @csrf                

        <div class="col-lg-12 mt-3">
          <div class="card card-outline">
            <div class="card-header bg-blue">
              <h5 class="text-white m-b-0">Cart Products </h5>
            </div>
            <div class="card-body p-0 ">
              <div class="info-box">
                  <div class="table-responsive">

                    @foreach($cartDetail['cartProducts'] as $key=>$value)
                        <div class="col-12 mb-3 card">
                          <p class="mb-1 mt-2"><strong>Product Name:</strong> {{$value->name}}</p>
                          <p class="mb-1"><strong>Unit Price:</strong> {{Helpers::price_formate($value->sale_price)}}</p>
                          <p class="mb-1"><strong>Total Price:</strong> {{Helpers::price_formate($value->sale_price*$value->qty)}}</p>
                          <p class="mb-1">
                            <div class="add-cart-btn-group col-2 p-0">
                                <button type="button" class="plus-btn" data-id="{{$value->product_id}}" data-type="1">-</button>
                                <input type="number" value="{{$value->qty}}">
                                <button type="button" class="devide-btn" data-id="{{$value->product_id}}" data-type="2">+</button>
                            </div>
                          </p>
                        </div>
                    @endforeach


                    <table class="table">
                        <thead class="bg-success text-white">
                            
                        </thead>
                        <tbody>
                          <tr>
                            <th>Cart Total</th>
                            <th>{{Helpers::price_formate($cartDetail['cartTotal'])}}</th>
                          </tr>
                          <tr>
                            <th>Gst</th>
                            <!-- <th>{{Helpers::price_formate($cartDetail['gst'])}}</th> -->
                            <th>Included</th>
                          </tr>
                          <tr>
                            <th>Final Amount</th>
                            <th>{{Helpers::price_formate($cartDetail['cartFinalAmount'])}}</th>
                          </tr>
                          <tr id="walletUseAmount" style="display:none">
                            <th>Wallet Use</th>
                            <th>-<span></span></th>
                          </tr>
                          <tr id="payableAmount" style="display:none">
                            <th>Payable Amount</th>
                            <th><span></span></th>
                          </tr>

                        </tbody>
                      </table>

                      <div style="text-align: center;">
                      Total BV: {{$cartDetail['totalBv']}}

                      @if($user->is_paid)                                
                          <label class="btn btn-success" for="payment_mode"><input type="checkbox" value="1" id="payment_mode" name="payment_mode"  /> Use Repurchase Wallet</label>
                      @endif  
                    </div>

                    <!-- end table -->
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="col-lg-12 mt-3">
          <div class="card card-outline">
            <div class="card-header bg-blue">
              <h5 class="text-white m-b-0">Shipping Address </h5>
            </div>
            <div class="card-body ">
              <div class="info-box row d-flex m-0 p-0">
                  
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Name</label>
                        <div class="input-group">
                          <div class="input-group-addon"><i class="fa fa-user"></i></div>
                          <input class="form-control" placeholder="Name" type="text" name="name" value="{{@$orders->name}}" required>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Email</label>
                        <div class="input-group">
                          <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                          <input class="form-control" placeholder="Email" type="email" name="email" value="{{@$orders->email}}" >
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Phone</label>
                        <div class="input-group">
                          <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                          <input class="form-control" placeholder="Phone" type="number" name="phone" value="{{@$orders->phone}}" required>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>State</label>
                        <div class="input-group">
                          <div class="input-group-addon"><i class="fa fa-circle-o"></i></div>
                          <select class="form-select mb-3" aria-label="Default select example" name="state" required>
                              <option value=""  >Select</option>
                              @php($states = DB::table('states')->get())
                              @foreach($states as $key => $value)
                                  <option value="{{$value->id}}" @if(@$orders->state==$value->id) selected @endif >{{$value->name}}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>City</label>
                        <div class="input-group">
                          <div class="input-group-addon"><i class="fa fa-circle-o"></i></div>
                          <input class="form-control" placeholder="City" type="text" name="city" value="{{@$orders->city}}" required>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Pincode</label>
                        <div class="input-group">
                          <div class="input-group-addon"><i class="fa fa-circle-o"></i></div>
                          <input class="form-control" placeholder="Pincode" type="text" name="pincode" value="{{@$orders->pincode}}" required>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Address</label>
                        <div class="input-group">
                          <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                          <input class="form-control" placeholder="Address" type="text" name="address" value="{{@$orders->address}}" required>
                        </div>
                      </div>
                    </div>

                    


                    <div class="col-md-12">
                      <button type="button" class="btn btn-success waves-effect waves-light m-r-10" id="openPaymentModal">Payment Now</button>
                      <button type="submit" class="btn btn-success waves-effect waves-light m-r-10" id="checkoutNow" style="display:none;">Checkout Now</button>
                    </div>






                    <!-- Modal -->
                    <div id="checkoutPaymentModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" style="width: fit-content;display: block;margin: 0 0 0 auto;">&times;</button>
                          </div>
                          <div class="modal-body">

                            <img id="qrImage" src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode('upi://pay?pa='.$upi.'&am='.$cartDetail['cartFinalAmount'].'&cu=INR') }}&size=300x300" alt="UPI QR Code for Payment" style="margin: 0 auto;display: block;">

<?php
$amount = $cartDetail['cartFinalAmount'];
$upiUrl = "upi://pay?pa=" . urlencode($upi) . "&am=" . urlencode($amount) . "&cu=INR";
?>
                              <a href="<?= $upiUrl ?>" style="padding: 10px 20px;background: #4CAF50;color: #fff;text-decoration: none;border-radius: 5px;margin: 15px auto;display: block;width: fit-content;">
                              Pay Now
                            </a>

                            <p id="payableAmount2" style="text-align: center;margin: 10px 0 0 0;font-size: 20px;font-weight: bold;color: black;">{{Helpers::price_formate($cartDetail['cartFinalAmount'])}}</p>

                            <!-- <img src="{{url('/')}}/public/qr.png" style="width: 50%;margin: 0 auto;display: block;"> -->
                            <span class="upi-copy-text" onclick="copyToClipboard('{{$upi}}')">{{$upi}} <i class="fa fa-copy"></i></span>

                            <div class="col-md-12 mt-3">
                              <div class="form-group">
                                <label>Upload Payment Screenshot</label>
                                <div class="input-group">
                                  <label class="custom-file center-block block w-100">
                                    <input type="file" id="screenshot" class="custom-file-input upload-single-image" required name="image" data-target="image" accept="image/*">
                                    <span class="custom-file-control"></span>
                                  </label>
                                </div>                                 
                                  <img class="upload-img-view img-thumbnail mt-2 mb-2 image" id="viewer" src="" alt="banner image"/>
                              </div>
                            </div>


                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
                          </div>
                        </div>

                      </div>
                    </div>





               
              </div>
            </div>
          </div>
        </div>
      </form>





      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->







<script>
    const cartFinalAmount = {{$cartDetail['cartFinalAmount']}};
    const wallet = {{@\App\Models\MemberModel::repurchase_wallet(Session::get('user')['id'])}};
    $(document).on("click", "#payment_mode",(function(e) {      
      var payment_mode = $("#payment_mode:checked").val();
      if(payment_mode==1)
      {
        if(wallet<cartFinalAmount)
        {
          $("#screenshot").attr("required",true);
          $("#openPaymentModal").show();
          $("#checkoutNow").hide();
        }
        else
        {
          $("#screenshot").attr("required",false);          
          $("#openPaymentModal").hide();
          $("#checkoutNow").show();
        }
      }
      else
      {
        $("#openPaymentModal").show();
          // $("#checkoutNow").hide();
        $("#screenshot").attr("required",true);          
      }
    }));



   $(document).on("click", "#openPaymentModal",(function(e) {
    $("#checkoutPaymentModal").modal("show");
   }));


     var type = 0;
   var p_id = 0;
   var input = 0;
   var qty = 0;
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
            location.reload()
            response = admin_response_data_check(response);
        });
   }));
   $(document).on("click", "#payment_mode",(function(e) {
      
      payment_mode = $("#payment_mode:checked").val();
        
      loader("show");
        var form = new FormData();
        form.append("payment_mode",payment_mode);
        var settings = {
          "url": "{{route('user.checkout.use_wallet')}}",
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
              $("#walletUseAmount span").html(data.wallet_amount);
              $("#payableAmount span").html(data.payable_amount);              
              $("#walletUseAmount, #payableAmount").show();
              $("#payableAmount2").html(data.payable_amount);
              $("#qrImage").attr("src", response.image);
            }
            else
            {
              $("#walletUseAmount, #payableAmount").hide();
            }

            
            

        });
   }));
</script>


@include('user.headers.footer')