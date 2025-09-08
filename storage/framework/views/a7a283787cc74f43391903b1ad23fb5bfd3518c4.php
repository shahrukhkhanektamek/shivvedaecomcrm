<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>                    
                    <th>Member Detail</th>
                    <th>Amount</th>
                    <th>Payment Mode</th>
                    <th>ScreenShot</th>
                    <th>Date Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <b>Name: </b><?php echo e($value->user_name); ?><br>
                        <b>Mobile: </b><?php echo e($value->user_phone); ?><br>
                        <b>ID: </b><?php echo e($value->uuser_id); ?><br>
                    </td>                   
                    <td><?php echo e(Helpers::price_formate($value->amount)); ?></td>   
                    <td><span class="btn btn-sm btn-dark"><?php echo e($value->payment_mode); ?></span></td>   
                    <td><img src="<?php echo e(Helpers::image_check($value->image,'default.jpg')); ?>" class="img-responsive big-image" alt="User" style="width: 100px;height: 100px;cursor: pointer;"></td>                
                    <td><?php echo e(date("d M, Y h:i A",strtotime($value->add_date_time))); ?></td>                   
                    <td>
                        <?php if($value->status==1): ?>
                        <span class="badge bg-success">Approved</span>
                        <?php endif; ?>

                        <?php if($value->status==0): ?>
                        <span class="badge bg-info">New</span>
                        <?php endif; ?>

                        <?php if($value->status==2): ?>
                        <span class="badge bg-danger">Rejected</span>
                        <?php endif; ?>
                    </td>                    
                    <td>
                        <?php if($value->status==0): ?>
                        <a href="<?php echo e($data['back_btn']); ?>/update" data-id="<?php echo e(Crypt::encryptString($value->id)); ?>" data-status="1" class="btn btn-sm btn-success mb-1 approve-reject">Approve</a><br>
                        
                        <a href="<?php echo e($data['back_btn']); ?>/update" data-id="<?php echo e(Crypt::encryptString($value->id)); ?>" data-status="2" class="btn btn-sm btn-danger mb-1 approve-reject">Reject</a><br>

                        <?php else: ?>

                        <p>No Need Action!</p>

                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <!-- end table -->
    </div>
</div>


<div class="card pagination" >
    <?php echo e($data_list->links()); ?>

</div>
<?php /**PATH /home/u171934876/domains/developershahrukh.in/public_html/demo/irshad/shivveda/resources/views/admin/deposit/table.blade.php ENDPATH**/ ?>