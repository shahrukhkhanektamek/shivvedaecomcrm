<?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-md-6 col-lg-4">
        <div class="card bg-none mb-3 mb-lg-4">
            <a  class="rounded height-140 mb-2 m-1" style="    overflow: hidden;">
                <img src="<?php echo e(Helpers::image_check(@$value->display_image)); ?>" class="w-100" alt="">
            </a>
            <div class="card-body pt-1">
                <a class="style-none">
                    <h6 class="text-theme-1 text-truncated mb-2"><?php echo e($value->name); ?></h6>
                </a>
                <div class="row gx-3 align-items-center">
                    <div class="col-auto w-100">
                        <a href="<?php echo e(url('/salesman/scan-face/'.Crypt::encryptString($value->id))); ?>" class="btn btn-success w-100 d-none"><i class="bi bi-cart"></i> Sale </a>

                        

                        <div class="col col-md-auto ms-md-auto mt-3 mt-md-0">
                            <div class="row gx-2 increamenter">
                                <div class="col-auto"><button type="button" class="btn btn-sm btn-square btn-theme rounded-circle plus-btn" data-id="<?php echo e($value->id); ?>" data-type="1">-</button></div>
                                <input type="number" class="form-control form-control-sm text-center width-50 qty" value="<?php echo e($value->qty); ?>">
                                <div class="col-auto"><button type="button" class="btn btn-sm btn-square btn-theme rounded-circle devide-btn" data-id="<?php echo e($value->id); ?>" data-type="2">+</button></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            

<div class="col-lg-12 pagination" >
    <?php echo e($data_list->links()); ?>

</div>
<?php /**PATH D:\xamp\htdocs\projects\irshad\shivvedaecomcrm\resources\views/salesman/product/table.blade.php ENDPATH**/ ?>