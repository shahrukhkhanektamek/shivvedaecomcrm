<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>                    
                    <th>User ID</th>
                    <th>Image </th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email </th>
                    <th>Pancard </th>
                    <th>Bank Holder Name </th>
                    <th>Bank Name </th>
                    <th>Account Number </th>
                    <th>IFSC </th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(sort_name.$value->user_id); ?></td>                    
                    <td>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <img class="avatar-xs rounded-circle"
                                    onerror="this.src='<?php echo e(asset('storage/app/public/upload/default.jpg')); ?>'"
                                    src="<?php echo e(asset('storage/app/public/upload/')); ?>/<?php echo e($value->image); ?>" alt="banner image"/>
                            </div>
                        </div>
                    </td>                   
                    <td><?php echo e($value->name); ?></td>                    
                    <td><?php echo e($value->phone); ?></td>                    
                    <td><?php echo e($value->email); ?></td>                    
                    <td><?php echo e($value->pan); ?></td>                    
                    <td><?php echo e($value->bank_holder_name); ?></td>                    
                    <td><?php echo e($value->bank_name); ?></td>                    
                    <td><?php echo e($value->account_number); ?></td>                    
                    <td><?php echo e($value->ifsc); ?></td>                    
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
<?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/admin/bank/table.blade.php ENDPATH**/ ?>