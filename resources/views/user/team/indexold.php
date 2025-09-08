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

          

          

        

        

        

        

<?php



$memberlist = $tree_view['memberlist'];

                

$member_detail = $user = \App\Models\MemberModel::GetUserData($id);

$member_log = $user;

$user_id=$id;



$imgae_path = 'green.png';









    $total_bp = 0;



            ?>

       

        <div class="hv-container" id="hv-container">

            <div class="hv-wrapper">



                <!-- Key component -->

                <div class="hv-item">



                    <div class="hv-item-parent" id="mainPerson">

                        
                        <?php
                            $data['member_detail'] = $member_detail;
                            echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();
                        ?>

                    </div>





































<div class="hv-item-children">

                         

                    <?php foreach($memberlist as $level_one){ ?>    

                        

                        <div class="hv-item-child">

                            <!-- Key component -->

                            <div class="hv-item">

                                <div class="hv-item-parent">

                                    <?php 

                                        $data = $level_one;

                                        echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

                                    ?>

                                </div>





                                <div class="hv-item-children">



                                 <?php foreach($level_one['L_3'] as $three){ ?>  

                                     <div class="hv-item-child">

                                        <?php 

                                            $data = $three;

                                            echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

                                        ?>

                                        

                                       

                                       <!--Level 4 -->

                                            <div class="hv-item-child">

                                                <!-- Key component -->

                                                <div class="hv-item">

                                                     <div class="hv-item-parent">

                                                     </div>

                                                     <div class="hv-item-children">

                                                     <?php foreach($three['L_4'] as $four){  ?>  

                                                         <div class="hv-item-child">

                                                            

                                    <?php 

                                        $data = $four;

                                        echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

                                    ?>

                                                            



                                                            <div class="hv-item-child">

                                                <!-- Key component -->

                                                <div class="hv-item">

                                                     <div class="hv-item-parent">

                                                     </div>

                                                     <div class="hv-item-children">



                                                     <?php foreach($four['L_5'] as $five){  ?>  

                                                         <div class="hv-item-child">

                                    <?php 

                                        $data = $five;

                                        echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

                                    ?>

                                                            

                                                            

                                                                <div class="hv-item-child">

                                                                    <!-- Key component -->

                                                                    <div class="hv-item">

                                                                         <div class="hv-item-parent">

                                                                         </div>

                                                                         <div class="hv-item-children">

                                                                        <?php foreach($five['L_6'] as $six){  ?>

                                                                             <div class="hv-item-child">

                                            

                                    <?php 

                                        $data = $six;

                                        echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

                                    ?>

                                            

                                                                                

                                                                                    <div class="hv-item-child">

                                                                                        <!-- Key component -->

                                                                                        <div class="hv-item">

                                                                                             <div class="hv-item-parent">

                                                                                             </div>

                                                                                             <div class="hv-item-children">

                                    <?php foreach($six['L_7'] as $seven){  ?>

                                         <div class="hv-item-child">

                                    <?php 

                                        $data = $seven;

                                        echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

                                    ?>





<div class="hv-item-child">

  <!-- Key component -->

  <div class="hv-item">

       <div class="hv-item-parent">

       </div>

       <div class="hv-item-children">

      <?php foreach($seven['L_8'] as $eight){  ?>

           <div class="hv-item-child">



            <?php 

                $data = $eight;

                echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

            ?>







<div class="hv-item-child">

  <!-- Key component -->

  <div class="hv-item">

       <div class="hv-item-parent">

       </div>

       <div class="hv-item-children">

      <?php foreach($eight['L_9'] as $nine){  ?>

           <div class="hv-item-child">



        <?php 

            $data = $nine;

            echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

        ?>

              







<div class="hv-item-child">

  <!-- Key component -->

  <div class="hv-item">

       <div class="hv-item-parent">

       </div>

       <div class="hv-item-children">

      <?php foreach($nine['L_10'] as $ten){ ?>

           <div class="hv-item-child">



            <?php 

                $data = $ten;

                echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

            ?>

              





<div class="hv-item-child">

  <!-- Key component -->

  <div class="hv-item">

       <div class="hv-item-parent">

       </div>

       <div class="hv-item-children">

      <?php foreach($ten['L_11'] as $eleven){  ?>

           <div class="hv-item-child">

              

            <?php 

                $data = $eleven;

                echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

            ?>





<div class="hv-item-child">

  <!-- Key component -->

  <div class="hv-item">

       <div class="hv-item-parent">

       </div>

       <div class="hv-item-children">

      <?php foreach($eleven['L_12'] as $twelb){  ?>

           <div class="hv-item-child">

              

            <?php 

                $data = $twelb;

                echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

            ?>







<div class="hv-item-child">

  <!-- Key component -->

  <div class="hv-item">

       <div class="hv-item-parent">

       </div>

       <div class="hv-item-children">

      <?php foreach($twelb['L_13'] as $thrteen){  ?>

           <div class="hv-item-child">

              

       <?php 

            $data = $thrteen;

            echo \Illuminate\Support\Facades\View::make('user/team/team-card',compact('data'))->render();

        ?>





           </div>

      <?php }//end of level_tweleb?>      

        </div>

   </div>

</div>







           </div>

      <?php }//end of level_tweleb?>      

        </div>

   </div>

</div>











           </div>

      <?php }//end of level_eleven?>      

        </div>

   </div>

</div>







           </div>

      <?php }//end of level_ten?>      

        </div>

   </div>

</div>

         



           </div>

           

      <?php }//end of level_nine?>      

        </div>

   </div>

</div>













           </div>

           

      <?php }//end of level_eight?>      

        </div>

   </div>

</div>







                                                                                                 </div>

                                                                                                 

                                                                                            <?php }//end of level_seven?>      

                                                                                              </div>

                                                                                         </div>

                                                                                    </div>









                                                                             </div>

                                                                          <?php }//end of level_six?> 

                                                                          </div>

                                                                     </div>

                                                                </div>

                                                        </div>

                                                                    

                                                       <?php }//end of level_five?>   

                                                      

                                                     </div>

                    

                                                </div>

                                            </div>

                                                            

                                                        </div>

                                                                    

                                                       <?php }//end of level_four?>   

                                                      

                                                     </div>

                    

                                                </div>

                                            </div>

                                         <!--Level 4 -->     

                                    </div>

                                    

                                    

                                 <?php }//end of level_three?>

                                 

                                    

                                  </div>

                                   

                                        

                                         



                            </div>

                        </div>                        

                       

                     

                     <?php }//end of level_one?>

 

                    </div>









                



                </div>



            </div>

        </div>

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
     window.addEventListener('load', function () {
            const container = document.getElementById('hv-container'); // your scrollable container
            const target = container.querySelector('#mainPerson .person'); // target element

            if (container && target) {
                const containerRect = container.getBoundingClientRect();
                const targetRect = target.getBoundingClientRect();

                const scrollTo = target.offsetLeft - (container.clientWidth / 2) + (target.offsetWidth / 2);
                container.scrollLeft = scrollTo;
            }
        });
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
