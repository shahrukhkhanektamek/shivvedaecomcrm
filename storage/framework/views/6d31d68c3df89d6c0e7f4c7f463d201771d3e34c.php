<?php echo $__env->make("salesman/include/header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



        <!-- content -->
        <div class="container mt-3 mt-lg-4 mt-xl-5" id="main-content">
           

            <div class="col-12 col-md-12 col-lg-12 mb-3 mb-lg-2">
                <div class="input-group">
                    <span class="input-group-text bg-none"><i class="bi bi-search"></i></span>
                    <input class="form-control pe-0 bg-none" type="search" placeholder="Customer..." id="search">
                </div>
            </div>
            <!-- products -->
            <div class="row gx-3" id="data-list">                
            </div>

        
        </div>   



<script>
   var data = '';
   var main_url = "<?php echo e(url('salesman/product')); ?>/load_data";

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
   
   $(document).on("click", "#search",(function(e) {
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
            if(response.status==200)
            {
              var data = response.data;
              var cartDetail = data.cartDetail; 
              var cartCount = cartDetail.cartCount;
              if(cartCount>0) $(".checkout").show();
              else $(".checkout").hide();

              $("#totalBv").html(data.cartDetail.totalBv);

            }

        });
   }



</script>


 <?php echo $__env->make("salesman/include/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\htdocs\projects\irshad\shivvedaecomcrm\resources\views/salesman/dashboard/index.blade.php ENDPATH**/ ?>