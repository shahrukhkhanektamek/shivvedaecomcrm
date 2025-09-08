<div>
    <div class="table-responsive">
        <table class="table">
            <thead class="bg-success text-white">
                @if(empty($data['level']))
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Members</th>
                        <th scope="col">RBV</th>
                        <th scope="col">Amount</th>
                        <th scope="col">TDS</th>
                        <th scope="col">Final Amount</th>
                    </tr>
                @else
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">RBV</th>
                        <th scope="col">Amount</th>
                        <th scope="col">TDS</th>
                        <th scope="col">Final Amount</th>
                    </tr>
                @endif
            </thead>
            <tbody>

                @if(empty($data['level']))
                    @foreach($data_list as $key=>$member)
                        <tr>
                            <td>Level: {{ $key }}</td>
                            <td><a href="{{$data['route'].'/level-member/'.$key}}">{{ $member['totalMembers'] }}</a></td>
                            <td>{{ $member['total_rbv'] }}</td>
                            <td><span class="text-danger text-bold">{{Helpers::price_formate($member['amount'])}}</span></td>
                            <td><span class="text-danger text-bold">{{Helpers::price_formate($member['tds_amount'])}}</span></td>
                            <td><span class="text-danger text-bold">{{Helpers::price_formate($member['final_amount'])}}</span></td>
                        </tr>
                    @endforeach

                    @else

                    @foreach($data_list as $key=>$member)
                        @if(!empty($member['name']))
                            <tr>
                                <td>{{$member['member_id']}}</td>
                                <td>{{$member['name']}}</td>
                                <td>{{ $member['rbv'] }}</td>
                                <td><span class="text-danger text-bold">{{Helpers::price_formate($member['amount'])}}</span></td>
                                <td><span class="text-danger text-bold">{{Helpers::price_formate($member['tds_amount'])}}</span></td>
                                <td><span class="text-danger text-bold">{{Helpers::price_formate($member['final_amount'])}}</span></td>
                            </tr>
                        @endif
                    @endforeach

                @endif
            </tbody>
        </table>
        <!-- end table -->
    </div>
</div>



