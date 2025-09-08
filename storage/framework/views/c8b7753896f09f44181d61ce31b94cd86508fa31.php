<div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    
                    <th>User Name</th>
                    <th>Bank Name</th>
                    <th>Bank Holder Name</th>
                    <th>Bank Account No. </th>
                    <th>IFSC Code </th>
                    <th>Phone </th>
                    <th>Amount </th>
                    <!-- <th>Income Date </th>
                    <th>Payout Date </th> -->
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php ($user = \App\Models\User::where('id',$value->member_id)->select('bank_holder_name','account_number','ifsc','rg_mobile','bank_name','name','user_id')->first()); ?>
                <tr>                   
                    <th>
                        <?php echo e(@$user->name); ?><br>
                        Affiliate ID.:- <?php echo e($user->user_id); ?>

                    </th>
                    <th><?php echo e(@$user->bank_name); ?></th>
                    <th><?php echo e(@$user->bank_holder_name); ?></th>
                    <th><?php echo e(@$user->account_number); ?></th>
                    <th><?php echo e(@$user->ifsc); ?></th>
                    <th><?php echo e(@$user->rg_mobile); ?></th>
                    <th><?php echo e(Helpers::price_formate(@$value->final_amount)); ?></th>                    
                    
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
<?php /**PATH /home/u171934876/domains/developershahrukh.in/public_html/demo/irshad/shivveda/resources/views/admin/payout-report/table.blade.php ENDPATH**/ ?>