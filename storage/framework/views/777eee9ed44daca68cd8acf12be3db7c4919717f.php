<table class="table table-sm">
  <thead class="bg-light">
    <tr data-column-center=".2.3.4.5.6.7.8">
      <th class="border-top-0 align-middle" rowspan="2" style="width:40%">Expenses</th>
      <?php $__currentLoopData = $categoryBarData['year']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearKey => $yearData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <th class="border-top-0 border-right" colspan="2"><?php echo e($yearData); ?></th>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <th class="border-top-0" colspan="2">Optimum <?php echo e($categoryBarData['YrSelect']); ?></th>

    </tr>

    <tr data-column-center=".1.2.3.4.5.6.7.8.9.10">
      <?php $__currentLoopData = $categoryBarData['year']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearKey => $yearData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <th>$</th>
      <th >%</th>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <th>$</th>
      <th class="align-right">%</th>
    </tr>
  </thead>
  <tbody data-column-right=".2.3.4.5.6.7.8.9.10.11.12">
    

    <?php $__currentLoopData = $categoryBarData['label']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cKey => $cValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>

          <?php if($cValue == 'Fees Collected' || $cValue == 'Dental Supplies' || $cValue == 'Lab Fees' || $cValue == 'Marketing' || $cValue == 'Occupancy'): ?>

            <td> <?php echo e($cValue); ?></td>
          
            <?php $__currentLoopData = $categoryBarData['data'][$cKey]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdKey => $cdValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              
              <td>$<?php echo e(number_format((float)$cdValue['totalVal'],2)); ?></td>
              <td ><?php echo e(number_format((float)$cdValue['percentage'],2)); ?>%</td>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          
              <td>$<?php echo e(isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00'); ?></td>
              <td class="text-right"><?php echo e(isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00'); ?>%</td>

          <?php endif; ?>
       
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <tr>
        <td>Employee Costs</td>
        <?php $__currentLoopData = $categoryBarData['totalEmployeeCost']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdKey => $cdValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          
        <td> $<?php echo e(number_format((float)$cdValue['amount'],2)); ?> </td>
        <td > <?php echo e(number_format((float)$cdValue['percentage'],2)); ?> %</td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <td>$<?php echo e(isset($categoryBarData['totalEmployeeCostOptimum'][$categoryBarData['YrSelect']]['amount']) ? number_format((float)$categoryBarData['totalEmployeeCostOptimum'][$categoryBarData['YrSelect']]['amount'],2) : '0.00'); ?></td>
        <td class="text-right"><?php echo e(isset($categoryBarData['totalEmployeeCostOptimum'][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['totalEmployeeCostOptimum'][$categoryBarData['YrSelect']]['percentage'],2) : '0.00'); ?>%</td>
      </tr>


      <?php $__currentLoopData = $categoryBarData['label']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cKey => $cValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <?php ($dispLabel = ''); ?>
          <?php if($cValue == 'Associate Wages' || $cValue == 'Assistant Wages' || $cValue == 'Hygienist Wages' || $cValue == 'Clerical Salaries' || $cValue == 'Taxes & Benefits' || $cValue == 'General & Admin'): ?>

            <?php if($cValue == 'General & Admin'): ?>
              <?php ($dispLabel = ''); ?>
            <?php else: ?>
              <?php ($dispLabel = 'padding-left:35px'); ?>
            <?php endif; ?>
          
            <td style="<?php echo e($dispLabel); ?>"> <?php echo e($cValue); ?></td>
        
            <?php $__currentLoopData = $categoryBarData['data'][$cKey]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdKey => $cdValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              
              <td>$<?php echo e(number_format((float)$cdValue['totalVal'],2)); ?></td>
              <td ><?php echo e(number_format((float)$cdValue['percentage'],2)); ?>%</td>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          
              <td>$<?php echo e(isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00'); ?></td>
              <td class="text-right"><?php echo e(isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00'); ?>%</td>

          <?php endif; ?>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


      <tr>
        <th>Total Operating Expenses</th>
        <?php $__currentLoopData = $categoryBarData['totalSumCat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdKey => $cdValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          
        <th> $<?php echo e(number_format((float)$cdValue['amount'],2)); ?> </th>
        <th > <?php echo e(number_format((float)$cdValue['percentage'],2)); ?> %</th>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <th>$<?php echo e(isset($categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['amount']) ? number_format((float)$categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['amount'],2) : '0.00'); ?></th>
        <th class="text-right"><?php echo e(isset($categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['percentage'],2) : '0.00'); ?>%</th>
      </tr>

    <!-- <tr>
      <th>Total Operating Expenses</th>
      <th>$104,055</th>
      <th >100%</th>
      <th>$111,377</th>
      <th >100%</th>
      <th>$103,003</th>
      <th>100%</th>
      <th>$104,055</th>
      <th >100%</th>
      <th>$104,055</th>
      <th >100%</th>
    </tr> -->
    <?php $__currentLoopData = $categoryBarData['label']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cKey => $cValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <?php if($cValue == 'Net Operating Income'): ?>
            <td style="background-color: lightgreen"><?php echo e($cValue); ?></td>
        
          <?php $__currentLoopData = $categoryBarData['data'][$cKey]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdKey => $cdValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <td style="background-color: lightgreen">$<?php echo e(number_format((float)$cdValue['totalVal'],2)); ?></td>
            <td  style="background-color: lightgreen"><?php echo e(number_format((float)$cdValue['percentage'],2)); ?>%</td>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
            <td style="background-color: lightgreen">$<?php echo e(isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00'); ?></td>
            <td class="text-right"  style="background-color: lightgreen"><?php echo e(isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ?  number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00'); ?>%</td>
        <?php endif; ?>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <tr>
      <td colspan="20" class="bg-light">Non-Operating Expenses</td>
    </tr>

    <?php $__currentLoopData = $categoryBarData['label']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cKey => $cValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <?php if($cValue == 'Depreciation Expense' || $cValue == 'Amortization Expense' || $cValue == 'Interest Expense' || $cValue == 'Officer & Family Wages' || $cValue == 'Gain or Loss on Sales' || $cValue == 'Miscellaneous Income'): ?>
            <td><?php echo e($cValue); ?></td>
        
          <?php $__currentLoopData = $categoryBarData['data'][$cKey]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdKey => $cdValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <td>$<?php echo e(number_format((float)$cdValue['totalVal'],2)); ?></td>
            <td ><?php echo e(number_format((float)$cdValue['percentage'],2)); ?>%</td>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
            <td>$<?php echo e(isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ?  number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00'); ?></td>
            <td class="text-right"><?php echo e(isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ?  number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00'); ?>%</td>
        <?php endif; ?>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <tr>
        <td>Net Income</td>
        <?php $__currentLoopData = $categoryBarData['netIncome'][$cKey]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdKey => $cdValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <td>$<?php echo e(number_format((float)$cdValue['totalVal'],2)); ?></td>
        <td><?php echo e(number_format((float)$cdValue['percentage'],2)); ?>%</td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
        <td>$<?php echo e(isset($categoryBarData['netIncomeOptimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['netIncomeOptimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00'); ?></td>
        <td class="text-right"><?php echo e(isset($categoryBarData['netIncomeOptimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ?  number_format((float)$categoryBarData['netIncomeOptimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00'); ?>%</td>
      </tr>
  </tbody>
</table>



    