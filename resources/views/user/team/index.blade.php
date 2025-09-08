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

          <div class="card card-outline">

            <!-- <div class="card-header bg-blue">

              <h5 class="text-white m-b-0">Basic Example</h5>

            </div> -->

            <div class="card-body">



<link rel="stylesheet" href="<?=url('public/tree/hic');?>/hierarchy-view.css">

<link rel="stylesheet" href="<?=url('public/tree/hic');?>/main.css">

<style>
    .management-hierarchy .person > img{ width:45px; height:50px}
    .bl{color:#900;}
    .bc{color:#060;}
    .management-hierarchy .person > p.name {text-transform: capitalize;}
</style>


<style>
    /*@media(max-width: 867px)
    {
        .management-hierarchy .person > img {
            width: 15px !important;
            height: 15px !important;
        }
        .management-hierarchy .person > p.name, .detail-popup p {
            font-size: 3px !important;
        }
        .hv-wrapper .hv-item .hv-item-children .hv-item-child:after {
            height: 0.5px !important;
        }
        .hv-wrapper .hv-item .hv-item-children .hv-item-child:before {
            width: 0.5px !important;
        }
        .detail-popup {
            width: 85px;
        }
    }*/
</style>






        <section class="management-hierarchy hiten" style="padding-bottom: 50px;" id="zoomDiv">

          

          

        

        

        


        </section>



              

            



             

            </div>

          </div>

        </div>

      </div>      

    </div>

    <!-- /.content --> 

  </div>

  <!-- /.content-wrapper -->









@include('user.headers.footer')

<script>
    var data = '';
    var main_url = "{{route('user.get_tree')}}";
     var windowWidth = window.innerWidth;
    function get_url_data()
   {
       data = `id={{$id}}&windowWidth=${windowWidth}`;
   }
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
            $("#zoomDiv").html(response.data.list);
            person_center()

        });
    }
    get_url_data()
    url =main_url+"?"+data;
    load_table();


     function person_center (){
            const container = document.getElementById('hv-container'); // your scrollable container
            const target = container.querySelector('#mainPerson .person'); // target element

            if (container && target) {
                const containerRect = container.getBoundingClientRect();
                const targetRect = target.getBoundingClientRect();

                const scrollTo = target.offsetLeft - (container.clientWidth / 2) + (target.offsetWidth / 2);
                container.scrollLeft = scrollTo;
            }
        };
        person_center()
 </script>


<script>
// let zoomLevel = 1; // 1 = 100%

// function applyZoom() {
//     const zoomDiv = document.getElementById("zoomDiv");
//     if (zoomDiv) {
//         zoomDiv.style.transform = `scale(${zoomLevel})`;
//         zoomDiv.style.transformOrigin = '0 0';
//         zoomDiv.style.width = `${100 / zoomLevel}%`;
//     }
// }

// // Handle keyboard (Ctrl + + / - / 0)
// document.addEventListener("keydown", function(event) {
//     if (event.ctrlKey) {
//         if (event.key === "=" || event.key === "+") {
//             zoomLevel += 0.1;
//         } else if (event.key === "-") {
//             zoomLevel = Math.max(0.1, zoomLevel - 0.1);
//         } else if (event.key === "0") {
//             zoomLevel = 1;
//         }
//         applyZoom();
//         event.preventDefault();
//     }
// });

// // Handle mouse wheel zoom (Ctrl + scroll)
// document.addEventListener("wheel", function(event) {
//     if (event.ctrlKey) {
//         if (event.deltaY < 0) {
//             zoomLevel += 0.1;
//         } else {
//             zoomLevel = Math.max(0.1, zoomLevel - 0.1);
//         }
//         applyZoom();
//         event.preventDefault();
//     }
// }, { passive: false });

// // Handle pinch zoom on touch devices (two-finger pinch)
// let pinchStartDistance = null;

// function getDistance(touches) {
//     const dx = touches[0].clientX - touches[1].clientX;
//     const dy = touches[0].clientY - touches[1].clientY;
//     return Math.sqrt(dx * dx + dy * dy);
// }

// document.addEventListener("touchstart", function(event) {
//     if (event.touches.length === 2) {
//         pinchStartDistance = getDistance(event.touches);
//     }
// });

// document.addEventListener("touchmove", function(event) {
//     if (event.touches.length === 2 && pinchStartDistance !== null) {
//         const newDistance = getDistance(event.touches);
//         if (newDistance > pinchStartDistance + 10) {
//             zoomLevel += 0.05;
//             pinchStartDistance = newDistance;
//             applyZoom();
//         } else if (newDistance < pinchStartDistance - 10) {
//             zoomLevel = Math.max(0.1, zoomLevel - 0.05);
//             pinchStartDistance = newDistance;
//             applyZoom();
//         }
//         event.preventDefault();
//     }
// }, { passive: false });

// document.addEventListener("touchend", function(event) {
//     if (event.touches.length < 2) {
//         pinchStartDistance = null;
//     }
// });
</script>
