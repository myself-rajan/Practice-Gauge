@extends('layouts.app_layout')

@section('content')

<link href="/css/app_modules/integration.css" rel="stylesheet">
<script src="/js/views/qbo_integration.js"></script>

<section id="secConnect">
  <div class="row" id="div1">
    <div class="col-lg-12 col-xl-10 col-xxl-8 col-fhd-7">
      <div class="card-body">
        <div class="row">
          <div class="col-8 "><h5>QBO Company Name: {{ $qbo_connect->qbo_company_name}}</h5>
          </div>
        </div>
      </div>

      <div class="card bg-white shadow-sm rounded border-0">
        <div class="card-body">
          <div class="row">
            <div class="col-8 ">
              @if($qbo_connect->qbo_connection == 1)
                <div class="waiting qb mb-4">
                  <img class="primary" src="/img/logo.svg" />
                  <i class="fas fa-sync mx-4 animated tada"></i>
                  <img class="external" src="/img/qb_3.png" />
                </div>

                 <!-- <div class="progress">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                </div>
                <div class="text-muted">Fetching Accoutnts...</div>  -->
              @else
                <div class="waiting qb text-center">
                    <img class="primary" src="/img/logo.svg">
                    <i class="fas fa-times mx-4 animated tada bg-danger"></i>
                    <img class="external" src="/img/qb_3.png">
                </div>
              @endif
            </div>

            <div class="col-4 border-left">
                <label class="d-block mt-1">
                  <span class="py-1 d-inline-block">
                    @if($qbo_connect->qbo_connection == 1)
                      @php($connected = "Connected")
                    @else
                      @php($connected = "Disconnected")
                    @endif
                    Status:
                    <strong class="text-danger ml-1 animated flash" id="lblConnStatus">{{ $connected }}</strong>
                  </span>
                  <button class="btn btn-outline-success border-0 rounded float-right auto_sync" id="" data-toggle="tooltip" title="" data-original-title="Refresh connection status">
                    <i class="fas fa-sync"></i>
                  </button>
                </label>
                <!-- <label class="d-block">Last Sync:<strong class="ml-1">Jan 12, 2019</strong></label> -->

                @if($qbo_connect->qbo_connection == 1)
                  <div>
                    <a href="{{ route('qbo_disconnect') }}" class="btn btn-outline-secondary rounded mt-3 btn-block" data-workspace-src="qboDisconnected" data-title="QuickBooks Integration - SmartBooks" data-page-header="Company Details">
                      <i class="fas fa-link mr-1"></i>
                      Disconnect to QuickBooks</a>
                  </div>

                @else
                <div>
                  <a href="{{ route('qbo_connect') }}" class="btn btn-outline-success rounded mt-3 btn-block" data-workspace-src="qbo" data-title="QuickBooks Integration - SmartBooks" data-page-header="Company Details">
                    <i class="fas fa-link mr-1"></i>
                    Connect to QuickBooks</a>
                </div>
                
                @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  $('.auto_sync').click(function() {
    var successurl = '{{ route('qbo_integration_sync') }}';
    //var successurl1 = '{{ isset($successCallback) ? $successCallback : ""  }}';
    //alert(successurl)
    var newurl = successurl.replace(/&amp;/g, "&");
    
     window.location.href = successurl;/*'http://local.pg.com/qbo/syncall'*/
     //window.close();
  })
 
</script>

@endsection