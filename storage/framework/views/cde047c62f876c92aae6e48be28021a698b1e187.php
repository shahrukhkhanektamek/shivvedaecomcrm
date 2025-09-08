<?php echo $__env->make('user.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php ($min_deposit = json_decode(DB::table('setting')->where('name','main')->first()->data)->min_deposit); ?>

  
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



              
              <form class="form row form_data" action="<?php echo e($data['submit_url']); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                <?php echo csrf_field(); ?>


                <div class="col-md-12">
                  <div class="form-group">
                    <label>Amount (Min <?php echo e(Helpers::price_formate($min_deposit)); ?>)</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-inr"></i></div>
                      <input class="form-control" placeholder="Amount" type="number" name="amount" name="amount" required>
                    </div>
                  </div>
                </div>
                
                
                <div class="col-md-12">
                  <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
                </div>
                
              </form>


             
            </div>
          </div>
        </div>








        <div class="col-lg-12 mt-3">
          <div class="card card-outline">
            <div class="card-header bg-blue">
              <h5 class="text-white m-b-0"><?php echo e($data['title']); ?> History</h5>
            </div>
            <div class="card-body">              
              <div id="data-list"></div>             
            </div>
          </div>
        </div>



      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->





<script>
   var data = '';
   var main_url = "<?php echo e($data['back_btn']); ?>/load_data";

   function get_url_data()
   {
       var status = $("#statuschange").val();
       var order_by = $("#order_by").val();
       var limit = $("#limit").val();
       var filter_search_value = $(".search-input").val();
       data = `status=${status}&order_by=${order_by}&limit=${limit}&filter_search_value=${filter_search_value}`;
   }
   url = main_url+'?'+data;
   load_table();
   $(document).on("change", "#statuschange, .order_by, .limit",(function(e) {
      get_url_data();
      url =main_url+"?"+data;
      load_table();
   }));
   $(document).on("click", ".search",(function(e) {
      get_url_data();
      url =main_url+"?"+data;
      load_table();
   }));
   $(document).on("click", ".pagination a",(function(e) {      
      event.preventDefault();
      get_url_data()
      url = $(this).attr("href")+'&'+data;
      load_table();
   }));

   function load_table()
   {
        data_loader("#data-list",1);
        var form = new FormData();
        var settings = {
          "url": url,
          "method": "GET",
          "timeout": 0,
          "processData": false,
          "headers": {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
           },
          "mimeType": "multipart/form-data",
          "contentType": false,
          "dataType": "json",
          "data": form
        };
        $.ajax(settings).always(function (response) {
            data_loader("#data-list",0);
            response = admin_response_data_check(response);
            $("#data-list").html(response.data.list);
        });
   }


</script>






<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/shivved1/public_html/resources/views/user/deposit/index.blade.php ENDPATH**/ ?>