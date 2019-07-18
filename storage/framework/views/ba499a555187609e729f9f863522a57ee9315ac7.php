  
  <?php $__env->startSection('content'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>

    <div class="card-body border-bottom border-info border-2">
      <h5 class="my-0 text-muted">Fill the following form and we will send a verfication link to your email.</h5>
    </div>
    <div class="card-body bg-light py-2">
      <h4 class="my-0">CPA Registration</h4>
    </div>

    <form id="cpa_register" name="cpa_register" method="post">
      <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
      <input type="hidden" name="role" id="role" value="3">
      <div class="card-body pb-0">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label>First Name</label>
              <input class="form-control form-control-sm validate_blur" placeholder="First Name" id="first_name" name="first_name" value="" />
              <!-- <span id="validName"></span> -->
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label>Last Name</label>
                  <input class="form-control form-control-sm validate_blur" placeholder="Last Name" id="last_name" value=""  name="last_name" />
                  <!-- <span id="validLastName"></span> -->
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label>Email address</label>
              <input class="form-control form-control-sm validate_blur" placeholder="Email" id="email" name="email" value=""  onblur="checkEmail()" />
              <span id="validEmail"></span>
            </div>
          </div>

           <div class="col-sm-6">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label>Organization Name</label>
                  <input class="form-control form-control-sm validate_blur" placeholder="Organization Name" id="company_name" value=""  name="company_name" />
                  <!-- <span id="validLastName"></span> -->
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="row border-top mt-3">
          <div class="col-4 pb-3">
            <div class="form-group mt-3">
              <label>How many practices do you handle?</label>
              <select class="form-control form-control-sm mb-3 validate_blur" id="practices_count" name="practices_count">
                <option value="0">Select one</option>
                <option value="10">1-10</option>
                <option value="25">11-25</option>
                <option value="50">26-50</option>
                <option value="75">51-75</option>
                <option value="100">76-100</option>
                <option value="200">101-200</option>
                <option value="200+">200+</option>
              </select>
                <!-- <div class="input-group align-items-center">
                <input type="range" class="custom-range validate_blur" min="5" max="50" step="1" id="rngPractices"
                  style="width:80%;" value="5" name="range">
                <label class="form-control form-control-sm mb-0" id="rngPractices_target" style="width:20%;">5</label>
              </div> -->
            </div>

            <div class="form-group">
              <label>How did you know about Practice Gauge?</label>

              <select class="form-control form-control-sm mb-3 validate_blur" id="order_about" name="order_about">
                <option value="">Select one</option>
                <?php $__currentLoopData = $aboutKnown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value['id']); ?>"><?php echo e($value['name']); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               
              </select>
              <input class="form-control form-control-sm  alpha-disabled" placeholder="Specify if other"  name="order_about1"  id="order_about1" value=""   />
            </div>
            <!-- <span id="order_about_valid"></span> -->
          </div>

          <div class="col-8 border-left pb-3">
            <div class="form-group mt-3">
              <label class="mb-0">Region of your client(s)</label>
              <small class="d-block text-muted mb-3">Select all the regions that apply</small>
              <div class="row">
                <div class="col">
                  <div>
                    <select class="form-control form-control-sm mb-3 validate_blur" id="region" name="region[]">
                      <option value="0">Select one</option>
                    <?php $__currentLoopData = $region; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <!-- <input type="checkbox" id="region_<?php echo e($value['id']); ?>" name="region[]" class="custom-control-input checkbox validate_blur" value="<?php echo e($value['id']); ?>" >
                      <label class="custom-control-label checkbox" for="region_<?php echo e($value['id']); ?>"><?php echo e($value['state']); ?></label> -->
                      <option value="<?php echo e($value['id']); ?>"><?php echo e($value['state']); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
              </div>
              <span id="validRegion"></span>

              <label class="mb-0">States of your client(s)</label>
              <small class="d-block text-muted mb-3">Select all the states that apply</small>
              <div class="row">
                <div class="col">
                  <div>
                    <select class="validate_click" name="states[]"  id="states" multiple>
                        <option value="0">Select State</option>
                      <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value['id']); ?>"><?php echo e($value['name']); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="clearfix">
            <div class="custom-control custom-checkbox float-left">
              <input type="checkbox" id="chkAgreement" name="chkAgreement"  value="" class="custom-control-input">
              <label class="custom-control-label" for="chkAgreement">I accept the terms and conditions of
                service.</label>
                <span id="chkAgreementError"></span>
            </div>
            <button class="float-right btn btn-sm btn-primary " type="button" id="btnSubmit"><i
                class="fas fa-check mr-2"></i>Register</button>
          </div>
        </div>
    </form>
      </div>

    <script type="text/javascript">
      $(document).ready(function(){
        $('#first_name').val('');
        $('#last_name').val('');
        $('#email').val('');
        $('#company_name').val('');

        $("#cpa_register").trigger("reset");
      });

      $('#states').multiselect({
        columns: 1,
        placeholder: 'Select states',
        search: true,
        //enableFiltering: true,
        maxHeight: 300,
        width:300
      });
     
      $('#btnSubmit').click(function () {
        var name       = $.trim($('#first_name').val());
        var last_name  = $.trim($('#last_name').val());
        var email      = $.trim($('#email').val());
        var company_name = $.trim($('#company_name').val());
        var order_about = $('#order_about').val();
        var practices_count = $('#practices_count').val();
        var states = $('#states').val();
        var region = $('#region').val();
        var chkAgreement = document.getElementById('chkAgreement').checked;
        /*var regionSelect     =   $('.checkbox:checked').map(function(_, el) {
            return $(el).val();
        }).get();*/
   
        var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

        if(name == "" || last_name == ""|| email == ""  || order_about == "" || chkAgreement == "" || practices_count == '' || region == '' || states == '' || company_name == ''){    

          if(name == ""){
            $("#first_name").css("border-color", 'red');
            $("#validName").text('Please enter the name.');
            $("#validName").css('color', 'red');
          }else{
             $("#first_name").css("border-color", '');
             $("#validName").text('');
              $("#validName").css('color', '');
          }
          if(last_name == ""){
              $("#last_name").css("border-color", 'red');
              $("#validLastName").text('Please enter the last name.');
              $("#validLastName").css('color', 'red');
          }else{
             $("#last_name").css("border-color", '');
              $("#validLastName").text('');
              $("#validLastName").css('color', '');
          }
          
          if(email == ""){
            $("#email").css("border-color", 'red');
            $("#validEmail").text('');
            $("#validEmail").css('color', 'red');
          }
          else{
            $("#email").css("border-color", '');
             $("#validEmail").text('');
              $("#validEmail").css('color', '');
            if(emailRegax.test(email) == false)
            {
              $("#email").css("border-color", 'red');
              $("#validEmail").text('Please provide valid email address');
              $("#validEmail").css('color', 'red');
            }else
            {
              $("#email").css("border-color", '');
              $("#validEmail").text('');
              $("#validEmail").css('color', '');
            } 
          }
          if(order_about == ""){
              $("#order_about").css("border-color", 'red');
             $("#order_about_valid").text('Please select any one.');
              $("#order_about_valid").css('color', 'red');
          }else{
             $("#order_about").css("border-color", '');
             $("#order_about_valid").text('');
              $("#order_about_valid").css('color', '');
          }
          if(chkAgreement == false){
              $("#chkAgreement").css("border-color", 'red');
             $("#chkAgreementError").text('*');
              $("#chkAgreementError").css('color', 'red');
          }else{
              $("#chkAgreement").css("border-color", '');
              $("#chkAgreementError").text('');
              $("#chkAgreementError").css('color', '');
          }
          
          if(practices_count == 0){
            $("#practices_count").css("border-color", 'red');
          }else{
            $("#practices_count").css("border-color", '');
          }
          
          if(region == 0){
            $("#region").css("border-color", 'red');
          }else{
            $("#region").css("border-color", '');
          }
          
          if(states == 0){
            $(".multiselect ").css("border-color", 'red');
          }else{
            $(".multiselect ").css("border-color", '');
          }

          if(company_name == ""){
              $("#company_name").css("border-color", 'red');
             $("#company_name_valid").text('Please select any one.');
              $("#company_name_valid").css('color', 'red');
          }else{
             $("#company_name").css("border-color", '');
             $("#company_name_valid").text('');
              $("#company_name_valid").css('color', '');
          }

          return false;
        }else{
          submitCPA();
        }
      });
  
      $('#order_about').change(function(){
        if($('#order_about').val() == 4){
            $('#order_about1').removeClass('alpha-disabled');
        }else{
           $('#order_about1').addClass('alpha-disabled');
        }
      });

      function  submitCPA(){
        var email = $('#email').val();
        var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
        if(emailRegax.test(email) == false){
          $("#email").css("border-color", 'red');
          $("#validEmail").text('Please provide valid email address');
          $("#validEmail").css('color', 'red');
          $("#btnSubmit").addClass('alpha-disabled');
        }else{
          $("#email").css("border-color", '');
          $("#validEmail").text('');
          $("#validEmail").css('color', '');
          $("#btnSubmit").removeClass('alpha-disabled');
          var _btn = $('#btnSubmit');
          _btn.text('Saving... Please wait');
          _btn.addClass('btn-processing');
          _btn.prop('diasbled', true);
          var formCPA = $('#cpa_register').serialize();
          $.ajax({
            url:'<?php echo e(route("save_reg_cpa")); ?>',
            type:'POST',
            data:formCPA,
            success: function(response) {
              if(response > 0){
                window.location  = WEBURL+'/register/sent_success';
              }else{
                window.location  = WEBURL+'/login';
              }      
            }
          });
        }
      }

      function checkEmail(){
        var email = $('#email').val();
        // var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
        // if(emailRegax.test(email) == false)
        //  {
        //        $("#email").css("border-color", 'red');
        //        $("#validEmail").text('Please provide valid email address');
        //        $("#validEmail").css('color', 'red');
        //         $("#btnSubmit").addClass('alpha-disabled');
        // }else{
        //        $("#email").css("border-color", '');
        //        $("#validEmail").text('');
        //        $("#validEmail").css('color', '');
        //         $("#btnSubmit").removeClass('alpha-disabled');
        $.ajax({
          url: '<?php echo e(route("check_email_std")); ?>',
          type: 'GET',
          data: {'email' : email},
          success: function(response) {
            if(response > 0){
              $('#validEmail').html('Email is already available.Please try another.');
              $('#validEmail').css('color', 'red');
              $("#email").css('border-color', 'red');
              $("#btnSubmit").addClass('alpha-disabled');
            }else{
               $('#validEmail').html('');
               $('#validEmail').css('color', '');
               $("#email").css('border-color', '');
               $("#btnSubmit").removeClass('alpha-disabled');
            }
          }
        });
        //}
      }

      
      $('.multiselect').on('blur',function (){
        var states = $('#states').val();
        $(".multiselect").css("border-color", '');
      });

      $('.validate_blur').blur(function (){
        
        var name       = $('#first_name').val();
        var last_name  = $('#last_name').val();
        var email      = $('#email').val();
        var company_name = $('#company_name').val();
        var order_about = $('#order_about').val();
        var practices_count = $('#practices_count').val();
        var region = $('#region').val();
        var states = $('#states').val();
        var chkAgreement = document.getElementById('chkAgreement').checked;
        /*var regionSelect     =   $('.checkbox:checked').map(function(_, el) {
            return $(el).val();
        }).get();*/

        if(name != ""){      
          $("#first_name").css("border-color", '');
        }
        if(last_name != ""){      
          $("#last_name").css("border-color", '');
        }
        if(order_about != ""){      
          $("#order_about").css("border-color", '');
        }
        if(practices_count != 0){      
          $("#practices_count").css("border-color", '');
        }
        if(region != 0){      
          $("#region").css("border-color", '');
        }
        if(states != 0){   
          $(".multiselect").css("border-color", '');
        }
        if(chkAgreement != ""){      
          $("#chkAgreement").css("border-color", '');
          $("#chkAgreementError").text('');
          $("#chkAgreementError").css('color', '');
        }
        /*if(regionSelect != ""){      
              $(".checkbox").css("border-color", '');
              $("#validRegion").text('');
              $("#validRegion").css('color', '');
        }*/
      });
    </script>

    <style>
      .alpha-disabled {
        opacity: 0.5;
        pointer-events: none;
        cursor: not-allowed;
      }

      .multiselect.dropdown-toggle{
        border-radius: 0px !important;
        color: #212529;
        background-color: #f8f9fa;
        border-color: #d3d9df;

      }

      .btn-group .multiselect.dropdown-toggle:first-child{
        border-radius: 0px !important;
      }

      .btn-group {
        width: 50%; 
      }
      .multiselect-container.dropdown-menu{
        min-width: -webkit-fill-available;
      }

      .multiselect-container.dropdown-menu li label{
        padding-left:20px;
      }
    </style>
    <!-- <div class="login-footer-singup">
      Dont have an account? <a href="<?php echo e(route('login')); ?>">Sign In</a>
   </div> -->
  <?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.register_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>