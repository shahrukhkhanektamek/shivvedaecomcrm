<?php include"headers/header.php"; ?>


  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1 class="text-black">Form Layouts</h1>
      <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Forms</li>
        <li><i class="fa fa-angle-right"></i> Form Layouts</li>
      </ol>
    </div>
    
    <!-- Main content -->
    <div class="content">
      <div class="row">
        <div class="col-lg-12">
          <div class="card card-outline">
            <!-- <div class="card-header bg-blue">
              <h5 class="text-white m-b-0">Basic Example</h5>
            </div> -->
            <div class="card-body">



              <form class="form ">
                <div class="form-group">
                  <label for="exampleInputuname">User Name</label>
                  <div class="input-group">
                    <div class="input-group-addon"><i class="ti-user"></i></div>
                    <input class="form-control" id="exampleInputuname" placeholder="Username" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <div class="input-group">
                    <div class="input-group-addon"><i class="ti-email"></i></div>
                    <input class="form-control" id="exampleInputEmail1" placeholder="Enter email" type="email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="pwd1">Password</label>
                  <div class="input-group">
                    <div class="input-group-addon"><i class="ti-lock"></i></div>
                    <input class="form-control" id="pwd1" placeholder="Enter email" type="password">
                  </div>
                </div>
                <div class="form-group">
                  <label for="pwd2">Confirm Password</label>
                  <div class="input-group">
                    <div class="input-group-addon"><i class="ti-lock"></i></div>
                    <input class="form-control" id="pwd2" placeholder="Enter email" type="password">
                  </div>
                </div>
                <div class="form-group">
                  <div class="checkbox checkbox-success">
                    <input id="checkbox1" type="checkbox">
                    <label for="checkbox1"> Remember me </label>
                  </div>
                </div>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
                <button type="submit" class="btn btn-inverse waves-effect waves-light">Cancel</button>
              </form>


             
            </div>
          </div>
        </div>
      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->





<?php include"headers/footer.php"; ?>