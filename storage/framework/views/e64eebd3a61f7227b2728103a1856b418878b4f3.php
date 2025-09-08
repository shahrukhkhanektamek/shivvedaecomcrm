<div>
    <div class="table-responsive">
        <table class="table">
            <thead class="bg-success text-white">
                <tr>                    
                    <th scope="col">#</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Date Time</th>
                      <th scope="col">Message</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th scope="row"><?php echo e($key+1); ?></th>
                      <td>
                            <?php if($value->type==1): ?>
                                <span class="text-green text-bold">+ <?php echo e(Helpers::price_formate($value->amount)); ?></span>
                            <?php endif; ?>
                            <?php if($value->type==2): ?>
                                <span class="text-danger text-bold">- <?php echo e(Helpers::price_formate($value->amount)); ?></span>
                            <?php endif; ?>
                        </td>
                      <td><?php echo e(date("d M, Y h:i A", strtotime($value->add_date_time))); ?></td>
                      <td><?php echo $value->message; ?></td>
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
<?php /**PATH /home/u171934876/domains/developershahrukh.in/public_html/demo/irshad/shivveda/resources/views/user/wallet/table.blade.php ENDPATH**/ ?>