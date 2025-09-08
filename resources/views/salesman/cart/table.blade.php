@foreach($data_list as $key=> $value)
    

    <li class="list-group-item px-2">
        <div class="row gx-3 align-items-center">
            <div class="col-auto">
                <figure class="avatar avatar-50 coverimg rounded" style="background-image: url({{Helpers::image_check(@$value->image)}});">
                    <img src="{{Helpers::image_check(@$value->image)}}" alt="" style="display: none;">
                </figure>
            </div>
            <div class="col-9 col-md-5">
                <p class="mb-0 text-truncated">{{$value->product_name}}</p>
                <!-- <p class="small text-secondary">ID: RT15246630</p> -->
            </div>
            

            <div class="col col-md-auto ms-md-auto mt-3 mt-md-0">
                <div class="row gx-2 increamenter">
                    <div class="col-auto"><button type="button" class="btn btn-sm btn-square btn-theme rounded-circle plus-btn" data-id="{{$value->product_id}}" data-type="1">-</button></div>
                    <input type="number" class="form-control form-control-sm text-center width-50 qty" value="{{$value->qty}}">
                    <div class="col-auto"><button type="button" class="btn btn-sm btn-square btn-theme rounded-circle devide-btn" data-id="{{$value->product_id}}" data-type="2">+</button></div>
                </div>
            </div>



            <div class="col-auto text-end mt-3 mt-md-0 d-none">
                <h6 class="mb-0">$ 65.00</h6>
                <p class="small text-secondary">1 Item</p>
            </div>
        </div>
    </li>



@endforeach
            
