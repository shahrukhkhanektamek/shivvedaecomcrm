<?php echo $__env->make("salesman/include/header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



        <!-- content -->
        <div class="container mt-3 mt-lg-4 mt-xl-5" id="main-content" style="display:none;">
           
            <!-- products -->
            <div class="row gx-3">
                <div class="card bg-none mb-3 mb-lg-4">
                    <div class="card-body">
                        <h6 class="mb-3">Order (7 Items)</h6>
                        <ul class="list-group adminuiux-list-group mb-0" id="data-list">
                            
                        </ul>
                    </div>
                </div>

                <div class="card bg-none mb-3 mb-lg-4 d-none">
                    <div class="card-body">
                        <h4 class="mb-1">$261.00</h4>
                        <p><span class="badge badge-light text-bg-success">Paid</span></p>

                        <div class="row gx-3 mb-2">
                            <div class="col">Sub Total</div>
                            <div class="col-auto">$ 270.00</div>
                        </div>
                        <div class="row gx-3 mb-2">
                            <div class="col">Discount</div>
                            <div class="col-auto">$ 10.00</div>
                        </div>
                        <div class="row gx-3 mb-2">
                            <div class="col">Shipping Charge</div>
                            <div class="col-auto">FREE</div>
                        </div>
                        <div class="row gx-3 mb-3 mb-lg-4">
                            <div class="col">Gift Packaging</div>
                            <div class="col-auto">00.00</div>
                        </div>

                        <h6 class="mb-3">Payment details</h6>
                        <p class="text-secondary mb-2">Net Banking:</p>
                        <p class="text-secondary mb-2">AmericaSpice Bank</p>
                        <p class="text-secondary mb-2">A/C No.: 200525XXXXXX5524</p>
                        <p class="text-secondary text-truncated mb-2">Transaction.: FGDFG44G56G4D1G65G4DSG1S6G4G1SGS6G1S</p>
                        <p class=""><span class="badge badge-sm badge-light text-bg-theme-1 theme-blue">Received</span></p>

                        <button class="btn btn-theme theme-green"><i class="bi bi-whatsapp"></i> Invoice</button>
                        <button class="btn btn-light mx-1"><i class="bi bi-envelope"></i> Invoice</button>
                    </div>
                </div>
           
            </div>
            <a href="<?php echo e(url('salesman/scan-face')); ?>" class="btn btn-success d-block">Checkout</a>        
        </div>

        <div class="container mt-3 mt-lg-4 mt-xl-5" id="cart-empty" style="display:none;">
            <h1 style="text-align: center;text-transform: uppercase;">Empty Cart</h1>
        </div>




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
            if(response.status==200)
            {
              var data = response.data;
              var cartDetail = data.cartDetail; 
              var cartCount = cartDetail.cartCount;
              if(cartCount>0)
              {
                $("#main-content").show();                
                $("#cart-empty").hide();                
              }
              else
              {
               $("#main-content").hide();                
               $("#cart-empty").show();                
              }

              $("#totalBv").html(data.cartDetail.totalBv);

            }

        });
   }



</script>



 <?php echo $__env->make("salesman/include/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\htdocs\projects\irshad\shivvedaecomcrm\resources\views/salesman/cart/index.blade.php ENDPATH**/ ?>