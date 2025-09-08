<?php echo $__env->make('user.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>




<style>
    .success-container {
        max-width: 500px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        color: #28a745;
    }
    p {
        font-size: 18px;
    }
    .total-amount {
        font-size: 20px;
        font-weight: bold;
        margin: 10px 0;
    }
    .btn {
        display: inline-block;
        padding: 10px 15px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
    }
</style>
  
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
            <div class="card-body row ">
                
                <div class="success-container">
                    <h2>🎉 Order Placed Successfully! 🎉</h2>
                    <p>Thank you for your purchase.</p>
                    <p class="total-amount">Total Amount: <span id="order-total"><?php echo e(Helpers::price_formate($orders->final_amount)); ?></span></p>
                    <a href="<?php echo e(route('user.product.list')); ?>" class="btn">Continue Shopping</a>
                </div>

            </div>
          </div>
        </div>



      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->










<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\htdocs\projects\irshad\shivveda.in\resources\views/user/checkout/success.blade.php ENDPATH**/ ?>