 
  <?php $__env->startSection('content'); ?>
    <div class="registration-wrapper">
      <div class="card-body text-center">
        <i class="fas fa-check-circle text-success fa-3x mt-3">
        </i>
        <h3 class="mt-4">All Done</h3>
        <div class="my-3">Mail has been sent successfully. 
          <p>We are redirecting to account mapping screen in <span id="time_interval" style="color: red"></span> Second</p></div>
      </div>

      <div class="card-footer text-center">
        <h5>
          <small class="d-block text-muted">Please make sure to also check your spam folder.</small>
        </h5>
      </div>
    </div>

    <script type="text/javascript">
      $(document).ready(function(){
        window.seconds = 4;
        var myinterval = setInterval(function(){ window.seconds = window.seconds - 1;
          $('#time_interval').text(window.seconds);

          if(window.seconds == 1) {
            clearInterval(myinterval);
             window.location.href= WEBURL+'/account_mapping';
          }
        }, 1000);
      });
    </script>
  <?php $__env->stopSection(); ?>

 
<?php $__env->startSection('signVal'); ?>

  <div class="login-footer-singup">
    Dont have an account? <a href="<?php echo e(route('register')); ?>">Sign Up</a>
  </div>
 <?php $__env->stopSection(); ?> 




<?php echo $__env->make('layouts.auth_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>