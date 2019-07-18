<?php $__env->startSection('content'); ?>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <div id="loginSlider" class="carousel slide">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="alert alert-danger" id="lblError" style="display:none">
            <span class="text"></span>
          </div>
        <form method="POST" action=""  id="new_pass_frm">
          <?php echo csrf_field(); ?> 
          <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button> 
                  <strong>Email Verified successfully</strong>
          </div>
        
          <input type="hidden" name="user_id" id="user_id" value="<?php echo e($id); ?>">
          <!-- Login Panel -->
          <div class="login-control-group">
            <label>New Password</label>
            <input id="password" type="password" class="login-control" name="password" value="" onkeyup="CheckPasswordStrength(this.value)" />
            <span id="validPass"></span>
            <br>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <span id="password_strength"></span>       
          </div>

          <div class="login-control-group">
            <label>Confirm Password</label>
            <input id="cpassword" type="password" name="cpassword" class="login-control" value="" />
            <span id="validCpass"></span>
          </div>

          <div class="login-control-group text-let">
            <button class="btn btn-primary" id="btnLogin" type="button">Save</button>
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
   
  var password   = $('#password').val(); 
  var cpassword      = $('#cpassword').val();
  var validator = $("#new_pass_frm").validate({
  rules: {
    password: "required",
    cpassword: {
        equalTo: "#password"
    }
  },
  messages: {
    password: "<div style='color:red'>Enter Password</div>",
    cpassword: "<div style='color:red'>Confirm Password is incorrect</div>"
  }
  });
  if (validator.form()) {
    submitNewPassword();
  }
  })
  function submitNewPassword(){
    var formNewPwd = $('#new_pass_frm').serialize();
    $.ajax({
      url:'<?php echo e(route("save_new_password")); ?>',
      type:'POST',
      data:formNewPwd,
      success: function(response) {
        if(response > 0){
            window.location  = WEBURL+'/login';
        }else{
            window.location  = WEBURL+'/register/failure';
        }        
      }
    });
  }
  function CheckPasswordStrength(password) {
    var password_strength = document.getElementById("password_strength");

    //TextBox left blank.
    if (password.length == 0) {
        password_strength.innerHTML = "";
        $('.progress-bar').css('width', '0%'); 
        return;
    }

    //Regular Expressions.
    var regex = new Array();
    regex.push("[A-Z]"); //Uppercase Alphabet.
    regex.push("[a-z]"); //Lowercase Alphabet.
    regex.push("[0-9]"); //Digit.
    //regex.push("[$@$!%*#?&]"); //Special Character.
    
    var passed = 0;
    //Validate for each Regular Expression.
    for (var i = 0; i < regex.length; i++) {
        if (new RegExp(regex[i]).test(password)) {
            passed++;
        }
    }

    //Validate for length of Password.
    if(password.length > 7){
      passed++;
    }

    //Display status.
    var color    = "";
    var strength = "";        
    var progressWidth    = "0%"; 
    switch (passed) {
        case 0:
        case 1:
            strength = "Weak";
            color    = "red";
            progressWidth    = "20%";
            $("#btnLogin").addClass("alpha-disabled");
            break;
        case 2:
            strength = "Weak";
            color    = "red";
            progressWidth    = "20%";
            $("#btnLogin").addClass("alpha-disabled");
            break;
        case 3:
            strength = "Good";
            color    = "darkorange";
            progressWidth    = "50%";
            $("#btnLogin").removeClass("alpha-disabled");
            break;
        case 4:
            strength = "Strong";
            color    = "green";
            progressWidth    = "80%";
            break;
        case 5:
            strength = "Very Strong";
            color    = "darkgreen";
            progressWidth    = "100%";
            break;
        default:
            progressWidth    = "0%";
    }

    password_strength.innerHTML   = strength;
    password_strength.style.color = color; 

    //Progress Bar
    $('.progress-bar').css('background-color', color);      
    $('.progress-bar').css('width', progressWidth);      
  }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>