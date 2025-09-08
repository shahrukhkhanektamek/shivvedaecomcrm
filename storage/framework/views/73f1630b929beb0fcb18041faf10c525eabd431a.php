<div>

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">

                <tr>                    

                    <th>Order Id</th>
                    <th>Branch</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email </th>
                    <th>Screenshot </th>
                    <th>Shipping Detail </th>
                    <th>Status </th>
                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php ($count = DB::table('orders')->where('user_id', $value->user_id)->count()); ?>
                <tr>

                    <td>#<?php echo e($value->order_id); ?></td>
                    <td><?php echo e($value->branch_name); ?></td>
                    <td><?php echo e($value->name); ?></td>

                    <td><?php echo e($value->phone); ?></td>                    

                    <td><?php echo e($value->email); ?></td>
                    <td><img src="<?php echo e(Helpers::image_check($value->screenshot,'default.jpg')); ?>" class="img-responsive big-image" alt="User" style="width: 100px;height: 100px;cursor: pointer;"></td> 

                    <td>
                        <strong>Name: </strong><?php echo e($value->name); ?><br>
                        <strong>Email: </strong><?php echo e($value->email); ?><br>
                        <strong>Phone: </strong><?php echo e($value->phone); ?><br>
                        <strong>Address: </strong><?php echo e($value->address); ?><br>
                        <strong>State: </strong><?php echo e(@DB::table('states')->where('id', $value->state)->first()->name); ?><br>
                        <strong>City: </strong><?php echo e($value->city); ?><br>
                        <strong>Pincode: </strong><?php echo e($value->pincode); ?><br>
                    </td>

                    <td>
                        <?php if($value->status==0): ?>

                        <span class="badge bg-primary">New</span>

                        <?php elseif($value->status==1): ?>

                        <span class="badge bg-info">Proccess</span>

                        <?php elseif($value->status==2): ?>

                        <span class="badge bg-dark">Shipped</span>

                        <?php elseif($value->status==3): ?>

                        <span class="badge bg-success">Delivered</span>

                        <?php elseif($value->status==4): ?>

                        <span class="badge bg-danger">Cancel</span>

                        <?php endif; ?>

                        <?php if($count==1): ?> 
                        <span class="badge bg-primary">First Order</span>
                        <?php endif; ?>

                    </td>                    

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

<?php /**PATH D:\xamp\htdocs\projects\irshad\shivveda.in\resources\views/admin/order/table.blade.php ENDPATH**/ ?>