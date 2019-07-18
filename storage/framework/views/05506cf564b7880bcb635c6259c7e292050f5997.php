<div class="list-group rounded mb-4" id="company-content">
  <?php if(isset($companies) && sizeof($companies) > 0): ?>
  <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <button type="button" class="list-group-item" onclick="companyRedirect('<?php echo e($company->id); ?>')" >
      	<?php echo e($company->name); ?>

        <?php if($search_from == 'auth'): ?>
        		<?php if(isset(Session::get('company')['company_id']) && Session::get('company')['company_id'] == $company->id ): ?>
        		<span class="text-success float-right">(Current)</span>
        		<?php elseif($company->qbo_connection == 0): ?>
        		<i class="float-right far fa-dot-circle text-danger animated pulse infinite" data-toggle="tooltip" title="This company has problesm syncing to QuickBooks"></i>
        		<?php endif; ?>
        <?php endif; ?>
        <small class="text-muted d-block font-italic">Last used: <?php echo e(date('M d, Y', strtotime($company->updated_at))); ?></small>
    </button>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
</div>