<div>

    <div class="table-responsive">

        <table class="table">

            <thead class="bg-success text-white">

                <tr>                    

                    <th scope="col">Member ID.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Status</th>
                    <th scope="col">All Time Income</th>
                    <th scope="col">Join Date</th>

                </tr>

            </thead>

            <tbody>

            @foreach($data_list as $key=> $value)

            @php($all_time_income = \App\Models\MemberModel::all_time_income($value->id))

                <tr>
                    <td>{{$value->user_id}}</td>
                    <td>{{$value->name}}</td>
                    <td>@if($value->position==1)Left @else Right @endif</td>
                    <td>@if($value->is_paid==1) <span class="badge badge-success">Paid</span> @else <span class="badge badge-danger">UnPaid</span> @endif</td>
                    <td>{{Helpers::price_formate($all_time_income)}}</td>
                    <td>{{date("d M, Y h:i A", strtotime($value->add_date_time))}}</td>
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

