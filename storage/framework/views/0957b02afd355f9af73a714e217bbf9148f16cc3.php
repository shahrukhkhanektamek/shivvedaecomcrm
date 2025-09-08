<?php echo $__env->make('user.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1 class="text-black"><?php echo e($data['page_title']); ?></h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo e(url('user/dashboard')); ?>">Home</a></li>
        <?php $__currentLoopData = $data['pagenation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li class="sub-bread"><i class="fa fa-angle-right"></i> <?php echo e($value); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ol>
    </div>
    
    <!-- Main content -->
    <div class="content">
      <div class="row">

        <div class="col-lg-12 mt-3">
          <div class="card card-outline">
            <div class="card-header bg-blue">
              <h5 class="text-white m-b-0"><?php echo e($data['title']); ?> History</h5>
            </div>
            <div class="card-body">
              <table width="100%" style="font-size: 14px; color:#000; text-align:center;" border="1">
                      <thead>
                        <tr style="background-color:#069; color:#fff">
                          <th>Target</th>
                          <th>Current</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                                                <tr>
                          <td scope="row">5 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>
                        <tr>
                          <td scope="row">New 25 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>
                        <tr>
                          <td scope="row">New 50 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>
                        <tr>
                          <td scope="row">New 100 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>
                        <tr>
                          <td scope="row">New 250 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>
                        <tr>
                          <td scope="row">New 500 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>
                        <tr>
                          <td scope="row">New 1000 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>
                        <tr>
                          <td scope="row">New 2000 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>
                        <tr>
                          <td scope="row">New 5000 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>
                        <tr>
                          <td scope="row">New 15000 Pair</td>
                          <td>0 Pair</td>
                          <td><span style="color:red">Pending</span></td>
                        </tr>

                      
                      </tbody>
                    </table>
                         
            </div>
          </div>
        </div>

      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->







<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/user/reward/index.blade.php ENDPATH**/ ?>