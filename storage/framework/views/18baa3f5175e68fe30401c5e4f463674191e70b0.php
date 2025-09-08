<div class="person">
    <?php ( $member_detail = $data['member_detail']); ?>
    <!-- <img class="avatar-xs rounded-circle" src="<?php echo e(asset('storage/app/public/upload/')); ?>/<?php echo e($member_detail->image); ?>" alt="banner image"/> -->

    <?php if($member_detail->is_paid==1): ?>
        <img class="avatar-xs rounded-circle" src="<?php echo e(asset('public/tree/images/rank/green.png')); ?>" alt="banner image"/>
    <?php elseif($member_detail->is_paid==0): ?>
        <img class="avatar-xs rounded-circle" src="<?php echo e(asset('public/tree/images/rank/red.png')); ?>" alt="banner image"/>
    <?php endif; ?>
    <p class="name">
        <a style="color: #000000;" href="<?php echo e(route('user.team').'/'.Crypt::encryptString($member_detail->id)); ?>"><?php echo e($member_detail->name); ?>/<b><?php echo e(sort_name); ?><?php echo e($member_detail->user_id); ?></b></a><br>
        Sponser ID: <b><?php echo e($member_detail->sponser_id); ?></b><br>
        Position: <b><?php if($member_detail->position==1): ?> Left <?php else: ?> Right <?php endif; ?></b>
    </p>
    <div class="detail-popup">
        <p><b>Mobile : </b><?php echo e($member_detail->phone); ?></p>
        <p><b>Rank : </b><?php echo e(@\App\Models\MemberModel::active_package($member_detail->rank)); ?></p>
        <p><b>Total Left : </b><?php echo e($member_detail->total_left_unpaid+$member_detail->total_left_paid); ?></p>
        <p><b>Total Right : </b><?php echo e($member_detail->total_right_unpaid+$member_detail->total_right_paid); ?></p>
        <p><b>Package : </b><?php echo e(@\App\Models\MemberModel::active_package($member_detail->id)->package_name); ?></p>
        <p><b>KYC : </b>
            <?php if($member_detail->kyc_step==1): ?>
            <span class="badge bg-success">KYC Complete</span>
            <?php endif; ?>

            <?php if($member_detail->kyc_step==2): ?>
            <span class="badge bg-info">KYC Under Review</span>
            <?php endif; ?>

            <?php if($member_detail->kyc_step==3): ?>
            <span class="badge bg-warning">KYC Rejected</span>
            <?php endif; ?>

            <?php if($member_detail->kyc_step==0): ?>
            <span class="badge bg-info">KYC Not Update</span>
            <?php endif; ?>
        </p>
    </div>    
</div><?php /**PATH D:\xamp\htdocs\projects\irshad\ayurvedicmlm\resources\views/user/team/team-card.blade.php ENDPATH**/ ?>