<div class="list-group rounded mb-4"  id="secCompanyList">
 <?php $__currentLoopData = $organization; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <a type="button" class="list-group-item" 
  href="<?php echo e(route('select_company').'?org_id='.$org['id']); ?>" style="text-align: center;text-decoration: none;color: black">
     <?php echo e($org['company_name']); ?>

  </a>
 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>