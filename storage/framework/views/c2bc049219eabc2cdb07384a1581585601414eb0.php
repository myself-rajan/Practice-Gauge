<?php $__env->startSection('content'); ?>
  <div id="navData" data-title="User Manager - Practice Gauge" data-page-header="User Manager"></div>
  <script src="<?php echo e(asset('/js/cropper/cropper.js')); ?>"></script>
  <script src="<?php echo e(asset('/js/cropper/jquery-cropper.min.js')); ?>"></script>
  <script src="<?php echo e(asset('/js/views/userDetail.js')); ?>"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>

  <link href="<?php echo e(asset('js/cropper/cropper.css')); ?>" rel="stylesheet" />

  <div class="breadcrumb-wrapper" aria-label="Breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#" data-workspace-src="home" data-title="Home - Practice Gauge"
          data-page-header="Home"><i class="fas fa-home"></i></a></li>
      <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card bg-light mb-3 border-0">
          <div class="row">
            <div class="col-2">
              <div>
                <button class="btn btn-primary btn-sm add-user-btn mb-10" data-toggle="modal"  data-target="#mAddUser"  style="cursor: pointer;margin-bottom: 10px">
                  <i class="fas fa-user-plus"></i> Add User
              </button>
              </div>
            </div>
          </div>

        <div class="card-header">
          <h4 class="mb-0">Users</h4>
        </div>

        <div class="card-body table-y-scroll">
          <table class="table table-sm table-no-border bg-white" >
            <thead>
              <tr>
                <th class="border-top-0 bg-light">Name</th>
                <th class="border-top-0 bg-light">Email</th>
                <th class="border-top-0 bg-light">User Type</th>
                <th class="border-top-0 bg-light">Status</th>
                <th class="border-top-0 bg-light">&nbsp;</th>
              </tr>
            </thead>

            <tbody class="align-middle">
              <?php if(count($users) > 0): ?>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <td><?php echo e($row->first_name); ?> <?php echo e($row->last_name); ?></td>
                    <td><?php echo e($row->email); ?></td>
                    <?php $__currentLoopData = $roleUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowRole): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if($rowRole['id'] == $row->role_id): ?>
                        <td><?php echo e($rowRole['name']); ?></td>
                      <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($row->active == 1): ?>
                      <td>Active</td>
                    <?php else: ?>
                      <td>InActive</td>
                    <?php endif; ?>
                    
                    <td class="text-right">
                      <a class="btn btn-sm btn-light rounded-pill text-primary userValId" data-toggle="modal" data-target="#mAddUser" data-id="<?php echo e($row->id); ?>"><i class="fas fa-pencil-alt" style="cursor: pointer"></i></a>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              
              <?php else: ?>
                <tr  colspan="4">
                  <td >
                    No user found
                  </td>
                </tr>
                
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="mAddUser">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            <div class="col-4 col-fhd-3">
              <img class="img-fluid" src="<?php echo e(asset('/img/logo.svg')); ?>">
            </div>
            <div class="col-8 col-fhd-9 text-right d-none">
              <button class="btn btn-light shadow-sm" href="login.html">
                <i class="fas fa-sign-out-alt mr-1"></i>
                Logout
              </button>
            </div>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
            <span aria-hidden="true" id="close_model">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div>
            <div class="card border-0 mt-0 mb-4 mb-lg-0 mt-lg-0">
              <div class="card-body border-bottom border-info border-2">
                <h5 class="my-0 text-muted">Complete the following Details to add user.</h5>
              </div>

              <form method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="user_id" id="user_id" value="">
                <input type="hidden" name="company_id" id="company_id" value="">
                <div class="card-body pb-0">
                 
                  <div class="row">
                    <!-- <div class="col-sm-4 col-md-3 col-xxl-2">
                      <div class="position-relative">
                        <img src="<?php echo e(asset('/img/users/Oliver_Queen.jpg')); ?>" class="img-fluid img-thumbnail bg-white mb-2 w-100" id="showProfilePicEdit"
                          alt="Profile Picture" />
                        <input type="hidden" name="profile_path" id="profile_path" value="">
                        <button class="btn btn-sm btn-light btn-block border-secondary fieldset-hide"
                          id="btnProfilePicture">Change</button>
                      </div>
                    </div> -->

                    <div class="col-sm-6 col-md-6 col-xxl-6">
                      <div class="form-group">
                        <label>First Name</label>
                        <input class="form-control form-control-sm validate_blur" type="text" id="first_name" data-placeholder=""  name="first_name" placeholder="" value="" tabindex="1">
                      </div>
                      <div class="form-group">
                        <label>Email</label>
                          <input class="form-control form-control-sm validate_blur" type="text" id="email" name="email" data-placeholder="" placeholder="" value="" onkeyup="checkEmail()" tabindex="3">
                          <span id="validEmail"></span>
                      </div>
                      <div class="form-group">
                        <label>Role</label>
                        <select class="form-control form-control-sm validate_blur" id="role" name="role" tabindex="5">
                         <?php $__currentLoopData = $roleUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if($row['id'] != 1 && $row['id'] != 3): ?>
                           <option value="<?php echo e($row['id']); ?>"><?php echo e($row['name']); ?></option>
                          <?php endif; ?>
                     
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <label>Contact</label>
                        <input class="form-control form-control-sm validate_blur" id="contact" name="contact" type="text" data-placeholder="" placeholder=""
                          value="" tabindex="4">
                      </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-xxl-6">
                      <div class="form-group">
                        <label>Last Name</label>
                        <input class="form-control form-control-sm validate_blur" type="text" data-placeholder="" name="last_name" id="last_name" placeholder=""
                          value="" tabindex="2">
                      </div>

                      <div class="form-group">
                        <label>Password</label>
                        <input class="form-control form-control-sm validate_blur" type="password" id="password"  name="password" data-placeholder="" placeholder=""
                          value="" tabindex="6">
                      </div>
                    
                      <div class="form-group company_select">
                        <label>Company</label>
                        <select class="validate_click" name="company_ids"  id="company_ids" multiple  >
                            <?php $__currentLoopData = $company; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <?php if($row->id != ''): ?>
                                <option value="<?php echo e($row->id); ?>"><?php echo e($row->name); ?></option>
                              <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </div>

                      <div class="form-group border-bottom-0 py-20">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="chkUserActive" id="chkUserActive" tabindex="6" checked>
                          <label class="custom-control-label" for="chkUserActive" >Active</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
       
        <div class="card-footer">
          <div class="clearfix float-right add-btn" style="display: none">            
            <button class="btn btn-sm btn-secondary mr-1" id="addCancel" data-toggle="fieldset-disable" data-target="#newUSer" data-dismiss="modal"><i
                  class="fas fa-times mr-1"></i>Cancel</button>
            <button class="btn btn-sm btn-success" data-toggle="" id="saveNewUser" data-target="#fsPolicy" type="button" onclick="validateNewUser()" style="cursor: pointer;"><i
                  class="fas fa-check mr-1"></i>Save</button>
          </div>
          <div class="clearfix float-right edit-btn"  style="display: none">       
            <button class="btn btn-sm btn-secondary mr-1" data-toggle="fieldset-disable" data-target="#fsPolicy" id="editCancel" data-dismiss="modal"><i
                class="fas fa-times mr-1"></i>Cancel</button>
            <button class="btn btn-sm btn-success" data-toggle="" data-target="#fsPolicy" 
            onclick="validateEditUser()" type="button" style="cursor: pointer;"><i
                class="fas fa-check mr-1"></i>Save</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--User Change Picture Modal-->
  <div class="modal fade" id="mdProfilePic">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
                Change profile picture
            </h4>
          </div>
          <form id="frmProfilePic" class="visible-md-block w-100" enctype="multipart/form-data" method="POST" name="profilePic">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
              <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="fileProfilePciture" aria-describedby="inputGroupFileAddon04" name="profile_picture">
                    <label class="custom-file-label" for="fileProfilePciture">Choose file</label>
                </div>
              </div>
              <div class="crop-wrapper position-relative" id="cropProfilePic"></div>
            </div>
            <div class="modal-footer">
              <div class="text-right mt-3">
            <button class="btn btn-sm btn-light" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-success" data-dismiss="modal" id="btnSaveProfilePic">Save</button>
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      window.workspaceScript.onLoad();
    });

    $('#company_ids').multiselect({
      placeholder: 'Select',
      search: true,
      //enableFiltering: true,
      buttonWidth: '200px',
      buttonHeight: '10px'
    });

    function showModal() {
      $('#mdProfilePic').modal('show');
    }

  	/*$("#editUser").click(function(){
  		//$("#addUser").hide();
  		//$("#fsPolicy").show();
      //$("#newUSer").hide();
  	});*/
    
    $(".add-user-btn").click(function(){
      $('.add-btn').show();
      $('.edit-btn').hide();
    });
  	$("#addUser").click(function(){
      var logo = '/img/users/Oliver_Queen.jpg';
      var logoPath = WEBURL+logo;
      $("#showProfilePicAdd").attr("src", logoPath);
      //$("#editUser").hide();
      //$("#fsPolicy").hide();
      //$("#newUSer").show();
      //$('.fieldset-edit').show();
  	});

    function validateNewUser() {
      $('.add-btn').show();
      var first_name = $('#first_name').val();
      var last_name  = $('#last_name').val();
      var email      = $('#email').val();
      var contact    = $('#contact').val();
      var password   = $('#password').val();
      var company_ids= $('#company_ids').val();

      var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

      $("#first_name").css("border-color", '');
      $("#last_name").css("border-color", '');
      $("#email").css("border-color", '');
      if(first_name == "" || last_name == ""|| email == "" || contact == "" || password == "" || company_ids == "") { 
        if(first_name == "") {
          $("#first_name").css("border-color", 'red');
          $("#validName").text('Please enter the name.');
          $("#validName").css('color', 'red');
        }

        if(last_name == "") {
            $("#last_name").css("border-color", 'red');
            $("#validLastName").text('Please enter the last name.');
            $("#validLastName").css('color', 'red');
        }

        if(email == "") {
          $("#email").css("border-color", 'red');
        }
        else {
          $("#email").css("border-color", '');
          $("#validEmail").text('');
          $("#validEmail").css('color', '');
          if(emailRegax.test(email) == false) {
            $("#email").css("border-color", 'red');
            $("#validEmail").text('Please given valid email address');
            $("#validEmail").css('color', 'red');
          }else{
            $("#email").css("border-color", '');
            $("#validEmail").text('');
            $("#validEmail").css('color', '');
          } 
        }
        if(contact == ""){
            $("#contact").css("border-color", 'red');
        }
        if(password == ""){
          $("#password").css("border-color", 'red');
        }
        if(company_ids == ""){
          $(".company_select .multiselect").css("border-color", 'red');
        }
      }else{
        newUserSubmit();
      }
    }

    /*$('.company_select .multiselect').blur(function() {
      if($('#company_ids').val() == '') {
        $(".company_select .multiselect").css("border-color", 'red');
      } else {
        $(".company_select .multiselect").css("border-color", '');
      }
    })*/

    $('.company_select .multiselect').on('blur', function () {
      $(".company_select .multiselect").css("border-color", '');
    })

  	function newUserSubmit(){
  		//var formData = $("#new_userSvd").serialize();
      var data = {
        first_name: $('#first_name').val(),
        last_name: $('#last_name').val(),
        email: $('#email').val(),
        contact: $('#contact').val(),
        user_id: $('#user_id').val(),
        password: $('#password').val(),
        profile_path: $('#profile_path').val(),
        chkUserActive1: $('#chkUserActive:checkbox:checked').length,
        role: $('#role').val(),
        company_ids: $('#company_ids').val(),
      }

  		$.ajax({
  			 url:'<?php echo e(route("newUserLogin")); ?>',
    		 type:'POST',
    		 data:data,
           success: function(response) {
           	if(response > 0){
              strawberry.toast.show("Infomation saved successfully", "success");
              $('.toast-wrapper').css('z-index', 9999);
              strawberry.toast.hide();
              window.location.reload();
              return false;
            }
          }
  		})
  	}

    /*$("#role").click(function(){
      $('#company_ids').val([]).multiselect('refresh');
      if($(this).val() == 4) {
        $('#company_ids').multiselect({
          includeSelectAllOption: true
        });
        $("#company_ids").multiselect('selectAll', false);
        $("#company_ids").multiselect('updateButtonText');
      }
    });*/

    /*$("#addCancel, #editCancel").click(function(){
      $('.modal').hide();
    });*/
    

  	$(".userValId").click(function(){
      $('.edit-btn').show();
      $('.add-btn').hide();
  		var id = $(this).data('id');
  		$.ajax({
        url:'<?php echo e(route("editUserList")); ?>',
        type:'POST',
        data:{'id':id},
        
        success: function(response) {
          $("#first_name").val(response.user.first_name);
          $("#last_name").val(response.user.last_name);
          $("#email").val(response.user.email);
          $("#email").attr('disabled', true);

          $("#contact").val(response.user.contact);
          $("#password").val(response.user.pwd);
          $("#user_id").val(id);

          if(response.user.profile_image == null) {
            response.user.profile_image = '/img/users/Oliver_Queen.jpg';
          }
          var logoPath = WEBURL+response.user.profile_image;
          $("#showProfilePicEdit").attr("src", logoPath);
          $("#profile_path").val(response.user.profile_image);

          $('#role option[value="'+response.user.role_id+'"]').prop('selected', true);

          if(response.user.active == 1){
            $('#chkUserActive').prop('checked', true);
          }

          var valArr = response.user.company_name;
          i = 0, size = valArr.length;
          $('#company_ids').val([]).multiselect('refresh');
          for(i; i < size; i++){
            $('#company_ids').multiselect('select', valArr[i].id);
          }
        }
      });
  	});

    function validateEditUser() {
      var first_name = $('#first_name').val();
      var last_name  = $('#last_name').val();
      //var email      = $('#email').val();
      var contact    = $('#contact').val();
     // var password   = $('#passwor').val();

      var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

      if(first_name == "" || last_name == ""|| email == "" || contact == "") { 
        if(first_name == "") {
          $("#first_name").css("border-color", 'red');
        }

        if(last_name == "") {
          $("#last_name").css("border-color", 'red');
        }

        if(email == "") {
          $("#email").css("border-color", 'red');
        } else {
          $("#email").css("border-color", '');
          if(emailRegax.test(email) == false) {
            $("#email").css("border-color", 'red');
          } else {
            $("#email").css("border-color", '');
          } 
        }
        if(contact == "") {
          $("#contact").css("border-color", 'red');
        }
      } else {
        updateUser();
      }
    }

    function updateUser(){
      //$("#addUser").show();
      var data = {
        first_name: $('#first_name').val(),
        last_name: $('#last_name').val(),
        contact: $('#contact').val(),
        email: $('#email').val(),
        user_id: $('#user_id').val(),
        profile_path: $('#profile_path').val(),
        role: $('#role').val(),
        chkUserActive: $('#chkUserActive:checkbox:checked').length,
        company_ids  : $('#company_ids').val(),
        pwd    :$('#password').val(),
      }

      $.ajax({
         url:'<?php echo e(route("updateUser")); ?>',
         type:'POST',
         data:data,
          success: function(response) {
            window.location.reload();
            strawberry.toast.show("Infomation updated successfully", "success");
            $('.toast-wrapper').css('z-index', 9999);
            strawberry.toast.hide();
            $('.fieldset-edit').hide();
            return false;
          }
      })
    }

    $('#btnSaveProfilePic').click(function(event){
      var imgUrl   = $('#profilePicCrop').attr('src');
      var imgJson  = JSON.stringify($('#profilePicCrop').cropper("getData"));

      event.preventDefault();
      var form = document.forms.namedItem("profilePic");
      var formData = new FormData(form);
      formData.append("imgData", imgJson);

      $.ajax({
        url: "<?php echo e(route('save_user_profile')); ?>",
        data: formData,
          type: 'post',
          processData: false,
          contentType: false,
        success: function(response) {
          var logoPath = WEBURL+response['logoPath'];
          $("#showProfilePicAdd").attr("src", logoPath);
          $("#showProfilePicEdit").attr("src", logoPath);
          $("#profile_path").val(response['logoPath']);
          $("#profile_path").val(response['logoPath']);
        }
     });
    });

    function searchUser(){
      var search = $("#search").val();
      $.ajax({
        url: '<?php echo e(route("search_users")); ?>',
        type: 'POST',
        data: {'search' : search},
        success: function(response) {
          console.log(response);
          $(".search-list").html(response);
        }
      });
    }

    function checkEmail(){
      var email = $('#email').val();
      var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
      if(emailRegax.test(email) == false) {
          $("#email").css("border-color", 'red');
          $("#validEmail").text('Please given valid email address');
          $("#validEmail").css('color', 'red');
          $("#saveNewUser").addClass('alpha-disabled');
      } else {
          $("#email").css("border-color", '');
          $("#validEmail").text('');
          $("#validEmail").css('color', '');
          $("#saveNewUser").removeClass('alpha-disabled');
        $.ajax({
         url: '<?php echo e(route("check_email_std")); ?>',
          type: 'GET',
          data: {'email' : email},
          success: function(response) {
            if(response > 0){
              $('#validEmail').html('Email is alread taken!');
              $("#validEmail").css('color', 'red');
              $("#saveNewUser").addClass('alpha-disabled');
            }else{
               $('#validEmail').html('');
               $("#validEmail").css('color', '');
               $("#saveNewUser").removeClass('alpha-disabled');
            }
            
          }
        }); 
      }
    }

    $('.validate_blur').keyup(function (){
      var first_name    = $('#first_name').val();
      var last_name     = $('#last_name').val();
      var email         = $('#email').val();
      var contact       = $('#contact').val();
      var password      = $('#password').val();


      if(first_name != ""){
        $("#first_name").css("border-color", '');
      } 
   
      if(last_name != ""){
        $("#last_name").css("border-color", '');
      }
  
      if(contact != ""){
        $("#contact").css("border-color", '');
      }
   
      if(email != ""){      
        $("#email").css("border-color", '');
      }

      if(password != ""){
        $("#password").css("border-color", '');
      }
    })

    $('#mAddUser').on('hidden.bs.modal', function () {
      $(this).find('form').trigger('reset');
      $("#company_ids").multiselect('updateButtonText');
      $('#email').removeAttr('disabled');
    })
  </script>

  <style type="text/css">
    .multiselect.dropdown-toggle {
      /* border-radius: 0px !important; */
      color: #212529;
      background-color: #f8f9fa;
      border-color: #d3d9df;
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

    .multiselect-container.dropdown-menu{
      min-width: -webkit-fill-available;
      /*min-height:250px;*/
    }

    .multiselect-container.dropdown-menu li label{
      padding-left:20px;
    }
  </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>