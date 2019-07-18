<?php if(count($breadcrumbs)): ?>
    <ol class="breadcrumb">
        <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($breadcrumb->url && !$loop->last): ?>
                <?php if($breadcrumb->title == 'Home'): ?>
                 <li class="breadcrumb-item">
                    <a href="<?php echo e(route('home')); ?>"> <i
                  class="fas fa-home"></i></a></li>
                <?php else: ?>
                <li class="breadcrumb-item"><a href="<?php echo e($breadcrumb->url); ?>"><?php echo e($breadcrumb->title); ?></a></li>
                <?php endif; ?>
            <?php else: ?>
                <?php if($breadcrumb->title == 'Home'): ?>
                 <li class="breadcrumb-item active">
                     <i class="fas fa-home"></i>
                 </li>
                <?php else: ?>
                <li class="breadcrumb-item active">
                    <?php echo e($breadcrumb->title); ?>

                </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
<?php endif; ?>
