<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>                    
                    <th>Image </th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data_list as $key=> $value)
                <tr>
                    <td>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <img class="avatar-xs rounded-circle"
                                    onerror="this.src='{{asset('storage/app/public/upload/default.jpg')}}'"
                                    src="{{asset('storage/app/public/upload/')}}/{{$value->image}}" alt="banner image"/>
                            </div>
                        </div>
                    </td>                   
                    <td>
                        {{$value->name}}
                        <br>

                        @if($value->kyc_step==1)
                        <span class="badge bg-success">KYC Complete</span>
                        @endif

                        @if($value->kyc_step==2)
                        <span class="badge bg-info">KYC Under Review</span>
                        @endif

                        @if($value->kyc_step==3)
                        <span class="badge bg-warning">KYC Rejected</span>
                        @endif

                        @if($value->kyc_step==0)
                        <span class="badge bg-info">KYC Not Update</span>
                        @endif

                    </td>                    
                    <td>{{$value->phone}}</td>                    
                    <td>{{$value->email}}</td>                    
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
