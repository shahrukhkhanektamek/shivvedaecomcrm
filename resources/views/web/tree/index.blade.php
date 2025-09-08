<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
<link rel="stylesheet" href="<?=url('public/tree/hic');?>/hierarchy-view.css">
<link rel="stylesheet" href="<?=url('public/tree/hic');?>/main.css">
<style>
    .management-hierarchy .person > img{ width:45px; height:50px}
    .bl{color:#900;}
    .bc{color:#060;}
    .management-hierarchy .person > p.name {text-transform: capitalize;}
    .detail-popup {
        position: absolute;
        z-index: 999;
        width: 200px;
        background: white;
        padding: 5px 5px;
        top: 50%;
        left: 99%;
        border-radius: 5px;
        display: none;
    }
.person:hover .detail-popup {
    display: block;
}
.management-hierarchy .person {
    position: relative;
}
.management-hierarchy .person > p.name a {
    text-decoration: none;
}
.detail-popup p {
    font-size: 12px;
    margin: 0;
    color: #3BAA9D !important;
}
b {
    font-weight: 500;
    color: #595959;
}
body {
    padding: 0;
    margin: 0;
}
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
                <div class="hv-item">
                    <div class="hv-item-parent" id="mainPerson">
                        <?php
                            $data['member_detail'] = $member_detail;
                            echo \Illuminate\Support\Facades\View::make('web/tree/team-card',compact('data'))->render();
                        ?>
                    </div>
<div class="hv-item-children">
                    <?php foreach($memberlist as $level_one){ ?>    
                        <div class="hv-item-child">
                            <div class="hv-item">
                                <div class="hv-item-parent">
                                    <?php 
                                        $data = $level_one;
                                        echo \Illuminate\Support\Facades\View::make('web/tree/team-card',compact('data'))->render();
                                    ?>
                                </div>
                                <div class="hv-item-children">
                                 <?php foreach($level_one['L_3'] as $three){ ?>  
                                     <div class="hv-item-child">
                                        <?php 
                                            $data = $three;
                                            echo \Illuminate\Support\Facades\View::make('web/tree/team-card',compact('data'))->render();
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



<script>
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
