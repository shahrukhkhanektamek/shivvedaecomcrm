<?php echo $__env->make('user.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php ($orderProducts = DB::table("order_products")->where("order_id",$order->order_id)->get()); ?>
  
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
              <h5 class="text-white m-b-0">Shipping Address 

                <?php if($order->status==0): ?>
                  <span class="badge btn btn-default">Confirm</span>
                <?php elseif($order->status==1): ?>
                  <span class="badge btn btn-info">Proccess</span>
                <?php elseif($order->status==2): ?>
                  <span class="badge btn btn-dark">Shipped</span>
                <?php elseif($order->status==3): ?>
                  <span class="badge btn btn-success">Delivered</span>
                <?php elseif($order->status==4): ?>
                  <span class="badge btn btn-danger">Cancel</span>
                <?php endif; ?>
              </h5>
            </div>
            <div class="card-body row ">
              <div class="row" style="padding:0 15px;width: 100%;">
                    
                    <div class="col-md-4">
                      <p><strong>Name: </strong><?php echo e($order->name); ?></p>
                    </div>

                    <div class="col-md-4">
                      <p><strong>Email: </strong><?php echo e($order->email); ?></p>
                    </div>

                    <div class="col-md-4">
                      <p><strong>Phone: </strong><?php echo e($order->phone); ?></p>
                    </div>

                    <div class="col-md-4">
                      <?php ($states = DB::table('states')->where('id',$order->state)->first()); ?>
                      <p><strong>State: </strong><?php echo e(@$states->name); ?></p>
                    </div>

                    <div class="col-md-4">
                      <p><strong>City: </strong><?php echo e($order->city); ?></p>
                    </div>

                    <div class="col-md-4">
                      <p><strong>Address: </strong><?php echo e($order->address); ?></p>
                    </div>

                   

                   

              </div>
            </div>
          </div>
        </div>




        <div class="col-lg-12 mt-3">
          <div class="card card-outline">
            <div class="card-header bg-blue">
              <h5 class="text-white m-b-0">Order Products </h5>
            </div>
            <div class="card-body row ">
              <div class="info-box">
                  <div class="table-responsive">
                    <table class="table">
                        <thead class="bg-success text-white">
                            <tr>                    
                                <th scope="col">#</th>
                                <th scope="col">Product name</th>
                                <th scope="col">Price</th>
                                <th scope="col">QTY</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php $__currentLoopData = $orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                              <td><?php echo e($key+1); ?></td>
                              <td><?php echo e($value->name); ?></td>
                              <td><?php echo e(Helpers::price_formate($value->price)); ?></td>
                              <td><?php echo e($value->qty); ?></td>
                            </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                          <tr>
                            <td></td>
                            <td></td>
                            <th>Sub Total</th>
                            <th><?php echo e(Helpers::price_formate($order->amount)); ?></th>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                            <th>Gst</th>
                            <th><?php echo e(Helpers::price_formate($order->gst)); ?></th>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                            <th>Total Amount</th>
                            <th><?php echo e(Helpers::price_formate($order->final_amount)); ?></th>
                          </tr>

                        </tbody>
                      </table>
                    <!-- end table -->
                </div>
              </div>
            </div>
          </div>
        </div>


        






      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->










<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/user/my-order/view.blade.php ENDPATH**/ ?>