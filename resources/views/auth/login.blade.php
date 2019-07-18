@extends('layouts.auth_layout')

@section('content')
  <?php
    \Session::forget('new_practices_flow');
    \Session::forget('company')['qbo_connection'];
    /*echo "<pre>";
    print_r(\Session::all());
    echo "<pre>";exit();*/
  ?>
  <div id="loginSlider" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="alert alert-danger" id="lblError" style="display:none">
          <span class="text"></span>
        </div>
        <form method="POST" action="{{route('login')}}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          
          @if($errors->any())
            <div class="alert alert-danger" id="lblError">
              <span class="text">Incorrect email or password</span>
            </div>
          @endif
          @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{{ $message }}</strong>
            </div>
          @endif

        <!-- Login Panel -->
        <div class="login-control-group">
          <label>Email</label>
           <input id="email" type="text" class="login-control validate_blur" name="email" value="{{ old('email') }}" />
            <span id="validEmail"></span>
        </div>
        <div class="login-control-group">
          <label>Password</label>
            <input id="password" type="password" name="password" class="login-control validate_blur" />
            <span id="passMsg"></span>
          <a class="login-forgot-link" href="{{route('forget_pass')}}">Forgot Password?</a>
        </div>
        <div class="login-control-group text-let">
          <button class="btn btn-primary" id="btnLogin">Login</button>
          <a class="login-forgot-link float-right d-none" href="#">Help</a>
        </div>
        <div class="text-center section-or d-none">
          <span>
            OR
          </span>
        </div>
        <div style="margin-left:-25px;margin-right:-25px;" class="py-3 text-center d-none">
          <button class="btn btn-light rounded mb-1 shadow-sm">
            <img src="{{asset('/img/btn_google_light_normal_ios.svg')}}" /> Sign in with Google
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

    </div>
  </div>

  
  @section('signVal')
    <div class="login-footer-singup">
      Dont have an account? <a href="{{route('register')}}">Sign Up</a>
    </div>
  @endsection

<script type="text/javascript">
  $('#btnLogin').click(function () {
    var email      = $('#email').val();
    var password   = $('#password').val(); 
    var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

    if(email == "" || password == ""){

      if(password == ""){
        $("#password").css("border-color", 'red');
         $("#passMsg").text('Please enter the password.');
          $("#passMsg").css('color', 'red'); 
      }else{
        $("#password").css("border-color", ''); 
         $("#passMsg").text('');
          $("#passMsg").css('color', ''); 
      }

      if(email == ""){
          $("#email").css("border-color", 'red');
          $("#validEmail").text('Please enter the email.');
          $("#validEmail").css('color', 'red');
      }else{
          $("#email").css("border-color", '');
          $("#validEmail").text('');
          $("#validEmail").css('color', '');
      }
      return false;
    }
  })


  $('.validate_blur').keyup(function (){
     var email      = $('#email').val();
     var password   = $('#password').val(); 


    if(email != ""){      
          $("#email").css("border-color", '');
          $("#validEmail").text('');
          $("#validEmail").css('color', '');
    }

    if(password != ""){
          $("#password").css("border-color", '');
         $("#passMsg").text('');
          $("#passMsg").css('color', ''); 
    }

  })
  $("#email").blur(function(){
    var email      = $('#email').val();
    var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
     if(emailRegax.test(email) == false)
      {
        $("#email").css("border-color", 'red');
        $("#validEmail").text('Please provide valid email address');
        $("#validEmail").css('color', 'red');
        $("#btnLogin").addClass('alpha-disabled');
      }else
      {
        $("#email").css("border-color", '');
        $("#validEmail").text('');
        $("#validEmail").css('color', '');
        $("#btnLogin").removeClass('alpha-disabled');
      } 
       
  });
</script>

@endsection