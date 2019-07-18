<?php $__env->startSection('content'); ?>

<script src="<?php echo e(asset('/js/views/qbo_integration.js')); ?>"></script>
<script src="<?php echo e(asset('/js/views/registration.js')); ?>"></script>

<section id="secQB">
  <input type="hidden" name="email" id="email" value="<?php echo e(isset($cpaData->email) ? $cpaData->email : ''); ?>">
  <input type="hidden" name="name" id="name" value="<?php echo e(isset($cpaData->first_name) ? $cpaData->first_name : ''); ?>">
  <input type="hidden" name="company_id" id="company_id" value="<?php echo e(isset($companyId) ? $companyId : ''); ?>">


  <input type="hidden" name="client_name" id="client_name" value="<?php echo e(isset($clientData->first_name) ? $clientData->first_name : ''); ?> <?php echo e(isset($clientData->last_name) ? $clientData->last_name : ''); ?>">

  <div class="card border-0 mt-0 mb-4 mb-lg-0 mt-lg-0">
    <div class="card-body border-bottom border-info border-2">
      <span class="float-right text-center">
        Step 3 of 3
        <div class="progress" style="height: 5px; width:100px;" id="prgEnd">
          <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
          
        </div>
      </span>
      <h5 class="my-0 text-muted">Complete the following steps to define your
        practice.</h5>

    </div>
    <div class="card-body bg-light py-2">
      <h4 class="my-0">
        <button class="btn btn-sm btn-light btn-back" data-toggle="tooltip" title="" data-original-title="Go back to Business Models"><i class="fas fa-chevron-left mx-1"></i></button>
        <span class="align-middle">
          QuickBooks Online Integration
        </span>
      </h4>
    </div>


    <div class="card-body border-bottom disabled">

      <div class="form-group" id="chkQB_wrapper">
        <a href="#" class="mb-2 d-block">Read our terms and policy regarding QuickBooks
          integration.</a>

        <div class="custom-control custom-checkbox">
          <input type="checkbox" id="chkQB_yes" class="custom-control-input" checked>
          <label class="custom-control-label" for="chkQB_yes">I have read the above terms and policy, and
            allow Practice Gauge to connect to my QuickBooks account</label>
        </div>
      </div>
    </div>

    <div class="card-body text-center " id="divQB">
      <div id="divQB_0" style="display: none;">
        <div class="waiting qb mb-4">
          <img class="primary" src="<?php echo e(asset('/img/logo.svg')); ?>">
          <i class="fas fa-times mx-4 animated tada"></i>
          <img class="external" src="<?php echo e(asset('/img/qb_3.png')); ?>">
        </div>

        <!-- <button class="btn-qb"></button> -->
        <button href="#" class="btn-qb"></button>
      </div>


      <div class="card-body text-center">           
       <div id="divQB_2">
          <label>Please wait while we fetch data from QuickBooks Online</label>
          <div class="waiting qb mb-4">
            <img class="primary" src="<?php echo e(asset('/img/logo.svg')); ?>">
            <i class="fas fa-sync mx-4 animated tada"></i>
            <img class="external" src="<?php echo e(asset('/img/qb_3.png')); ?>">
          </div>
          <div class="progress">
            <div id="qbo-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
              aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div class="text-muted">Fetching Accounts...</div>
        </div>

        <div id="divQB_3" style="display:none">
          <i class="fas fa-check-circle text-success fa-3x mt-3">
          </i>
          <h3 class="mt-4">All Done</h3>
          <div class="my-5">You have successfully configured your company and inetegrated with QuickBooks</div>
        </div>
        <div id="divQB_4" style="display:none">
          <div class="waiting qb mb-4">
            <img class="primary" src="<?php echo e(asset('/img/logo.svg')); ?>">
            <i class="fas fa-times mx-4 animated tada bg-danger"></i>
            <img class="external" src="<?php echo e(asset('/img/qb_3.png')); ?>">
          </div>
          <!-- <h3 class="mt-4">All Done</h3> -->
          <div class="my-5 text-warning">There is a problem while connecting to the QuickBooks File </div>
        </div>
        <!-- <div class="card-footer text-right">
          <em class="text-muted ">You will be redirected into the appliaction shortly...</em>
        </div> -->
        <div id="divQBCheck" style="display:none">
          <i class="fas fa-check-circle text-success fa-3x mt-3">
          </i>
        </div>
      </div>

      <!-- <div id="divSuccessQB" style="display:none">

        <i class="fas fa-check-circle text-success fa-3x mt-3">
        </i>
        <h3 class="mt-4">All Done</h3>
        <div class="my-5">You have successfully configured your practice and inetegrated with QuickBooks
        </div>
      </div> -->
    </div>
    
    <div class="card-footer text-center text-muted divDoneQB" id="divTheEnd" style="display: none">
      <button class="btn btn-sm btn-success" onclick="window.location  = WEBURL+'/login';"><i class="fas fa-check-circle mr-1"></i> Done</button>
    </div>

  </div>

</section>


<script type="text/javascript">

  $(document).ready(function(){

    window.progress = 25;

    updateProgress();

    setTimeout(function () {

      var check_error = getQueryParam('error');

      if(check_error == 'true'){
        //strawberry.toast.show('This QuickBooks File already connected with some other company', 'danger');
        strawberry.dialog.confirm({
          title: 'QBO',
          body : 'QBO is already connected with other practices. Skip for now and do QBO connection later',
          yes  : connectLater,
        })

        setTimeout(function () {                    
           //window.location = "<?php echo e(route('home')); ?>";
        }, 5000);

      } else {
        getAccounts();
        getReports();
      }
         
    }, 1000);

  })

  var updateProgress = function(){
    $('#qbo-progress-bar').css('width', window.progress+'%');
    if(window.progress == 100) {
      window.seconds = 3;
      var myinterval = setInterval(function(){ window.seconds = window.seconds - 1; 
        $('#time_interval').text(window.seconds);

        if(window.seconds == 1) {
          clearInterval(myinterval);
          switchLogin();
        }
      }, 1000);
      
      strawberry.dialog.ok({
          title: 'QBO',
          body : 'Practice has been added successfully. We are redirecting to Account mapping screen to move forward. Please wait (<span id="time_interval">'+window.seconds+'</span>)',
          yes  : switchLogin,
      })
    }
  };

  function switchLogin() {
    //window.location  = WEBURL+'/login';
    $.ajax({
      url: "<?php echo e(route('switch_login')); ?>",
      type: 'POST',
      data: {
        company_id: $('#company_id').val(),
      },
      success: function (result) {   
        window.location  = WEBURL+'/account_mapping';
      },
    });
  }

  function getAccounts() {
    window.progress += 25;
    updateProgress();
    $.ajax({
      url: "<?php echo e(route('import_accounts')); ?>",
      type: 'GET',
      dataType: "JSON",
      success: function (result) { 

          if(result['error'] == true) {
            strawberry.toast.show(result['msg'], "warning");
            return false;
          }   
          window.progress = 100;
          updateProgress();

          setTimeout(function () {
              $('#divQB_2').slideToggle();
              $('#divQB_3').slideToggle();
          }, 1000);

          setTimeout(function () {             
            $('.divDoneQB').slideToggle();
            
            //window.location = "<?php echo e(route('home')); ?>";
          }, 4000);

        },
        error : function(response){
            window.progress = 100;
            updateProgress();

            setTimeout(function () {
                $('#divQB_2').slideToggle();
                $('#divQB_3').slideToggle();
            }, 1000);

            setTimeout(function () {                    
               //window.location = "<?php echo e(route('home')); ?>";
            }, 4000);
        }
    });
  }

  function getReports() {
    var i = 0;
    
    setTimeout(function () {    
      if($("#email").val() == "") {
        sendMailAdmin();
      } else {
        sendMailCpa();                                               
      }         
    }, 8000);
            
    for(i=0; i<=3; i++){

      var __ajax_url = "<?php echo e(route('get_qbo_reports')); ?>" + '?count=' + i;

      console.log(__ajax_url);

      $.ajax({
        url: __ajax_url,
        type: 'GET',
        dataType: "JSON",
        success: function (result) {   
          window.progress +=30;
          updateProgress();
          
          
        },
        error: function(){
          window.progress += 30;
          updateProgress();
        }
      });
    } 
  }

  function connectLater() {
    $('.divDoneQB').slideToggle();
    $('#divQBCheck').slideToggle();
    $('#chkQB_wrapper').slideToggle();
    $('#divQB_2').slideToggle();
  }

  function sendMailCpa(){

    $.ajax({
      url: "<?php echo e(route('emailSendCpa')); ?>",
      type:'POST',
      data:{
        email :$("#email").val(),
        name :$("#name").val(),
        client_name :$("#client_name").val(),
      },
      success: function(response) {
        strawberry.toast.show("Mail sent successfully", "success");
        strawberry.toast.hide();

      }
    });
  }

  function sendMailAdmin(){

    $.ajax({
      url: "<?php echo e(route('emailSendAdmin')); ?>",
      type:'POST',
      
      success: function(response) {
        strawberry.toast.show("Mail sent successfully", "success");
        strawberry.toast.hide();
      }
    });
  }
  
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.register_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>