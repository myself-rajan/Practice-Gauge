<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TrueRev</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/ico/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/ico/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/ico/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/ico/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/ico/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/ico/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/ico/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/ico/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/ico/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('ico/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('ico/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('ico/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('ico/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('ico/manifest.json') }}">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="ico/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link type="text/css" rel="stylesheet" href="{{ asset('/css/openstyles.css') }}"  media="screen,projection"/>

    <!-- Start of truerev Zendesk Widget script -->
    <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=9542ee53-2dc5-437d-b593-bbb34a7c626e"> </script>
    <!-- End of truerev Zendesk Widget script -->

    <script type="text/javascript">
        window.zESettings = {
            webWidget: {
                helpCenter: { suppress: true },
                contactForm: { suppress: true }
            }
        };
    </script>
    <!--
        See for further details
            https://developer.zendesk.com/embeddables/docs/widget/zesettings
            https://support.zendesk.com/hc/en-us/articles/229167008-Advanced-customization-of-your-Web-Widget
    -->
</head>
<body class="pricing">
    <header class="fixed-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light white">
                <a class="navbar-brand" href="https://truerev.com/">
                    <img src="{{ asset('/images/img/logo.png') }}" class="d-inline-block align-top" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto icons">
                     <li class="nav-item social">
                         <a class="nav-link" href="https://angel.co/truerev" target="_blank"><img src="{{ asset('/images/img/angellist.svg') }}" style="width: 20px;" alt=""></a>
                     </li>
                     <li class="nav-item social">
                        <a class="nav-link" href="https://www.linkedin.com/company/truerev/"><img src="{{ asset('/images/img/linkedin.svg') }}" alt=""></a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link pricing-link" href="https://truerev.com/index.php#about-us">Pricing</a>
                        </li>
                </ul>
                <form class="form-inline">
                    @csrf
                    <a href="https://app.truerev.com/"><button class="btn btn-outline-dark" type="button">Login</button></a>
                </form>
            </div>
        </nav>
    </div>
</header>

<section class="business-model">
    <div class="container" style="margin-top: 20px;">
       <h2> <div class="col-sm-12 text-blue">QuickBooks account has been disconnected from TrueRev</div></h2>
        <br> <br>

        <p style="padding-left: 16px;"> Your QuickBooks account connection has been disconnected. </b> 
            You will no longer be able to send data directly to your QuickBooks account from TrueRev.
            If you’d like to re-connect TrueRev and your QuickBooks account, <a target="_blank" href="{{ route('integration_help') }}"> click here to view our help guide. </a> </p>

        <br>

        <p style="padding-left: 16px;"> Please contact <a href="mailto:support@truerev.com" class="text-blue"><i class="fa fa-envelope"></i> support@truerev.com </a> for any queries. Thank you!</p>


        
    </div>
</div>
</section>

<section class="business-model bellow">
    &nbsp;
</section>

<footer>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 w50">
                <img src="{{ asset('/images/img/logo-light.png') }}" class="logo-footer" alt="">
            </div>
            <div class="col-md-1 w50">
                <a href="https://angel.co/truerev"><img src="{{ asset('/images/img/angellist-dark.svg') }}" width="16" height="16" alt=""></a>
                <a href="https://www.linkedin.com/company/truerev/"><img src="{{ asset('/images/img/linkedin-dark.svg') }}" width="16" height="16" alt=""></a>
            </div>
            <div class="col-md-4 text-right">
                &copy; Copyright 2019 TrueRev. All rights reserved.
            </div>
            <div class="col-md-3 text-right">
                <a href="https://truerev.com/privacy.html">Privacy Policy</a>
                <a href="https://truerev.com/terms.html">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
<script src="/node_modules/jquery/dist/jquery.min.js"></script>
<script src="/node_modules/popper.js/dist/umd/popper.min.js"></script>
<script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('/js/openapp.js') }}"></script>
</body>
</html>
