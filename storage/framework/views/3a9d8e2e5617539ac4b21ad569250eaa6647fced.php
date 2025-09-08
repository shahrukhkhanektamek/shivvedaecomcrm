<div>
    <div class="table-responsive">
        <table class="table">
            <thead class="bg-success text-white">
                <tr>                    
                    <th scope="col">Member ID.</th>
                      <th scope="col">Name</th>
                      <th scope="col">Position</th>
                      <th scope="col">Join Date</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($value->user_id); ?></td>
                    <td><?php echo e($value->name); ?></td>
                    <td><?php if($value->position==1): ?>Left <?php else: ?> Right <?php endif; ?></td>
                    <td><?php echo e(date("d M, Y h:i A", strtotime($value->add_date_time))); ?></td>
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
<?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/user/direct-team/table.blade.php ENDPATH**/ ?>