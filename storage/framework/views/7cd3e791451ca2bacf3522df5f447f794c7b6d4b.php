<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Ticket ID.</th>
                    <th>Date Time</th>
                    <th>Subject</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    
                    
                    <td><?php echo e($value->ticket_id); ?></td>
                    <td><?php echo e($value->add_date_time); ?></td>
                    <td><?php echo e($value->subject); ?></td>                    
                    <td>
                        <div class="d-flex gap-2">
                            <div class="edit">
                                <a href="<?php echo e($data['view_btn_url'].'/'.Crypt::encryptString($value->id)); ?>" class="btn btn-sm btn-success edit-item-btn">View</a>
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
<?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/admin/support/table.blade.php ENDPATH**/ ?>