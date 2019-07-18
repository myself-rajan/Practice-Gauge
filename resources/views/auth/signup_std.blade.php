@extends('layouts.auth_layout')
@section('content')
  <div id="loginSlider" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="alert alert-danger" id="lblError" style="display:none">
          <span class="text"></span>
        </div>

        <div class="login-control-group">
          <h4 class="text-center">Standard Sign Up</h4>
        </div>
        <!-- Login Panel -->
        <form method="POST" action="" id="stdUser">
          @csrf
          <input type="hidden" name="user_type" id="user_type" value="6">
          <div class="login-control-group">
            <label>Email</label>
            <input id="email" type="text" class="form-control validate_blur" name="email" onblur="checkEmail()">
            <span id="validEmail"></span>
            <span id="CheckEmail_val"></span>                       
          </div>
          <div class="login-control-group">
            <label>Organization Name</label>
            <input id="organization_name" type="text" class="form-control validate_blur" name="organization_name">
            <span id="validOrganizationName"></span>
            <span id="CheckOrganizationNameVal"></span>                       
          </div>
          <div class="login-control-group">
            <label>Set Password</label>
            <input id="password" type="password"  class="form-control validate_blur" name="password" onkeyup="CheckPasswordStrength(this.value)" />
            <br>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <span id="password_strength"></span>       
          </div>

          <div class="login-control-group">
            <label>Confirm Password</label>
            <input id="cpassword" type="password"  class="form-control validate_blur" name="cpassword" onblur="Validate()"/> 
            <span id="confirm_pass"></span>   
          </div>

          <div class="login-control-group text-let">
            <button type="button" class="btn btn-sm btn-primary " id="btnSubmit" href="">Sign Up</button>
            <a class="login-forgot-link float-right d-none" href="#">Help</a>
          </div>

          <div class="text-center section-or d-none">
            <span>
              OR
            </span>
          </div>

          <div style="margin-left:-25px;margin-right:-25px;" class="py-3 text-center d-none">
            <button class="btn btn-light rounded mb-1 shadow-sm">
                 <img src="{{asset('/img/btn_google_light_normal_ios.svg')}}" /> Sign Up with Google
            </button>
          </div>
      </div>
        </form>

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

  @section('signVal')
  <div class="login-footer-singup">
    Dont have an account? <a href="{{route('login')}}">Sign In</a>
  </div>
  @endsection

  <script type="text/javascript">
    $('#btnSubmit').click(function () {
      var email      = $('#email').val();
      var password   = $('#password').val(); 
      var cpassword = $('#cpassword').val(); 
      var organization_name  = $('#organization_name').val(); 

      var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

      if(email == "" || password == "" || organization_name == "" || cpassword == ""){
        if(email == ""){
          $("#email").css("border-color", 'red');
        }else{
          if(emailRegax.test(email) == false){
            $("#email").css("border-color", 'red');
            $("#validEmail").text('');
            $("#validEmail").css('color', 'red');
          }else{
            $("#email").css("border-color", '');
            $("#validEmail").text('');
            $("#validEmail").css('color', '');
          } 
        }

        if(password == ""){
          $("#password").css("border-color", 'red'); 
        }else{
          $("#password").css("border-color", ''); 
        }

        if(cpassword == ""){
          $("#cpassword").css("border-color", 'red'); 
        }else{
          $("#cpassword").css("border-color", ''); 
        }

        if(organization_name == ""){
          $("#organization_name").css("border-color", 'red'); 
        }else{
          $("#organization_name").css("border-color", ''); 
        }

        return false;
      }else{
        submitStd();
      }
    })

    function checkEmail(){
      var email = $('#email').val();
      $.ajax({
        url: '{{route("check_email_std")}}',
        type: 'GET',
        data: {'email' : email},
        success: function(response) {
          if(response > 0){
            $('#CheckEmail_val').html('Email is already available.Please try another.');
            $("#CheckEmail_val").css('color', 'red');
            //$("#btnSubmit").addClass('alpha-disabled');
          }else{
             $('#CheckEmail_val').html('');
             $("#CheckEmail_val").css('color', '');
             //$("#btnSubmit").removeClass('alpha-disabled');
          }
          
        }
      });
    }

    function submitStd(){
     var email          = $('#email').val();
     var emailRegax     = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
     if(emailRegax.test(email) == false) {
        $("#email").css("border-color", 'red');
        $("#CheckEmail_val").text('Please provide valid email address');
        $("#CheckEmail_val").css('color', 'red');
        //$("#btnSubmit").addClass('alpha-disabled');
     }else{
        $("#email").css("border-color", '');
        $("#CheckEmail_val").text('');
        $("#CheckEmail_val").css('color', '');
        //$("#btnSubmit").removeClass('alpha-disabled');
         var _btn = $("#btnSubmit");
        _btn.text('Saving... Please wait');
        _btn.addClass('btn-processing');
        _btn.prop('diasbled', true);
        var stdUser = $('#stdUser').serialize();
        $.ajax({
          url:'{{route("save_reg_std")}}',
          type:'POST',
          data:stdUser,
            success: function(response) {
              if(response > 0){
                  window.location  = WEBURL+'/register/sent_success';
              }else{
                window.location  = WEBURL+'/auth/login';
              }  
            }
        });
     }
    }

    $('.validate_blur').keyup(function (){
      var email      = $('#email').val();
      var password   = $('#password').val(); 
      var cpassword = $('#cpassword').val(); 
      var organization_name  = $('#organization_name').val(); 
      // if(email != ""){      
      //       $("#email").css("border-color", '');
      //       $("#validEmail").text('');
      //       $("#validEmail").css('color', '');
      // }

      if(password != ""){
        $("#password").css("border-color", '');
        $("#passMsg").text('');
        $("#passMsg").css('color', ''); 
      }

      if(cpassword != ""){
        $("#cpassword").css("border-color", '');
      }

      if(organization_name != ""){
        $("#organization_name").css("border-color", ''); 
      }
    })

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
      regex.push("[$@$!%*#?&]"); //Special Character.

      var passed = 0;

      //Validate for each Regular Expression.
      for (var i = 0; i < regex.length; i++) {
          if (new RegExp(regex[i]).test(password)) {
              passed++;
          }
      }

      //Validate for length of Password.
      if (passed > 2 && password.length > 8) {
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
              $("#btnSubmit").addClass("alpha-disabled");
              break;
          case 2:
              strength = "Good";
              color    = "darkorange";
              progressWidth    = "50%";
               $("#btnSubmit").removeClass("alpha-disabled");
              break;
          case 3:
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

    function Validate() {
      var password = document.getElementById("password").value;
      var confirmPassword = document.getElementById("cpassword").value;
      if (password != confirmPassword) {
           $("#cpassword").css("border-color", 'red');
           $("#confirm_pass").text('Confirm Password does not math');
           $("#confirm_pass").css('color', 'red'); 
           $("#btnSubmit").addClass('alpha-disabled');
           return false;
      }else{
           $("#cpassword").css("border-color", '');
           $("#confirm_pass").text('');
           $("#confirm_pass").css('color', ''); 
           $("#btnSubmit").removeClass('alpha-disabled');
      }
      return true;
    }
  </script>
@endsection