@include('user.headers.header')



  



  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper"> 

    <!-- Content Header (Page header) -->

    <div class="content-header sty-one">

      <h1 class="text-black">{{$data['page_title']}}</h1>

      <ol class="breadcrumb">

        <li><a href="{{url('user/dashboard')}}">Home</a></li>

        @foreach($data['pagenation'] as $key => $value)

          <li class="sub-bread"><i class="fa fa-angle-right"></i> {{$value}}</li>

        @endforeach

      </ol>

    </div>

    

    <!-- Main content -->

    <div class="content">

      <div class="row">





            <div class="col-lg-12">

          <div class="info-box">



                <div class="col-lg-4" style="margin: 0 auto;">

                    <div class="user-profile-box m-b-3">

                      <div class="box-profile text-white">
                        <img class="profile-user-img img-responsive img-circle m-b-2" src="{{Helpers::image_check($row->image,'user.png')}}" alt="User profile picture">
                        <a href="{{route('user.profile-image.edit')}}" class="badge badge-primary p-1" style="margin: 0 auto;display: block;width: fit-content;margin-bottom: 10px;">Update Image</a>
                        <h3 class="profile-username text-center">{{$row->name}}</h3>
                      </div>

                    </div>

                    

                  </div>







            <form class="form row form_data" action="{{$data['submit_url']}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>

                @csrf





                



                <div class="col-md-6">

                  <div class="form-group">

                    <label>First Name</label>

                    <div class="input-group">

                      <div class="input-group-addon"><i class="fa fa-user"></i></div>

                      <input class="form-control" placeholder="First Name" type="text" name="name" value="{{@explode(' ', $row->name)[0]}}" required>

                    </div>

                  </div>

                </div>

                <div class="col-md-6">

                  <div class="form-group">

                    <label>Last Name</label>

                    <div class="input-group">

                      <div class="input-group-addon"><i class="fa fa-user"></i></div>

                      <input class="form-control" placeholder="Last Name" type="text" name="lname" value="{{@explode(' ', $row->name)[1]}}" >

                    </div>

                  </div>

                </div>



                <div class="col-md-6">

                  <div class="form-group">

                    <label>Email</label>

                    <div class="input-group">

                      <div class="input-group-addon"><i class="fa fa-envelope"></i></div>

                      <input class="form-control" placeholder="Email" type="email" name="email" value="{{$row->email}}" >

                    </div>

                  </div>

                </div>



                <div class="col-md-6">

                  <div class="form-group">

                    <label>Select Country</label>

                    <div class="input-group">

                      <div class="input-group-addon"><i class="fa fa-globe"></i></div>

                      <select class="form-select mb-3" aria-label="Default select example" name="country" >

                          <option value=""  >Select</option>

                          @php($countries = DB::table('countries')->get())

                          @foreach($countries as $key => $value)

                              <option value="{{$value->id}}" @if($row->country==$value->id)selected @endif >{{$value->name}} (+{{$value->phonecode}})</option>

                          @endforeach

                      </select>

                    </div>

                  </div>

                </div>



                <div class="col-md-6">

                  <div class="form-group">

                    <label>Phone</label>

                    <div class="input-group">

                      <div class="input-group-addon"><i class="fa fa-phone"></i></div>

                      <input class="form-control" placeholder="Phone" type="number" name="phone" value="{{$row->phone}}"  >

                    </div>

                  </div>

                </div>



                <div class="col-md-6">

                  <div class="form-group">

                    <label>State</label>

                    <div class="input-group">

                      <div class="input-group-addon"><i class="fa fa-circle-o"></i></div>

                      <select class="form-select mb-3" aria-label="Default select example" name="state" >

                          <option value=""  >Select</option>

                          @php($states = DB::table('states')->get())

                          @foreach($states as $key => $value)

                              <option value="{{$value->id}}" @if($row->state==$value->id) selected @endif >{{$value->name}}</option>

                          @endforeach

                      </select>

                    </div>

                  </div>

                </div>



                <div class="col-md-6">

                  <div class="form-group">

                    <label>City</label>

                    <div class="input-group">

                      <div class="input-group-addon"><i class="fa fa-circle-o"></i></div>

                      <input class="form-control" placeholder="City" type="text" name="city" value="{{$row->city}}" >

                    </div>

                  </div>

                </div>



                <div class="col-md-12">

                  <div class="form-group">

                    <label>Address</label>

                    <div class="input-group">

                      <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>

                      <input class="form-control" placeholder="Address" type="text" name="address" value="{{$row->address}}" >

                    </div>

                  </div>

                </div>



                

                

                <div class="col-md-12">

                  <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Update</button>

                </div>



              </form>

          </div>

        </div>

      </div>

      <!-- Main row --> 

    </div>

    <!-- /.content --> 

  </div>

  <!-- /.content-wrapper -->



@include('user.headers.footer')

