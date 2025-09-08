<?php echo $__env->make('user.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1 class="text-black"><?php echo e($data['page_title']); ?></h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo e(url('user/dashboard')); ?>">Home</a></li>
        <?php $__currentLoopData = $data['pagenation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li class="sub-bread"><i class="fa fa-angle-right"></i> <?php echo e($value); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                 <div class="wallet mainwallet mb-4">
                          <div class="mainwalleticon">
                           <i class="icon-wallet"></i>
                          </div>
                          <div class="mainwalletcontent">
                             <h2 class="wallet-balance"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome(Session::get('user')['id'])->wallet)); ?></h2> 
                          </div>
                 </div>

              
              <form class="form row form_data" action="<?php echo e($data['submit_url']); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                <?php echo csrf_field(); ?>


                <div class="col-md-3">
                  <div class="form-group">
                    <label>Member ID.</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-star"></i></div>
                      <input class="form-control" placeholder="Member ID." type="number" name="member_id" id="sponser_id" required>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Member Name</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-user"></i></div>
                      <input class="form-control" placeholder="Member Name" type="text" disabled id="member_name">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Sponser Name</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-user"></i></div>
                      <input class="form-control" placeholder="Sponser Name" type="text" disabled id="sponser_name">
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>Sponser ID</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-user"></i></div>
                      <input class="form-control" placeholder="Sponser ID" type="text" disabled id="sponser_id2">
                    </div>
                  </div>
                </div>

                

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Select Package</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-globe"></i></div>
                      <select class="form-select mb-3" aria-label="Default select example" name="package" required>
                          <option value=""  >Select</option>
                          <?php ($packages = DB::table('package')->get()); ?>
                          <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($value->id); ?>" ><?php echo e($value->name); ?> (<?php echo e(Helpers::price_formate($value->sale_price)); ?>)</option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                    </div>
                  </div>
                </div>

                
                
                
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
                
              </form>


             
            </div>
          </div>
        </div>
      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->






  <script>
    function check_sponser()
    {
        $(search_input).parent().find(".alert").remove();
        input_loader(search_input,1);

        var sponser_id = $("#sponser_id").val();
        var form = new FormData();
        form.append("sponser_id", sponser_id);
        var settings = {
          "url": "<?php echo e(url('check-sponser')); ?>",
          "method": "POST",
          "processData": false,
          "mimeType": "multipart/form-data",
          "headers": {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
           },
          "contentType": false,
          "dataType":"json",
          "data": form
        };
        $.ajax(settings).always(function (response) {
            input_loader(search_input,0);
          
          response = admin_response_data_check(response);
          console.log(response);

          if(response.status==200)
          {            
            print_input_search_success_error(search_input,response.message,1);
            $("#member_name").val(response.data.name);
            $("#sponser_name").val(response.data.sponser_name);
            $("#sponser_id2").val(response.data.sponser_id);
          }
          else
          {
            if(sponser_id!='')
            {
                print_input_search_success_error(search_input,response.message,2);
            }
            $("#member_name").val('');
            $("#sponser_name").val('');
            $("#sponser_id2").val('');
          }   


        });
    }
    $(document).on("keyup", "#sponser_id",(function(e) {
        search_input = $(this);
        check_sponser();
    }));
  </script>







<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u171934876/domains/developershahrukh.in/public_html/demo/irshad/shivveda/resources/views/user/activation/index.blade.php ENDPATH**/ ?>