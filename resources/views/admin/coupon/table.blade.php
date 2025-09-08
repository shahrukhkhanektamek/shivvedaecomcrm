<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">Code</th>
                    <th scope="col">Package</th>
                    <th scope="col">Type</th>
                    <th scope="col">Limit</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data_list as $key=> $value)

            @php($package = DB::table('package')->where('id',$value->package_id)->first())

                <tr>
                    
                    <td>{{$value->code}}</td>
                    <td>
                        {{@$package->name}}
                    </td>
                    <td>
                        @if($value->type==1)
                            Flat
                        @endif
                        @if($value->type==2)
                            Percent
                        @endif
                    </td>
                    <td>{{$value->limit_use}}</td>
                    <td>{{$value->amount}}</td>
                    <td>
                        <div class="d-flex gap-2">

                            <div class="edit">
                                <a href="{{$data['edit_btn_url'].'/'.Crypt::encryptString($value->id)}}" class="btn btn-sm btn-success edit-item-btn">Edit</a>
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
