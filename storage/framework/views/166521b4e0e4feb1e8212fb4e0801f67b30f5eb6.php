
  <table class="table table-sm" data-column-right=".2.3.4.5.">
    <thead class="bg-light">
      <tr>
        <th class="border-top-0">Month</th>

        <?php $__currentLoopData = $fetchData['year']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearKey => $yearVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>        
          <th class="border-top-0"><?php echo e($yearVal); ?></th>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $fetchData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthKey => $monthVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($monthKey != 'tatalSum' && $monthKey != 'year'): ?> 
          <tr>
            <td><?php echo e($monthKey); ?></td>
            <?php $__currentLoopData = $monthVal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearKey => $yearVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <td>$ <?php echo e(number_format($yearVal,2)); ?></td>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
          </tr>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
      <tr>
        <th class="border-secondary">Totals</th>
        <?php $__currentLoopData = $fetchData['tatalSum']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sKey => $sVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <th class="border-secondary"> $ <?php echo e(number_format($sVal,2)); ?></th>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tr>
    </tfoot>
  </table> 
</div>
