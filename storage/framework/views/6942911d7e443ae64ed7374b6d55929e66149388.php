<div>
    <div class="table-responsive">
        <table class="table">
            <thead class="bg-success text-white">
                <tr>                    
                    <th scope="col">#</th>
                      <th scope="col">Amount</th>
                      <th scope="col">TDS</th>
                      <th scope="col">R. Wallet</th>
                      <th scope="col">Final Amount</th>
                      <th scope="col">Type</th>
                      <th scope="col">Date Time</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th scope="row"><?php echo e($key+1); ?></th>
                    <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($value->amount)); ?></span></td>
                    <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($value->tds_amount)); ?></span></td>
                    <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($value->wallet_amount)); ?></span></td>
                    <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($value->final_amount)); ?></span></td>
                    <td>
                        <?php if($value->type==1): ?>
                        <span class="btn btn-primary" data-type='1'>Direct Income</span>
                        <?php elseif($value->type==2): ?>
                        <span class="btn btn-primary" data-type='2'>Pair Income</span>
                        <?php elseif($value->type==3): ?>
                        <span class="btn btn-primary" data-type='3'>Downline Income</span>
                        <?php elseif($value->type==4): ?>
                        <span class="btn btn-primary" data-type='4'>Upline Income</span>
                        <?php elseif($value->type==5): ?>
                        <span class="btn btn-primary" data-type='5'>Rank Bonus Income</span>
                        <?php elseif($value->type==6): ?>
                        <span class="btn btn-primary" data-type='6'>Repurchase Income</span>
                        <?php endif; ?>
                    </td>
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
<?php /**PATH /home/shivved1/public_html/resources/views/user/earning/table.blade.php ENDPATH**/ ?>