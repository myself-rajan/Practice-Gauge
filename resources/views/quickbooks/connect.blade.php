@extends('layouts.app_layout')

@section('content')

<div id="navData" data-title="QuickBooks Integration - SmartBooks" data-page-header="QuickBooks Online Integration"></div>

<div class="workspace p-4" id="workspace" style="transition: all 0.3s ease 0s; transform: none; opacity: 1;"><div id="navData" data-title="QuickBooks Integration - My Practice Gauge" data-page-header="QuickBooks Online Integration"></div>
<link href="/css/app_modules/integration.css" rel="stylesheet">
<script src="/js/views/qbo_integration.js"></script>

<section id="secConnect">
  <div class="row" id="div1">
    <div class="col-lg-12 col-xl-10 col-xxl-8 col-fhd-7">
      <div class="card bg-white shadow-sm rounded border-0">
        <div class="card-body">
          <label>Please wait while we connect to QuickBooks Online</label>

          <div class="waiting qb mb-4">
            <img class="primary" src="/img/logo.svg">
            <span class="_1"></span>
            <span class="_2"></span>
            <span class="_3"></span>
            <span class="_4"></span>
            <img class="external" src="/img/qb_3.png">
          </div>

          <div class="text-muted pt-3">
            A new window has opened. Please login to your QuickBooks account to integrate with My Practice Gauge.<br>
            <a  href="javascript:doQuickbooksLogin();" data-href = "{{ $qbo_url }}" target="_blank" class="text-primary qbo_connect_url">Click here</a> if the window hasn't opened.
          </div>
        </div>
      </div>


    </div>
  </div>
</section>

<script type="text/javascript">
  /*$(document).ready(function() {
    location.href = $('.qbo_url').attr('href');
  });*/
  function doQuickbooksLogin() {
    var qbo_url = $('.qbo_connect_url').data('href');
    window.open(qbo_url, '_blank',"width=800,height=740");
  }



  $(document).ready(function(){
    var check_error = getQueryParam('error');

    doQuickbooksLogin();

    if(check_error == 'true'){
      strawberry.toast.show('QuickBooks file error', 'danger');
    }
  })
  
</script>

@endsection