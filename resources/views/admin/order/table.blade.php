<div>

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">

                <tr>                    

                    <th>Order Id</th>
                    <th>Branch</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email </th>
                    <th>Screenshot </th>
                    <th>Shipping Detail </th>
                    <th>Status </th>
                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

            @foreach($data_list as $key=> $value)
            @php($count = DB::table('orders')->where('user_id', $value->user_id)->count())
                <tr>

                    <td>#{{$value->order_id}}</td>
                    <td>{{$value->branch_name}}</td>
                    <td>{{$value->name}}</td>

                    <td>{{$value->phone}}</td>                    

                    <td>{{$value->email}}</td>
                    <td><img src="{{Helpers::image_check($value->screenshot,'default.jpg')}}" class="img-responsive big-image" alt="User" style="width: 100px;height: 100px;cursor: pointer;"></td> 

                    <td>
                        <strong>Name: </strong>{{$value->name}}<br>
                        <strong>Email: </strong>{{$value->email}}<br>
                        <strong>Phone: </strong>{{$value->phone}}<br>
                        <strong>Address: </strong>{{$value->address}}<br>
                        <strong>State: </strong>{{@DB::table('states')->where('id', $value->state)->first()->name}}<br>
                        <strong>City: </strong>{{$value->city}}<br>
                        <strong>Pincode: </strong>{{$value->pincode}}<br>
                    </td>

                    <td>
                        @if($value->status==0)

                        <span class="badge bg-primary">New</span>

                        @elseif($value->status==1)

                        <span class="badge bg-info">Proccess</span>

                        @elseif($value->status==2)

                        <span class="badge bg-dark">Shipped</span>

                        @elseif($value->status==3)

                        <span class="badge bg-success">Delivered</span>

                        @elseif($value->status==4)

                        <span class="badge bg-danger">Cancel</span>

                        @endif

                        @if($count==1) 
                        <span class="badge bg-primary">First Order</span>
                        @endif

                    </td>                    

                    <td><a href="{{$data['back_btn'].'/view/'.Crypt::encryptString($value->id)}}" class="btn btn-sm btn-success mb-1">View</a><br></td>

                </tr>

            @endforeach

            </tbody>

        </table>

        <!-- end table -->

    </div>

</div>





<div class="card pagination" >

    {{$data_list->links()}}

</div>

