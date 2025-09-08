<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>                    
                    <th>Member Detail</th>
                    <th>UPI</th>
                    <th>Amount</th>
                    <th>charge</th>
                    <th>Withdrawal Amount</th>
                    <th>Date Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data_list as $key=> $value)
                <tr>
                    <td>
                        <b>Name: </b>{{$value->user_name}}<br>
                        <b>Mobile: </b>{{$value->user_phone}}<br>
                        <b>ID: </b>{{$value->uuser_id}}<br>
                    </td>                   
                    <td>{{$value->upi}}</td>              
                    <td>{{Helpers::price_formate($value->amount)}}</td>              
                    <td>{{Helpers::price_formate($value->charge)}}</td>              
                    <td>{{Helpers::price_formate($value->withdrawal_amount)}}</td>              
                    <td>{{date("d M, Y h:i A",strtotime($value->add_date_time))}}</td>                   
                    <td>
                        @if($value->status==1)
                        <span class="badge bg-success">Approved</span>
                        @endif

                        @if($value->status==0)
                        <span class="badge bg-info">New</span>
                        @endif

                        @if($value->status==2)
                        <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>                    
                    <td>
                        @if($value->status==0)
                        <a href="{{$data['back_btn']}}/update" data-id="{{Crypt::encryptString($value->id)}}" data-status="1" class="btn btn-sm btn-success mb-1 approve-reject">Approve</a><br>
                        
                        <a href="{{$data['back_btn']}}/update" data-id="{{Crypt::encryptString($value->id)}}" data-status="2" class="btn btn-sm btn-danger mb-1 approve-reject">Reject</a><br>

                        @else

                        <p>No Need Action!</p>

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
