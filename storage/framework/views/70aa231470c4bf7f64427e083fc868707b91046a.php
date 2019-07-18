<?php $__env->startSection('content'); ?>
  <div id="loginSlider" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="alert alert-danger" id="lblError" style="display:none">
          <span class="text"></span>
        </div>
        <form method="POST" action=""  id="forget_pass">
          <?php echo csrf_field(); ?>
          <span id="msg"></span>
          <!-- Forget Password Panel -->
          <div class="login-control-group">
            <label>Email</label>
             <input id="email" type="email" class="login-control validate_blur" name="email" onblur="forgetPassword()"/>
              <span id="CheckEmail_val"></span>
          </div>
         
          <div class="login-control-group text-let">
            <button class="btn btn-primary" id="btnLogin" type="button">Send</button>
            <!-- <a class="login-forgot-link float-right" href="#">Help</a> -->
          </div>
      </div>
      </form>
      <div class="carousel-item">
        <div class="slide-spinner" style="height:350px;">
          <span></span>
          <!-- <div class="text-center">Please wait while we log you in</div> -->
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $('#btnLogin').click(function () {
      var email      = $('#email').val();
      var password   = $('#password').val(); 
      var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
      if(email == ""){
        if(email == "") {
          $("#email").css("border-color", 'red');
          $("#CheckEmail_val").text('Please enter the email.');
          $("#CheckEmail_val").css('color', 'red');
        } else {
          if(emailRegax.test(email) == false) {
            $("#email").css("border-color", 'red');
            $("#CheckEmail_val").text('Please provide valid email address.');
            $("#CheckEmail_val").css('color', 'red');
          } else {
            $("#email").css("border-color", '');
            $("#CheckEmail_val").text('');
            $("#CheckEmail_val").css('color', '');
          } 
        }
        return false;
      }else{
        submitForgetPass();
      }
    })

    $('.validate_blur').blur(function (){
      var email = $('#email').val();
    
      if(email != ""){      
        $("#email").css("border-color", '');   
      }
    })

    function forgetPassword(){
      var email = $.trim($('#email').val());
      $.ajax({
        url: '<?php echo e(route("check_email_std")); ?>',
        type: 'GET',
        data: {'email':email},
        success: function(response) {
          if(response > 0){
            $('#CheckEmail_val').html('');
            $("#CheckEmail_val").css('color','');
          }else{
             $('#CheckEmail_val').html('This email id does not exits.');
             $("#CheckEmail_val").css('color', 'red');
          }    
        }
      });
    }

    function submitForgetPass(){
      var email = $('#email').val();
      $.ajax({
        url: '<?php echo e(route("send_request_pwd")); ?>',
        type: 'GET',
        data: {'email':email},
        success: function(response) {
          if(response != "") {
            $("#msg").html('<div class="alert alert-success alert-block" ><button type="button" class="close" data-dismiss="alert">×</button> <strong id="message">'+response.msg+'</strong>  </div>');
          } else {
            $("#msg").html('<div class="alert alert-danger alert-block" ><button type="button" class="close" data-dismiss="alert">×</button> <strong id="message">Does not send</strong>  </div>');
          }
        }
      });
    }
  </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>