<?php $__env->startSection('content'); ?>
  <div class="row ">
    <div class="col-sm-12 col-lg-7">
      <div class="card bg-white border-0 shadow-sm rounded">

        <div class="card-body">
          <a class="btn btn-sm btn-primary float-right" href="<?php echo e(route('account_mapping')); ?>"><i
              class="fas fa-sitemap mr-1"></i> Account Mapping</a>
          <i class="fas fa-info-circle mr-1 text-info"></i>
          Below is a list of available accounts
        </div>

        <div class="card-body border-top">
          <div class="form-group mb-2">
            <input class="form-control form-control-sm search" type="text" placeholder="Type here to search">
          </div>
          <div class="list-group fetch-account-data">
            <?php if(isset($accountsList)): ?>
            <?php $__currentLoopData = $accountsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a class="list-group-item list-group-item-action">

                <div class="media-body">
                  <h6 class="mt-0 mb-1">
                    <?php if($row['account_type'] == 'Income' || $row['account_type'] == 'Other Income'): ?>
                      <small class="d-block text-success float-right mt-1"><?php echo e($row['account_type']); ?></small>
                    <?php else: ?>
                      <small class="d-block text-danger float-right mt-1"><?php echo e($row['account_type']); ?></small>
                    <?php endif; ?>
                    <strong class="mr-2"><?php echo e($row['account_num']); ?></strong>
                    <?php echo e($row['account_name']); ?>


                  </h6>
                </div>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
              Records not found.
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-lg-4">
    </div>
  </div>

  <script type="text/javascript">
    $('.search').keyup(function() {
       $.ajax({
        url: "<?php echo e(route('search_accounts')); ?>",
        type: 'POST',
        data: {
          search: $('.search').val(),
          "_token": "<?php echo e(csrf_token()); ?>",
        },

        success: function(response) {
          $('.fetch-account-data').html(response);
        }
      });
    })
  </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>