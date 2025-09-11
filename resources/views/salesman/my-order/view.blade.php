@if(empty($is_header))
@include("salesman/include/header")
@endif

@php($orderProducts = DB::table("order_products")->where("order_id",$order->order_id)->get())

@php($gst = 12)
@php($gstAmount = $order->gst)
@php($subTotal = $order->amount)


<!-- content -->
<div class="container mt-3 mt-lg-4 mt-xl-5" id="main-content">
    <div class="row gx-1 align-items-center">
        <div class="col col-sm mb-3 mb-md-4">
            <p class="small text-secondary mb-2">{{date("d F, Y h:i A", strtotime($order->add_date_time))}}</p>
            <p class=""><span class="badge badge-light text-bg-theme-1 theme-blue">New Order</span></p>
        </div>
    </div>
    <!-- customer -->
    <div class="row gx-3 gx-lg-4">
        <div class="col-12 col-lg-8 col-xxl-9">
            <div class="row gx-3 gx-lg-4">
                <div class="col-12 col-xl-8">
                    <!-- customer -->
                    <div class="card bg-none mb-3 mb-lg-4">
                        <div class="card-body">
                            <div class="row gx-3 align-items-center flex-nowrap mb-3">
                                
                                <div class="col maxwidth-200">
                                    <h5 class="mb-0 text-truncated">{{$order->name}}</h5>
                                </div>
                            </div>
                            <div class="row gx-3 gx-lg-4">
                                <div class="col-12 col-md-6">
                                    <p class="text-secondary mb-2"><i class="bi bi-envelope"></i> {{$order->email}}</p>
                                    <p class="text-secondary mb-2"><i class="bi bi-phone"></i> {{$order->phone}}</p>
                                    <p class="text-secondary mb-2"><i class="bi bi-map"></i> @php($states = DB::table('states')->where('id',$order->state)->first()){{@$states->name}}</p>
                                    <p class="text-secondary mb-2"><i class="bi bi-map"></i> {{$order->city}}</p>
                                    <!-- <p class="text-secondary mb-2"><i class="bi bi-map"></i> {{$order->address}}</p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- order items-->
            <div class="card bg-none mb-3 mb-lg-4">
                <div class="card-body">
                    <h6 class="mb-3">Order ({{count($orderProducts)}} Items)</h6>
                    <ul class="list-group adminuiux-list-group mb-0">
                        
                        @foreach($orderProducts as $key=>$value)
                        <li class="list-group-item px-2">
                            <div class="row gx-3 align-items-center">
                                <div class="col-auto">
                                    <figure class="avatar avatar-50 coverimg rounded">
                                        <img src="{{Helpers::image_check(@$value->image)}}" alt="">
                                    </figure>
                                </div>
                                <div class="col-9 col-md-5">
                                    <p class="mb-0 text-truncated">{{$value->name}}</p>
                                    <!-- <p class="small text-secondary">ID: RT15246630</p> -->
                                </div>
                                
                                <div class="col-auto text-end mt-3 mt-md-0 d-none">
                                    <h6 class="mb-0">$ 65.00</h6>
                                    <p class="small text-secondary">1 Item</p>
                                </div>
                            </div>
                        </li>
                        @endforeach
                        
                    </ul>
                </div>
            </div>

           

        </div>
        <div class="col-12 col-lg-4 col-xxl-3">

            <!-- payment -->
            <div class="card bg-none mb-3 mb-lg-4 d-none">
                <div class="card-body">
                    <h4 class="mb-1">$261.00</h4>
                    <p><span class="badge badge-light text-bg-success">Paid</span></p>

                    <div class="row gx-3 mb-2">
                        <div class="col">Sub Total</div>
                        <div class="col-auto">$ 270.00</div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col">Discount</div>
                        <div class="col-auto">$ 10.00</div>
                    </div>
                    <div class="row gx-3 mb-2">
                        <div class="col">Shipping Charge</div>
                        <div class="col-auto">FREE</div>
                    </div>
                    <div class="row gx-3 mb-3 mb-lg-4">
                        <div class="col">Gift Packaging</div>
                        <div class="col-auto">00.00</div>
                    </div>

                    <h6 class="mb-3">Payment details</h6>
                    <p class="text-secondary mb-2">Net Banking:</p>
                    <p class="text-secondary mb-2">AmericaSpice Bank</p>
                    <p class="text-secondary mb-2">A/C No.: 200525XXXXXX5524</p>
                    <p class="text-secondary text-truncated mb-2">Transaction.: FGDFG44G56G4D1G65G4DSG1S6G4G1SGS6G1S</p>
                    <p class=""><span class="badge badge-sm badge-light text-bg-theme-1 theme-blue">Received</span></p>

                    <button class="btn btn-theme theme-green"><i class="bi bi-whatsapp"></i> Invoice</button>
                    <button class="btn btn-light mx-1"><i class="bi bi-envelope"></i> Invoice</button>
                </div>
            </div>

            
        </div>
    </div>

</div>






@if(empty($is_header))
@include("salesman/include/footer")
@endif