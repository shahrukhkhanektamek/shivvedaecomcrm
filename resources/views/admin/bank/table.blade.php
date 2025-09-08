<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>                    
                    <th>User ID</th>
                    <th>Image </th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email </th>
                    <th>Pancard </th>
                    <th>Bank Holder Name </th>
                    <th>Bank Name </th>
                    <th>Account Number </th>
                    <th>IFSC </th>
                </tr>
            </thead>
            <tbody>
            @foreach($data_list as $key=> $value)
                <tr>
                    <td>{{sort_name.$value->user_id}}</td>                    
                    <td>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <img class="avatar-xs rounded-circle"
                                    onerror="this.src='{{asset('storage/app/public/upload/default.jpg')}}'"
                                    src="{{asset('storage/app/public/upload/')}}/{{$value->image}}" alt="banner image"/>
                            </div>
                        </div>
                    </td>                   
                    <td>{{$value->name}}</td>                    
                    <td>{{$value->phone}}</td>                    
                    <td>{{$value->email}}</td>                    
                    <td>{{$value->pan}}</td>                    
                    <td>{{$value->bank_holder_name}}</td>                    
                    <td>{{$value->bank_name}}</td>                    
                    <td>{{$value->account_number}}</td>                    
                    <td>{{$value->ifsc}}</td>                    
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
