<?php echo $__env->make('user.headers.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php ($cartDetail = \App\Models\MemberModel::cartDetail(@$row->user_id)); ?>

  
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
              <h5 class="text-white m-b-0">Ticket Detail 
                <?php if($row->status==0): ?>
                <span class="badge btn btn-default">Pending</span>
                <?php elseif($row->status==2): ?>
                <span class="badge btn btn-info">Proccess</span>
                <?php elseif($row->status==1): ?>
                <span class="badge btn btn-success">Complete</span>
                <?php elseif($row->status==3): ?>
                <span class="badge btn btn-danger">Reject</span>
                <?php endif; ?>
               </h5>
            </div>
            <div class="card-body row ">
              <div class="info-box">
                  <form class="form row form_data" action="<?php echo e($data['submit_url']); ?>" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                    <?php echo csrf_field(); ?>                

                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Subject</label>
                        <div class="input-group">
                          <input class="form-control" placeholder="Name" type="text" name="subject" value="<?php echo e($row->subject); ?>" required readonly>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Detail</label>
                        
                          <textarea name="message" class="form-control" rows="10" readonly><?php echo e($row->message); ?></textarea>
                          <script>CKEDITOR.replace( 'message' );</script>
                        
                      </div>
                    </div>

                    <!-- <div class="col-md-12">
                      <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
                    </div> -->



                  </form>
              </div>
            </div>
          </div>
        </div>






      </div>
      
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->










<?php echo $__env->make('user.headers.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u171934876/domains/developershahrukh.in/public_html/demo/irshad/shivveda/resources/views/user/support/view.blade.php ENDPATH**/ ?>