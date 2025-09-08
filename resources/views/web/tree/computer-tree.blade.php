




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



              

       