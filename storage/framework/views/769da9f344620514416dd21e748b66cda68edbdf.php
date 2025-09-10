<?php echo $__env->make("salesman/include/header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


 <!-- content -->
<div class="container mt-3 mt-lg-4 mt-xl-5" id="main-content">
	<!-- order items-->
    <div class="col-12 col-md-12 col-lg-12 mb-3 mb-lg-2">
        <div class="input-group">
            <span class="input-group-text bg-none"><i class="bi bi-search"></i></span>
            <input class="form-control pe-0 bg-none" type="search" placeholder="Customer..." id="search">
        </div>
    </div>
	<div class="list-group bg-none mb-3 mb-lg-4" id="data-list">
	    
	</div>
</div>




<script>
   var data = '';
   var main_url = "<?php echo e($data['back_btn']); ?>/load_data";
   var type = 1;
   function get_url_data()
   {       
       var search = $("#search").val();
       data = `type=${type}&search=${search}`;
   }
   url = main_url+'?'+data;
   load_table();
   
   $(document).on("keyup", "#search",(function(e) {      
      event.preventDefault();
      get_url_data()
      url = main_url+'?'+data;
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
   


   $(document).on("change", "#from-date, #to-date",(function(e) {      
      event.preventDefault();
      loader("show");
        var form2 = new FormData();
        form2.append("from_date", $("#from-date").val())
        form2.append("to_date", $("#to-date").val())
        var settings = {
          "url": "<?php echo e(route('user.my-order.rbv')); ?>",
          "method": "POST",
          "timeout": 0,
          "processData": false,
          "headers": {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
           },
          "mimeType": "multipart/form-data",
          "contentType": false,
          "dataType": "json",
          "data": form2
        };
        $.ajax(settings).always(function (response) {
            loader("hide");
            response = admin_response_data_check(response);
            $("#totalBv").html(response.data.totalBv);
        });
   }));


</script>





<?php echo $__env->make("salesman/include/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\htdocs\projects\irshad\shivvedaecomcrm\resources\views/salesman/my-order/index.blade.php ENDPATH**/ ?>