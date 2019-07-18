 @extends('layouts.auth_layout')
  @section('content')
    <div class="registration-wrapper">
      <div class="card-body text-center">
        <i class="fas fa-check-circle text-success fa-3x mt-3">
        </i>
        <h3 class="mt-4">All Done</h3>
        <div class="my-2">Registration with Practice Gauge has been done</div>
      </div>

      <div class="card-footer text-center">
        <h5>We have sent a verification link with instructions to your email.
          <small class="d-block text-muted">Please make sure to also check your spam folder.</small>
          Please click here to <a href="{{route('login')}}">Sign In</a>
        </h5>
      </div>
    </div>
  @endsection

 
@section('signVal')
  <div class="login-footer-singup">
    Please click here to <a href="{{route('login')}}">Sign In</a>
  </div>
@endsection 

