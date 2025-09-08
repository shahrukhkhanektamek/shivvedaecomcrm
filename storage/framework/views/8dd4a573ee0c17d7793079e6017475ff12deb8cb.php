<?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    

    <li class="list-group-item px-2">
        <div class="row gx-3 align-items-center">
            <div class="col-auto">
                <figure class="avatar avatar-50 coverimg rounded" style="background-image: url(<?php echo e(Helpers::image_check(@$value->image)); ?>);">
                    <img src="<?php echo e(Helpers::image_check(@$value->image)); ?>" alt="" style="display: none;">
                </figure>
            </div>
            <div class="col-9 col-md-5">
                <p class="mb-0 text-truncated"><?php echo e($value->product_name); ?></p>
                <!-- <p class="small text-secondary">ID: RT15246630</p> -->
            </div>
            

            <div class="col col-md-auto ms-md-auto mt-3 mt-md-0">
                <div class="row gx-2 increamenter">
                    <div class="col-auto"><button type="button" class="btn btn-sm btn-square btn-theme rounded-circle plus-btn" data-id="<?php echo e($value->product_id); ?>" data-type="1">-</button></div>
                    <input type="number" class="form-control form-control-sm text-center width-50 qty" value="<?php echo e($value->qty); ?>">
                    <div class="col-auto"><button type="button" class="btn btn-sm btn-square btn-theme rounded-circle devide-btn" data-id="<?php echo e($value->product_id); ?>" data-type="2">+</button></div>
                </div>
            </div>



            <div class="col-auto text-end mt-3 mt-md-0 d-none">
                <h6 class="mb-0">$ 65.00</h6>
                <p class="small text-secondary">1 Item</p>
            </div>
        </div>
    </li>



<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
<?php /**PATH D:\xamp\htdocs\projects\irshad\shivvedaecomcrm\resources\views/salesman/cart/table.blade.php ENDPATH**/ ?>