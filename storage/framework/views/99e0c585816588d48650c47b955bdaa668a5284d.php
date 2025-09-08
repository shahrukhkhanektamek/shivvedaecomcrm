<div>
    <div class="table-responsive">
        <table class="table">
            <thead class="bg-success text-white">
                <tr>                    
                    <th scope="col">#</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Payment Mode</th>
                      <th scope="col">Date</th>
                      <th scope="col">Status</th>
                      <th scope="col">Message</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th scope="row"><?php echo e($key+1); ?></th>
                      <td><?php echo e(Helpers::price_formate($value->amount)); ?></td>
                      <td><span class="btn btn-sm btn-dark"><?php echo e($value->payment_mode); ?></span></td>   
                      <td><?php echo e(date("d M, Y", strtotime($value->add_date_time))); ?></td>
                      <td>
                        <?php if($value->status==0): ?>
                            <span class="badge bg-danger">Pending</span>
                        <?php elseif($value->status==1): ?>
                            <span class="badge bg-success">Accepted</span>
                        <?php elseif($value->status==2): ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php endif; ?>
                      </td>
                      <td><?php echo e($value->message); ?></td>                  
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
<?php /**PATH /home/shivved1/public_html/resources/views/user/deposit/table.blade.php ENDPATH**/ ?>