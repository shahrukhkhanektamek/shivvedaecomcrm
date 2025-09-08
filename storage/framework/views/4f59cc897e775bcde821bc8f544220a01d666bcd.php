<?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-3 mt-4">
        <div class="products-list-card card">
            <div class="products-image"><img class="img-thumbnail" src="<?php echo e(Helpers::image_check(@$value->display_image)); ?>"></div>
            <div class="products-list-detail">
                <h1><?php echo e($value->name); ?></h1>
                <div class="price-section">
                    <h1 style="text-decoration: line-through;">MRP: <?php echo e(Helpers::price_formate($value->real_price)); ?></h1>
                    <h1>DP: <?php echo e(Helpers::price_formate($value->sale_price)); ?></h1>
                </div>
                    <h1>BV: <?php echo e($value->bv); ?></h1>
                <div class="products-button">
                    <div class="add-cart-btn-group">
                        <button type="button" class="plus-btn" data-id="<?php echo e($value->id); ?>" data-type="1">-</button>
                        <input type="number" value="<?php echo e($value->qty); ?>">
                        <button type="button" class="devide-btn" data-id="<?php echo e($value->id); ?>" data-type="2">+</button>
                    </div>

                </div>                
            </div>
        </div>
    </div>





<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            

<div class="col-lg-12 pagination" >
    <?php echo e($data_list->links()); ?>

</div>
<?php /**PATH /home/shivved1/public_html/resources/views/user/product/table.blade.php ENDPATH**/ ?>