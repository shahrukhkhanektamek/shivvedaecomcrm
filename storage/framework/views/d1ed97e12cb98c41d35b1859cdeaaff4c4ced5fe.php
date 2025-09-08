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
        <div class="col-lg-6" style="margin: 0 auto;">
          <div class="card card-outline">
            <!-- <div class="card-header bg-blue">
              <h5 class="text-white m-b-0">Basic Example</h5>
            </div> -->
            <div class="card-body">



              
              <form class="form row form_data" action="<?php echo e($data['submit_url']); ?>" method="get" enctype="multipart/form-data" id="form_data_submit" novalidate>
                <?php echo csrf_field(); ?>

                <input type="hidden" name="amount" value="<?php echo e($data['amount']); ?>">


                <div class="col-md-12">
                  <div class="qr-div">
                    <h2>Pay <br><span><?php echo e(Helpers::price_formate($data['amount'])); ?></span></h2>
                    <p>Pay to given address. <br>Send Payment Proof for approval to company.</p>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=upi://pay?pa=<?php echo e(urlencode($data['upi'])); ?>&am=<?php echo e($data['amount']); ?>&cu=INR&size=300x300" alt="UPI QR Code for Payment">
                  </div>

                  <div class="upi-copy-section">
                    <span class="upi-copy-text" onclick="copyToClipboard('<?php echo e($data['upi']); ?>')"><?php echo e($data['upi']); ?> <i class="fa fa-copy"></i></span>
                  </div>
                </div>



                <div class="col-md-12">
                  <div class="form-group">
                    <label>Payment Mode</label>
                    <div class="input-group">
                      <div class="input-group-addon"><i class="fa fa-circle-o"></i></div>
                      <select class="form-select mb-3" aria-label="Default select example" name="placement" id="placement" required>
                        <option value="" >Select</option>
                        <option value="PhonePe">PhonePe</option>
                        <option value="Gpay">Gpay</option>
                        <option value="AmazonPe">AmazonPe</option>
                        <option value="PayTm">PayTm</option>
                        <option value="Other UPI">Other UPI</option>
                      </select>
                    </div>
                  </div>
                </div>


                <div class="col-md-12">
                  <div class="form-group">
                    <label>Upload Payment Screenshot</label>
                    <div class="input-group">
                      <label class="custom-file center-block block w-100">
                        <input type="file" id="file" class="custom-file-input" required name="image">
                        <span class="custom-file-control"></span>
                      </label>
                    </div>
                  </div>
                </div>
                
                
                <div class="col-md-12">
                  <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
                </div>
                
              </form>


             
            </div>
          </div>
        </div>








       



      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->

<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\projects\ayurvedicmlm\resources\views/user/deposit/pay.blade.php ENDPATH**/ ?>