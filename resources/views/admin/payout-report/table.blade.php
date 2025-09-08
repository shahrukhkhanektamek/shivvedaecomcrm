<div>

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">

                <tr>

                    

                    <th>User Name</th>

                    <th>Bank Name</th>

                    <th>Bank Holder Name</th>

                    <th>Bank Account No. </th>

                    <th>IFSC Code </th>
                    <th>PanCard </th>

                    <th>Phone </th>

                    <th>Amount </th>
                    <th>TDS </th>
                    <th>R. Wallet </th>
                    <th>Final Amount </th>

                    <!-- <th>Income Date </th>

                    <th>Payout Date </th> -->

                </tr>

            </thead>

            <tbody>

            @foreach($data_list as $key=> $value)



            @php($user = \App\Models\User::where('id',$value->member_id)->select('bank_holder_name','account_number','ifsc','rg_mobile','bank_name','name','user_id','kyc_step','is_paid')->first())

                <tr>                   

                    <th>

                        {{@$user->name}}<br>

                        Affiliate ID.:- {{$user->user_id}}<br>

                        @if($user->is_paid==1)
                        <span class="badge bg-success">Paid</span>
                        @endif
                        @if($user->is_paid==0)
                        <span class="badge bg-danger">UnPaid</span>
                        @endif

                        <br>

                        @if($user->kyc_step==1)
                        <span class="badge bg-success">KYC Complete</span>
                        @endif

                        @if($user->kyc_step==2)
                        <span class="badge bg-info">KYC Under Review</span>
                        @endif

                        @if($user->kyc_step==3)
                        <span class="badge bg-warning">KYC Rejected</span>
                        @endif

                        @if($user->kyc_step==0)
                        <span class="badge bg-info">KYC Not Update</span>
                        @endif

                    </th>

                    <th>{{@$user->bank_name}}</th>

                    <th>{{@$user->bank_holder_name}}</th>

                    <th>{{@$user->account_number}}</th>

                    <th>{{@$user->ifsc}}</th>
                    <th>{{@$user->pan}}</th>

                    <th>{{@$user->rg_mobile}}</th>

                    <th>{{Helpers::price_formate(@$value->amount)}}</th>                    
                    <th>{{Helpers::price_formate(@$value->tds_amount)}}</th>                    
                    <th>{{Helpers::price_formate(@$value->wallet_amount)}}</th>                    
                    <th>{{Helpers::price_formate(@$value->final_amount)}}</th>                    

                    

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

