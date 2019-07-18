  

  <?php $__env->startSection('content'); ?>
   <?php $id = isset($_GET['id'])?$_GET['id']:'';?>
  <div class="registration-wrapper">
    <div class="registration-panel">
      <div class="card border-0 mt-0 mb-4 mb-lg-0 mt-lg-0">
        <div class="card-body border-bottom text-center">
          <div class="row">
          <!--   <div class="col-4 col-fhd-3">
              <img class="img-fluid m-auto" src="asset('img/logo.svg')" />
            </div> -->
          </div>
        </div>

        <div class="card-body text-center">
          <i class="fas fa-check-circle text-success fa-3x mt-3">
          </i>
          <h3 class="mt-4">Verified</h3>
          <div class="my-5">Your email has been successfully verified.
          </div>
        </div>

        <div class="card-footer text-center">
          <h5><a href="<?php echo e(URL::to('/register/verifyStd?confirmation_code='.$id)); ?>">Click here</a> to continue with the registration process...
          </h5>
        </div>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>