<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pratice Gauge - Registration</title>

    <!-- Animate.CSS CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
      integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <!-- jQuery-UI Framework -->
    <script src="{{asset('js/jQueryUI/jquery-ui.min.js')}}"></script>
    <link href="{{asset('js/jQueryUI/jquery-ui.structure.min.css')}}" rel="stylesheet" />
    <link href="{{asset('js/jQueryUI/jquery-ui.min.css')}}" rel="stylesheet" />

    <!-- Popper CSN (Bootstrap 4 requires this to work)-->
    <script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>

    <!-- Bootstrap CDN -->
    <link href="https://bootswatch.com/4/cosmo/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Cosmo from bootswatch -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
      integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
      crossorigin="anonymous"></script>


    <!-- Bootstrap Overrides - JONES -->
    <link href="{{asset('css/bootstrap.overrides.css')}}" rel="stylesheet" />
    <link href="{{asset('css/bootstrap.xxl.css')}}" rel="stylesheet" />

    <!-- Strawberry CSS - JONES -->
    <link href="{{asset('css/strawberry.css')}}" rel="stylesheet" />
    <link href="{{asset('css/strawberry.min.768.css')}}" rel="stylesheet" />
    <!-- Responsive CSS -->
    <link href="{{asset('css/strawberry.utilities.css')}}" rel="stylesheet" />
    <!-- Utility CSS -->

      <!----Common Css-->
     <link href="{{asset('css/common.css')}}" rel="stylesheet" />


    <link href="{{asset('css/app_modules/integration.css')}}" rel="stylesheet" />
    <link href="{{asset('css/app_modules/connected.list.css')}}" rel="stylesheet" />
    <link href="{{asset('css/app_modules/registration.css')}}" rel="stylesheet" />
    <script src="{{ asset('/js/strawberry.core.js') }}"></script>
    <script src="{{ asset('/js/strawberry.js') }}"></script>

      <script src="{{asset('js/views/registration.js')}}"></script>

    <script src="{{asset('js/views/signup_cpa.js')}}"></script>
    <script>
         //Globally Set Web URL
      var l      = window.location;
      var WEBURL = l.protocol + "//" + l.host ;

        //Ajax Setup Token
      $(function () { 
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        })
      });
    </script>
  </head>

  <body>
   <div class="registration-wrapper">
      <div class="registration-panel">
          <div class="card border-0 mt-0 mb-4 mb-lg-0 mt-lg-0">
            <div class="card-body border-bottom">
              <div class="row">
                <div class="col-4 col-fhd-3">
                  <img class="img-fluid" src="{{asset('/img/logo.svg')}}" />
                </div>
            </div>
          </div>
             @yield('content')
        </div>
      </div>
    <!-- <div class="registration-footer-panel">
      <a href="#">Terms & Conditions</a>
      <a href="#">Privacy Policy</a>
    </div> -->
  </body>
</html>