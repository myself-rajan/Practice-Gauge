@extends('layouts.app_layout')

@section('content')
  <script src="{{ asset('/js/views/qbo_integration.js') }}"></script>
  <link href="{{ asset('/css/app_modules/integration.css') }}" rel="stylesheet" />

  <div id="divQB_2" style="">
    <label>Please wait while we fetch data from QuickBooks Online</label>
    <div class="waiting qb mb-4">
      <img class="primary" src="{{ asset('/img/logo.svg') }}">
      <i class="fas fa-sync mx-4 animated tada"></i>
      <img class="external" src="{{ asset('/img/qb_3.png') }}">
    </div>

    <div class="progress">
      <div id="qbo-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" >
      </div>
    </div>
    <div class="text-muted">Fetching Accounts...</div>

    <!-- <div class="col-xl-6">
      <h3>Sync Logs
      </h3>
      <div class="list-group rounded mb-4">
         <div class="list-group-item log-div" style="height: 430px;overflow: auto;">
           
         </div>   
      </div>
    </div> -->
  </div>

<script type="text/javascript">
  $(document).ready(function(){
    window.progress = 20;
    updateProgress();
    setTimeout(function () {
         getAccounts();
         getReports();
    }, 1000);
  })

  var updateProgress = function() {
    $('#qbo-progress-bar').css('width', window.progress+'%');
    if(window.progress >= 100){
      setTimeout(function () {
        window.location = "{{ route('qbo_integration') }}";
      }, 1000);
    }
  };

  function getAccounts() {
    $.ajax({
        url: "{{ route('import_accounts') }}",
        type: 'GET',
        dataType: "JSON",
        success: function (result) {   
            //console.log(result['error']);
            if(result['error'] == true) {
              strawberry.toast.show(result['msg'], "warning");
              return false;
            } 
            window.progress += 20;
            updateProgress();
        },
        error : function(response) {
            window.progress += 20;
            updateProgress();
        }
    });
  }


  function getReports() {
    var i = 0;

      for(i=0; i<=3; i++){

        var __ajax_url = "{{ route('get_qbo_reports') }}" + '?count=' + i;

        console.log(__ajax_url);

        $.ajax({
          url: __ajax_url,
          type: 'GET',
          dataType: "JSON",
          success: function (result) {   
            window.progress +=15;
            updateProgress();
          },
          error: function(){
            window.progress += 15;
            updateProgress();
          }
        });
      } 
  }

</script>

@endsection