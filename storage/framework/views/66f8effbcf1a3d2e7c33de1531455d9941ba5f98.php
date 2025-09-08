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
       



        <div class="col-lg-12 mt-3">
          <div class="card card-outline">
            
            <div class="card-body" id="data-list">
                         
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






<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u171934876/domains/developershahrukh.in/public_html/demo/irshad/shivveda/resources/views/user/right-team/index.blade.php ENDPATH**/ ?>