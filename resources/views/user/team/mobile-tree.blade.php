




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



              

       