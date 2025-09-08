<?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-3 mt-4">
        <div class="products-list-card card">
            <div class="products-image"><img class="img-thumbnail" src="<?php echo e(Helpers::image_check(@$value->display_image)); ?>"></div>
            <div class="products-list-detail">
                <h1><?php echo e($value->title); ?></h1>
                <div class="products-button">
                    <!-- <a href="#" class="btn btn-success add-to-cart" data-id="<?php echo e($value->id); ?>">Add to cart</a> -->

                </div>                
            </div>
        </div>
    </div>





<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            

<div class="col-lg-12 pagination" >
    <?php echo e($data_list->links()); ?>

</div>
<?php /**PATH /home/shivved1/public_html/resources/views/user/reward/table.blade.php ENDPATH**/ ?>