<div>
    <div class="table-responsive">
        <table class="table">
            <thead class="bg-success text-white">
                <tr>                    
                    <th scope="col">#</th>
                      <th scope="col">Order Date</th>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Mobile</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th scope="row"><a href="<?php echo e(route('user.my-order.view')); ?>/<?php echo e($value->order_id); ?>">#<?php echo e($value->order_id); ?></a></th>
                    <td><?php echo e(date("d M, Y h:i A", strtotime($value->add_date_time))); ?></td>
                    <th scope="row"><?php echo e($value->name); ?></th>
                    <th scope="row"><?php echo e($value->email); ?></th>
                    <th scope="row"><?php echo e($value->phone); ?></th>
                    <td><span class="text-danger text-bold"><?php echo e(Helpers::price_formate($value->amount)); ?></span></td>
                    <td>
                        <?php if($value->status==0): ?>
                        <span class="badge btn btn-default">Confirm</span>
                        <?php elseif($value->status==1): ?>
                        <span class="badge btn btn-info">Proccess</span>
                        <?php elseif($value->status==2): ?>
                        <span class="badge btn btn-dark">Shipped</span>
                        <?php elseif($value->status==3): ?>
                        <span class="badge btn btn-success">Delivered</span>
                        <?php elseif($value->status==4): ?>
                        <span class="badge btn btn-danger">Cancel</span>
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
<?php /**PATH /home/u171934876/domains/developershahrukh.in/public_html/demo/irshad/shivveda/resources/views/user/my-order/table.blade.php ENDPATH**/ ?>