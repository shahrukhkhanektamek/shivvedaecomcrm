<?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    

    


    <a href="<?php echo e(route('salesman.my-order.view')); ?>/<?php echo e($value->order_id); ?>" class="list-group-item px-2">
        <div class="row gx-3 align-items-center mb-3">
            <div class="col">
                <span class="badge badge-light text-bg-theme-1 theme-grey">Completed</span>
            </div>
            <div class="col-auto">
                <div class="btn btn-sm btn-square btn-link"><i class="bi bi-arrow-right"></i></div>
            </div>
        </div>
        <div class="row gx-3 align-items-center">
            <div class="col">
                <!-- <h6 class="mb-0">$ 185.00</h6> -->
                <p class="small text-secondary">#<?php echo e($value->order_id); ?></p>
                <h6 class="mb-0"><?php echo e($value->name); ?></h6>
                <h6 class="mb-0"><?php echo e($value->email); ?></h6>
                <h6 class="mb-0"><?php echo e($value->phone); ?></h6>
            </div>
            <div class="col">
                <p class="mb-0"><?php echo e(date("d M, Y", strtotime($value->add_date_time))); ?></p>
                <!-- <p class="small text-secondary">3 Items</p> -->
            </div>
            <div class="col-auto avatar-group">
                <figure class="avatar avatar-40 coverimg rounded" data-bs-toggle="tooltip" title="Sofa Single Coco">
                    <img src="assets/img/ecommerce/image-5.jpg" alt="">
                </figure>
                <figure class="avatar avatar-40 coverimg rounded" data-bs-toggle="tooltip" title="High Heels Footwear">
                    <img src="assets/img/ecommerce/image-7.jpg" alt="">
                </figure>
            </div>
        </div>
    </a>



<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div class="col-lg-12 pagination" >
    <?php echo e($data_list->links()); ?>

</div>

            
<?php /**PATH D:\xamp\htdocs\projects\irshad\shivvedaecomcrm\resources\views/salesman/my-order/table.blade.php ENDPATH**/ ?>