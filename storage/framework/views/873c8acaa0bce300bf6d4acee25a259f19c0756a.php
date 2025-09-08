<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
    <meta charset="utf-8" />
    <title><?php echo e($data['page_title']); ?> | <?php echo e(env("APP_NAME")); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Start Include Css -->
    <?php echo $__env->make('admin.headers.maincss', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- End Include Css -->

    <script src="<?php echo e(url('public/assetsadmin/')); ?>/libs/fullcalendar/index.global.min.js"></script>

    <style>
        .fc .fc-view-harness-active > .fc-view {
            inset: 0px;
            position: inherit;
            height: 500px;
        }
    </style>

</head>
<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- Start Include Header -->
        <?php echo $__env->make('admin.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- End Include Header -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0"><?php echo e($data['page_title']); ?></h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Home</a></li>
                                        <li class="breadcrumb-item active"><?php echo e($data['page_title']); ?>

                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body frm">
                                        <div class="row" style="align-items: center;">
                                            <div class="col-lg-12">
                                                <div class="" style="text-align: center;background: #00528f;border-radius: 5px;">
                                                       <div class="col-md-12">
                                                          <h2 style="margin: 10px 0 0 0;color: white;font-weight: 800;padding-top: 20px;"><?php echo e(sort_name.$row->user_id); ?></h2>
                                                       </div>
                                                       <div class="col-md-12">
                                                            <img src="<?php echo e(Helpers::image_check($row->image,'user.png')); ?>" id="ctl00_ContentPlaceHolder1_userimage" class="img-circle user-d-img" alt="User Avatar">
                                                       </div>
                                                       <div class="col-md-12">
<h2 id="ctl00_ContentPlaceHolder1_lblusername" style="font-size: 27px;margin: 0 0 10px 0;font-weight: 900;color: black;background: #ffffff;padding: 7px 0;text-transform: uppercase;width: fit-content;display: block;margin: 0px auto;margin-bottom: 10px;padding: 5px 10px;"><?php echo e($row->name); ?></h2>
                                                          
                                                          <!--<button id="btnCopy" onclick="copyToClipboard('#text')" class="btn btn-danger block" style="width: 100%;background: #febe4c !important;color: black;border: 0;margin-bottom: 15px;width: fit-content;border-radius: 0;">Click Referral Link</button>-->
                                                             
                                                          <!-- <button id="btnCopy" class="btn btn-danger block" style="width: 100%;background: #febe4c !important;color: black;border: 0;margin-bottom: 15px;width: fit-content;border-radius: 0;">Demp</button> -->

                                                          <button id="btnCopy" class="btn btn-danger block" style="width: 100%;background: #febe4c !important;color: black;border: 0;margin-bottom: 15px;width: fit-content;border-radius: 0;">Total Income: <?php echo e(Helpers::price_formate(@\App\Models\MemberModel::all_time_income($row->id))); ?></button>

                                                       </div>
                                                    </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Team</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(@\App\Models\MemberModel::totalTeam($row->id)); ?> Members</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Direct</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(@\App\Models\MemberModel::totalDirect($row->id)); ?> Members</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Matching</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(@\App\Models\MemberModel::calculatePairsForSponsor($row->id)['pair_count']); ?> Pair </h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Activation Date</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e($row->activate_date_time); ?> </h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>                                                
                                            </div>










                                            
                                        </div>
                                    </div>
                                </div>


                                


                                <!-- end card -->
                            </div>










                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>All Matching</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" style="align-items: center;">
                                            

                                            <div class="row">
                                                
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Matching</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(@\App\Models\MemberModel::totalTeam($row->id)); ?> Members</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Business</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(@\App\Models\MemberModel::totalTeam($row->id)); ?> Members</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Trade Business</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(@\App\Models\MemberModel::totalTeam($row->id)); ?> Members</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                                                             
                                            </div>










                                            
                                        </div>
                                    </div>
                                </div>


                                


                                <!-- end card -->
                            </div>




                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>All Income</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" style="align-items: center;">
                                            

                                            <div class="row">
                                                
                                                

                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Direct Income</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($row->id)->income1)); ?></h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                

                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pair Income</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($row->id)->income2)); ?></h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                

                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Downline Income</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($row->id)->income3)); ?></h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                

                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Upline Income</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($row->id)->income4)); ?></h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                

                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Rank Bonus Income</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(Helpers::price_formate(@\App\Models\MemberModel::getTypeAllIncome($row->id)->income5)); ?></h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                                                             
                                            </div>










                                            
                                        </div>
                                    </div>
                                </div>


                                


                                <!-- end card -->
                            </div>








                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>All Clubs</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" style="align-items: center;">
                                            

                                            <div class="row">

                                                
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Silver Club Team</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">Left : 6 / Right: 0</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Gold Club Team</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">Left : 6 / Right: 0</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Platinum Club Team</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">Left : 6 / Right: 0</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Diamond Club Team</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">Left : 6 / Right: 0</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Crown Club Team</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">Left : 6 / Right: 0</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="card card-animate">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Crown Club Team</p>
                                                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                                                        <div>
                                                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">Left : 6 / Right: 0</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                                                             
                                            </div>










                                            
                                        </div>
                                    </div>
                                </div>


                                


                                <!-- end card -->
                            </div>











                            <!-- end col -->
                            <div class="card p-3">
                                <div class="col-lg-12">
                                    <div id='calendar-container'>
                                        <div id='calendar'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   
                </div>
            </div>
            <!-- End Page-content -->
            <!-- Start Include Footer -->
            <?php echo $__env->make('admin.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!-- End Include Footer -->
        </div>
    </div>
    <!-- END layout-wrapper -->
    <!-- Start Include Script -->
    <?php echo $__env->make('admin.headers.mainjs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- End Include Script -->




   <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                  },
                events: function(fetchInfo, successCallback, failureCallback) {
                    // Perform an AJAX request to fetch events
                    var start = fetchInfo.startStr;
                    var end = fetchInfo.endStr;

                    fetch("<?php echo e($data['earning_calendar']); ?>"+'?start=' + start + '&end=' + end + "&id=<?php echo e($data['user_id']); ?>")
                        .then(response => response.json())
                        .then(data => {
                            successCallback(data); // Pass the event data to FullCalendar
                        })
                        .catch(error => {
                            console.error('Error fetching events:', error);
                            failureCallback(error); // Handle the error
                        });
                },
                datesSet: function(info) {
                    var startDate = info.start;
                    console.log('View start date:', startDate.toISOString());
                }
            });

            calendar.render();
        });
    </script>




</body>
</html><?php /**PATH C:\xampp\htdocs\projects\ayurvedicmlm\resources\views/admin/user/dashboard.blade.php ENDPATH**/ ?>