    </main>
</div>


<!-- mobile footer -->
<footer class="adminuiux-mobile-footer hide-on-scrolldown style-1">
    <div class="container">
        <ul class="nav nav-pills nav-justified">
            <li class="nav-item">
                <a class="nav-link" href="{{url('salesman/product')}}">
                    <span>
                        <i class="nav-icon bi bi-shop"></i>
                        <span class="nav-text">Shop</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{url('salesman/my-order')}}">
                    <span>
                        <i class="nav-icon bi bi-bag-check"></i>
                        <span class="nav-text">Orders</span>
                    </span>
                </a>
            </li>
            <!-- <li class="nav-item">
                <a href="{{url('salesman/customers')}}" class="nav-link ">
                    <span>
                        <i class="nav-icon bi bi-people"></i>
                        <span class="nav-text">Customers</span>
                    </span>
                </a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="{{url('salesman/cart')}}">
                    <span>
                        <i class="menu-icon bi bi-cart"></i>
                        <span class="nav-text">Cart</span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
</footer>

         


<script src="{{url('public/')}}/toast/saber-toast.js"></script>

<script src="{{url('public/')}}/toast/script.js"></script>

<script src="{{url('public/')}}/assetsadmin/select2/js/select2.full.min.js"></script>



<script>

  

$("select").select2();

$('.tags').select2({

  tags: true,

  tokenSeparators: ['||', '\n']

});



$(document).on('click',".logout",function (e) {

  event.preventDefault();

  loader('show');

  $.ajax({

      url:"{{route('salesman.salesman-logout')}}",

      type:"GET",

      dataType:"json",

      success:function(d)

      {

        admin_response_data_check(d)  

      },

      error: function(e) 

    {

      admin_response_data_check(e)

    } 

  });

});



$(".upload-single-image").on('change', function(){

  var files = [];

  var j=1;

  var upload_div = $(this).data("target");

  var name = $(this).data('name');

  $( "."+upload_div ).empty();

    for (var i = 0; i < this.files.length; i++)

    {

        if (this.files && this.files[i]) 

        {

            var reader = new FileReader();

            reader.onload = function (e) {

            $('.'+upload_div).attr("src",e.target.result);

            j++;

        }

        reader.readAsDataURL(this.files[i]);

    }

  }      

});
</script>



<script>
   var type = 0;
   var p_id = 0;
   var input = 0;
   var qty = 0;
   $(document).on("click", ".plus-btn, .devide-btn",(function(e) {
      type = $(this).data("type");
      p_id = $(this).data("id");
      input = $(this).parent().parent().find('input');

      if(type=='2') qty = parseInt($(input).val())+1;
      else qty = parseInt($(input).val())-1;
      if(qty<1) qty = 0;


      $(input).val(qty);

      event.preventDefault();
      loader("show");
        var form = new FormData();
        form.append("id",p_id);
        form.append("qty",qty);
        var settings = {
          "url": "{{route('salesman.cart.add')}}",
          "method": "POST",
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
            loader("hide");
            response = admin_response_data_check(response);
            if(response.status==200)
            {
              var data = response.data;
              if(data.cartCount>0) $(".checkout").show();
              else $(".checkout").hide();

              $("#totalBv").html(data.totalBv);

            }
        });
   }));


</script>














    </body>

</html>