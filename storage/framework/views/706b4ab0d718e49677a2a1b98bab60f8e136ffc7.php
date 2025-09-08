<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>                    
                    <th scope="col">Image</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <img class="img-thumbnail" style="width: 250px;" src="<?php echo e(Helpers::image_check($value->image)); ?>" alt="banner image"/>
                    </td>
                    <td><?php echo Helpers::active_inactive($value->status); ?></td>
                    <td>
                        <div class="d-flex gap-2">

                            <div class="edit">
                                <a href="<?php echo e($data['edit_btn_url'].'/'.Crypt::encryptString($value->id)); ?>" class="btn btn-sm btn-success edit-item-btn">Edit</a>
                            </div>
                            <div class="remove">
                                <a href="<?php echo e($data['delete_btn_url'].'/'.Crypt::encryptString($value->id)); ?>" class="btn btn-sm btn-danger remove-item-btn">Delete</a>
                            </div>
                        </div>
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
<?php /**PATH /home/shivved1/public_html/resources/views/admin/home-banner/table.blade.php ENDPATH**/ ?>