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
                    <th>PanCard </th>

                    <th>Phone </th>

                    <th>Amount </th>
                    <th>TDS </th>
                    <th>R. Wallet </th>
                    <th>Final Amount </th>

                    <!-- <th>Income Date </th>

                    <th>Payout Date </th> -->

                </tr>

            </thead>

            <tbody>

            <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>



            <?php ($user = \App\Models\User::where('id',$value->member_id)->select('bank_holder_name','account_number','ifsc','rg_mobile','bank_name','name','user_id','kyc_step','is_paid')->first()); ?>

                <tr>                   

                    <th>

                        <?php echo e(@$user->name); ?><br>

                        Affiliate ID.:- <?php echo e($user->user_id); ?><br>

                        <?php if($user->is_paid==1): ?>
                        <span class="badge bg-success">Paid</span>
                        <?php endif; ?>
                        <?php if($user->is_paid==0): ?>
                        <span class="badge bg-danger">UnPaid</span>
                        <?php endif; ?>

                        <br>

                        <?php if($user->kyc_step==1): ?>
                        <span class="badge bg-success">KYC Complete</span>
                        <?php endif; ?>

                        <?php if($user->kyc_step==2): ?>
                        <span class="badge bg-info">KYC Under Review</span>
                        <?php endif; ?>

                        <?php if($user->kyc_step==3): ?>
                        <span class="badge bg-warning">KYC Rejected</span>
                        <?php endif; ?>

                        <?php if($user->kyc_step==0): ?>
                        <span class="badge bg-info">KYC Not Update</span>
                        <?php endif; ?>

                    </th>

                    <th><?php echo e(@$user->bank_name); ?></th>

                    <th><?php echo e(@$user->bank_holder_name); ?></th>

                    <th><?php echo e(@$user->account_number); ?></th>

                    <th><?php echo e(@$user->ifsc); ?></th>
                    <th><?php echo e(@$user->pan); ?></th>

                    <th><?php echo e(@$user->rg_mobile); ?></th>

                    <th><?php echo e(Helpers::price_formate(@$value->amount)); ?></th>                    
                    <th><?php echo e(Helpers::price_formate(@$value->tds_amount)); ?></th>                    
                    <th><?php echo e(Helpers::price_formate(@$value->wallet_amount)); ?></th>                    
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

<?php /**PATH /home/shivved1/public_html/resources/views/admin/payout-report/table.blade.php ENDPATH**/ ?>