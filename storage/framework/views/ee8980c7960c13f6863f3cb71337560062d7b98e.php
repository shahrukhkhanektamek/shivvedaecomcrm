<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>                    
                    <th>Image </th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <img class="avatar-xs rounded-circle"
                                    onerror="this.src='<?php echo e(asset('storage/app/public/upload/default.jpg')); ?>'"
                                    src="<?php echo e(asset('storage/app/public/upload/')); ?>/<?php echo e($value->image); ?>" alt="banner image"/>
                            </div>
                        </div>
                    </td>                   
                    <td>
                        <?php echo e($value->name); ?>

                        <br>

                        <?php if($value->kyc_step==1): ?>
                        <span class="badge bg-success">KYC Complete</span>
                        <?php endif; ?>

                        <?php if($value->kyc_step==2): ?>
                        <span class="badge bg-info">KYC Under Review</span>
                        <?php endif; ?>

                        <?php if($value->kyc_step==3): ?>
                        <span class="badge bg-warning">KYC Rejected</span>
                        <?php endif; ?>

                        <?php if($value->kyc_step==0): ?>
                        <span class="badge bg-info">KYC Not Update</span>
                        <?php endif; ?>

                    </td>                    
                    <td><?php echo e($value->phone); ?></td>                    
                    <td><?php echo e($value->email); ?></td>                    
                    <td><a href="<?php echo e($data['back_btn'].'/view/'.Crypt::encryptString($value->id)); ?>" class="btn btn-sm btn-success mb-1">View</a><br></td>                    
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
<?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/admin/kyc/table.blade.php ENDPATH**/ ?>