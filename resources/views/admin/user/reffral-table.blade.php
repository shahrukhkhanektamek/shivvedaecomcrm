<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>                    
                    <th>Member ID </th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Date Activate</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data_list as $key=> $value)
                <tr>                    
                    <td>{{sort_name.$value->affiliate_id}}</td>
                    <td>{{$value->name}}</td>
                    <td>{{$value->email}}</td>
                    <td>{{$value->phone}}</td>
                    <td>
                        @if($value->is_paid==1)
                        <span class="badge bg-success">Paid</span>
                        @endif
                        @if($value->is_paid==0)
                        <span class="badge bg-danger">UnPaid</span>
                        @endif
                    </td>
                    <td>{{date("D d F Y h:i A", strtotime($value->add_date_time))}}</td>
                    <td>
                        @if($value->is_paid==1)
                            {{date("D d F Y h:i A", strtotime($value->activate_date_time))}}                            
                        @endif
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
