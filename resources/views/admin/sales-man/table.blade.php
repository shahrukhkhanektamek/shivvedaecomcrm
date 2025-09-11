<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>                    
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data_list as $key=> $value)
                <tr @if($value->is_paid==0) class="bg-danger" @endif>
                    <td>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                {{$value->name}}<br>
                                <b>{{$value->user_id}}</b>
                            </div>
                        </div>
                    </td>
                    
                    <td>{{$value->email}}</td>
                    <td>{{$value->phone}}</td>
                    <td>{{date("D d F Y h:i A", strtotime($value->add_date_time))}}</td>
                    <td>
                        @if($value->status==1)
                        <span class="badge bg-success">Active</span>
                        @endif
                        @if($value->status==0)
                        <span class="badge bg-danger">Blocked</span>
                        @endif
                    </td>
                    <td>                        
                         <div class="d-flex gap-2">
                            <div class="edit">
                                <a href="{{$data['edit_btn_url'].'/'.Crypt::encryptString($value->id)}}" class="btn btn-sm btn-success edit-item-btn">Edit</a>
                            </div>
                            <div class="edit">
                                <a href="{{$data['change_password_btn_url'].'/'.Crypt::encryptString($value->id)}}" class="btn btn-sm btn-success edit-item-btn">Change Password</a>
                            </div>
                            <div class="remove">
                                <a href="{{$data['delete_btn_url'].'/'.Crypt::encryptString($value->id)}}" class="btn btn-sm btn-danger remove-item-btn">Delete</a>
                            </div>
                        </div>
                    </td>
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
