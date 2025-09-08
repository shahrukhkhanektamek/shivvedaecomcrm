<div>
    <div class="table-responsive">
        <table class="table">
            <thead class="bg-success text-white">
                <?php if(empty($data['level'])): ?>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Members</th>
                        <th scope="col">RBV</th>
                        <th scope="col">Amount</th>
                        <th scope="col">TDS</th>
                        <th scope="col">Final Amount</th>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">RBV</th>
                        <th scope="col">Amount</th>
                        <th scope="col">TDS</th>
                        <th scope="col">Final Amount</th>
                    </tr>
                <?php endif; ?>
            </thead>
            <tbody>

                <?php if(empty($data['level'])): ?>
                    <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>Level: <?php echo e($key); ?></td>
                            <td><a href="<?php echo e($data['route'].'/level-member/'.$key); ?>"><?php echo e($member['totalMembers']); ?></a></td>
                            <td><?php echo e($member['total_rbv']); ?></td>
                            <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($member['amount'])); ?></span></td>
                            <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($member['tds_amount'])); ?></span></td>
                            <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($member['final_amount'])); ?></span></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php else: ?>

                    <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($member['name'])): ?>
                            <tr>
                                <td><?php echo e($member['member_id']); ?></td>
                                <td><?php echo e($member['name']); ?></td>
                                <td><?php echo e($member['rbv']); ?></td>
                                <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($member['amount'])); ?></span></td>
                                <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($member['tds_amount'])); ?></span></td>
                                <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($member['final_amount'])); ?></span></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php endif; ?>
            </tbody>
        </table>
        <!-- end table -->
    </div>
</div>



<?php /**PATH /home/shivved1/public_html/resources/views/user/lavel/table.blade.php ENDPATH**/ ?>