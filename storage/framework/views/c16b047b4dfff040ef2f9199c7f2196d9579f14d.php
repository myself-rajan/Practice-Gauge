<?php $__env->startSection('content'); ?>
  <div id="loginSlider" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="alert alert-danger" id="lblError" style="display:none">
          <span class="text"></span>
        </div>
        <div class="login-control-group">
          <h4 class="text-center">Sign Up</h4>
        </div>
        <!-- Login Panel -->
        <div class="login-control-group">
          <label class="d-block text-center mb-3">What type of account do you wish to create?</label>
          <div class="row">
            <div class="col">
              <a class="btn btn-light py-4 h-100 shadow rounded" href="<?php echo e(route('cpa_register')); ?>">
                <div>
                  <i class="fas fa-users fa-2x text-primary mb-2"></i>
                </div>
                <div>
                  <strong>Accountant</strong>
                </div>
                <p class="small">
                  You can manage multiple clients
                </p>
              </a>
            </div>
            <div class="col">
              <a class="btn btn-light py-4 h-100 shadow rounded" href="<?php echo e(route('signup_std')); ?>">
                <div>
                  <i class="fas fa-user-tie fa-2x text-primary mb-2"></i>
                </div>
                <div>
                  <strong>Standard</strong>
                </div>
                <p class="small">
                  You can manage only your company.
                </p>
              </a>
            </div>
          </div>
        </div>

       <!--  <div class="login-control-group text-let mb-3 clearfix">
          <a class="login-forgot-link float-right" href="#">Help</a>
        </div> -->
      </div>

      <div class="carousel-item">
        <div class="slide-spinner" style="height:350px;">
          <span></span>
          <div class="text-center">Please wait while we log you in</div>
        </div>
      </div>

      <div class="carousel-item">
        <!-- Select Company panel -->
        <div class="my-2">
          <h4 class="text-center mb-3">Hi, Astrid
            <small class="d-block text-muted">Select a company to continue</small>
          </h4>
          <form class="my-2 mb-0">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
            <input class="form-control" type="text" placeholder="Search..." placeholder="Search">
          </form>
          <div class="list-group rounded mb-4" style="display:none" id="secCompanyList">
            <button type="button" class="list-group-item list-group-item-action">
              Michael & Company, CPAs A PC
              <small class="d-block text-muted">Last used: Jan 16, 2019</small>
            </button>
            <button type="button" class="list-group-item list-group-item-action">
              Shannon S Russell DDS INC
              <small class="d-block text-muted">Last used: Jan 4, 2019</small>
            </button>
            <button type="button" class="list-group-item list-group-item-action">
              Dekken LLC
              <small class="d-block text-muted">Last used: Jan 1, 2019</small>
            </button>
            <button type="button" class="list-group-item list-group-item-action">
              Luther Corp
              <small class="d-block text-muted">Last used: Nov 18, 2018</small>
            </button>

            <button type="button" class="list-group-item list-group-item-action">
              Dunham Enterprises
              <small class="d-block text-muted">Last used: Sep 12, 2018</small>
            </button>
          </div>
        </div>

        <div style="margin-left:-25px;margin-right:-25px" class="py-3 text-center ">
          <button class="btn btn-outline-primary mb-1 shadow-sm" id="btnLogout">
            Logout
          </button>
        </div>

        <div style="margin-left:-25px;margin-right:-25px" class="py-3 text-center d-none">
          <button class="btn btn-light rounded mb-1 shadow-sm">
            Start a new company subscription
          </button>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('signVal'); ?>
<div class="login-footer-singup">
  Dont have an account? <a href="<?php echo e(route('login')); ?>">Sign In</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>