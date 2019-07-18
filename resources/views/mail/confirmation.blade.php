extends('layouts.auth_layout')
@section('content')
  <div class="registration-wrapper">
    <div class="registration-panel">
    <div class="card border-0 mt-0 mb-4 mb-lg-0 mt-lg-0">
      <div class="card-body border-bottom text-center">
        <div class="row">
          <div class="col-4 col-fhd-3">
            <img class="img-fluid m-auto" src="assets/img/logo.svg" />
          </div>

        </div>

      </div>

      <div class="card-body text-center">
        <i class="fas fa-check-circle text-danger fa-3x mt-3">

        </i>
        <h3 class="mt-4">Invalid activation link.</h3>
        <div class="my-5">

        </div>
      </div>



      <div class="card-footer text-center">
        <h5>Please contact on Pratice gauge team.
         
        </h5>
      </div>

    </div>
    </div>
  </div>


  <div class="registration-footer-panel">
    <a href="#">Terms & Conditions</a>
    <a href="#">Privacy Policy</a>
  </div>
@endsection