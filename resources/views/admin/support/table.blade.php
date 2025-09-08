<div>

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th>Ticket ID.</th>
                    <th>Date Time</th>
                    <th>Subject</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>User Phone</th>
                    <th scope="col">Action</th>

                </tr>

            </thead>

            <tbody>

            @foreach($data_list as $key=> $value)

                <tr>
                    <td>{{$value->ticket_id}}</td>
                    <td>{{$value->add_date_time}}</td>
                    <td>{{$value->subject}}</td>                    
                    <td>{{$value->user_name}}<br><b>{{$value->member_id}}</b></td>
                    <td>{{$value->user_email}}</td>
                    <td>{{$value->user_phone}}</td>



                    <td>

                        <div class="d-flex gap-2">

                            <div class="edit">

                                <a href="{{$data['view_btn_url'].'/'.Crypt::encryptString($value->id)}}" class="btn btn-sm btn-success edit-item-btn">View</a>

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

