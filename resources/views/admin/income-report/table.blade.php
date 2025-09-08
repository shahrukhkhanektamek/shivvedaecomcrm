<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 10px;">
                        <div class="form-check">
                            <input class="form-check-input fs-15" type="checkbox"
                                id="checkAll" value="option">
                        </div>
                    </th>
                    <th>Member ID    </th>
                    <th>Name</th>
                    <th>Email </th>
                    <th>Phone </th>
                    <th>Amount </th>
                    <th>TDS </th>
                    <th>R. Wallet </th>
                    <th>Final Amount </th>
                    <th>Type </th>
                    <th>Income Date Time </th>
                </tr>
            </thead>
            <tbody>
            @foreach($data_list as $key=> $value)
            @php($user = \App\Models\MemberModel::GetUserData($value->member_id))
            @php($sponser = \App\Models\MemberModel::GetSponserData($value->sponser_id))
                <tr>
                    <th scope="row">
                        <div class="form-check">
                            <input class="form-check-input fs-15" type="checkbox"
                                name="checkAll" value="option1">
                        </div>
                    </th>
                    <th>{{sort_name.$user->user_id}}</th>
                    <th>{{@$user->name}}</th>
                    <th>{{@$user->email}}</th>
                    <th>{{@$user->phone}}</th>
                    <th>{{Helpers::price_formate($value->amount)}}</th>                    
                    <th>{{Helpers::price_formate($value->tds_amount)}}</th>                    
                    <th>{{Helpers::price_formate($value->wallet_amount)}}</th>                    
                    <th>{{Helpers::price_formate($value->final_amount)}}</th>                    
                    <th>
                        <span class="badge bg-success">{{$value->type_name}}</span>
                    </th>                    
                    <td>{{date("d M, Y h:i A", strtotime($value->package_payment_date_time))}}</td>
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
