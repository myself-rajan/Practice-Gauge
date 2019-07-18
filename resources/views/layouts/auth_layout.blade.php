<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Practice Gauge - Login</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
      crossorigin="anonymous">

    <!-- Bootstrap CDN -->
    <link href="https://bootswatch.com/4/cosmo/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Cosmo from bootswatch -->
    <!----Common Css-->
     <link href="{{asset('css/common.css')}}" rel="stylesheet" />


    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous"></script>

    <!-- Popper CSN (Bootstrap 4 requires this to work)-->
    <script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>

    <!-- Bootstrap CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
      crossorigin="anonymous"></script>

    <link href="{{asset('/css/strawberry.login.css')}}" rel="stylesheet" />
    <script src="{{asset('/js/views/login.js')}}"></script>
    <script src="{{ asset('/js/jQueryUI/jquery-ui.min.js') }}"></script>
    <!-- <script src="{{ asset('/js/jQuery_3x/jquery.min.js') }}"></script> -->
    <script type="text/javascript">
      //Globally Set Web URL
      var l      = window.location;
      var WEBURL = l.protocol + "//" + l.host ;

      //Ajax Setup Token
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
  <body style="overflow: hidden;">
    <div class="login-wrapper"> 
      <div class="login-panel">
        <div class="login-logo">
          <img class="sidebar-logo" src="{{asset('/img/logo.svg')}}" />
        </div>
          @yield('content')
      </div>
    
      <div class="login-footer-panel d-none">
        <a href="#">Terms & Conditions</a>
        <a href="#">Privacy Policy</a>
      </div>
      @yield('signVal');
    </div>
  </body>
</html>