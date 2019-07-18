<?php $__env->startSection('content'); ?>
    <!--   <script src="<?php echo e(asset('js/views/editUser.js')); ?>"></script> -->
    <script src="<?php echo e(asset('js/cropper/cropper.js')); ?>"></script>
    <script src="<?php echo e(asset('js/cropper/jquery-cropper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/views/userDetail.js')); ?>"></script>
    <link href="<?php echo e(asset('js/cropper/cropper.css')); ?>" rel="stylesheet" />
      <div class="row">
        <div class="col-sm-4 col-md-3 col-xxl-2">
          <div class="position-relative">

            <img src="<?php echo e(isset($profile_image) ? $profile_image : asset('img/Oliver_Queen.jpg')); ?>" class="img-fluid img-thumbnail bg-white mb-2 w-100" alt="Profile Picture" id="showProfilePicAdd">
            <input type="hidden" name="profile_path" id="profile_path" value="<?php echo e(isset($profile_image) ? $profile_image : asset('img/users/Oliver_Queen.jpg')); ?>">
            <button class="btn btn-sm btn-light btn-block border-secondary fieldset-hide"
              id="btnProfilePicture" onclick="showModal()">Change</button>
          </div>
          <p class="text-danger error_image"></p>
        </div>
          <!--   <form id="editUser" name="editUser" method="post">-->
          <input type="hidden" name="user_id" id="user_id" value="<?php echo e(isset($id)?$id:''); ?>"> 
          <div class="col-sm-4 col-md-4 col-xxl-4">
            <div class="form-group">
              <label>First Name</label>
              <input class="form-control form-control-sm validate_blur" type="text" id="first_name" name="first_name"  placeholder="First Name" value="<?php echo e(isset($fname)?$fname:''); ?>" tabindex="1">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input class="form-control form-control-sm validate_blur" type="text" id="email" name="email"  placeholder="Email"
                value="<?php echo e(isset($email)?$email:''); ?>" onkeyup="checkEmail()" tabindex="3" disabled>
                <span id="validEmail"></span>
            </div>
              <div class="form-group">
              <label>Password</label>
              <input class="form-control form-control-sm validate_blur" type="password" id="password" name="password"  placeholder="Password"
                value="<?php echo e(isset($pwd)?$pwd:''); ?>"  tabindex="5" >
                
            </div>
          </div>
          <div class="col-sm-4 col-md-5 col-xxl-4">
            <div class="form-group">
              <label>Last Name</label>
              <input class="form-control form-control-sm validate_blur" type="text" id="last_name"  name="last_name" placeholder="Last Name"
                value="<?php echo e(isset($lname)?$lname:''); ?>" tabindex="2">
            </div>
            <div class="form-group">
              <label>Contact</label>
              <input class="form-control form-control-sm validate_blur" type="text" id="contact"  name="contact" placeholder="Contact"
                value="<?php echo e(isset($contact)?$contact:''); ?>" maxlength="10" tabindex="4">
            </div>
            <button class="btn btn-sm btn-success" data-toggle="" id="saveEditUser" data-target="#fsPolicy" type="button" style="float: right;" tabindex="5"><i class="fas fa-check mr-1"></i>Save</button>
          </div>
        <!-- </form> -->
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
                <button type="submit" class="btn btn-sm btn-success" data-dismiss="modal" id="btnSaveProfilePic" tabindex="6">Save</button>
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

	   	 $(document).ready(function(){
           $('.form-control').removeClass("empty-placeholder");
	   	})
      $("#saveEditUser").click(function(){
      	var fname 		= $("#first_name").val();
      	var lname 		= $("#last_name").val();
      	var contact     = $("#contact").val();
        var password        = $("#password").val();
        var profile_path        = $("#profile_path").val();
      	if(fname == "" || lname == "" || contact =="" || password == ""){
      		if(fname == ""){
      			$("#first_name").css("border-color", 'red');

      		}else{
      			$("#first_name").css("border-color", '');

      		}

      		if(lname == ""){
      			$("#last_name").css("border-color", 'red');
      		}else{
      			$("#last_name").css("border-color", '');
      		}

      		if(contact == ""){
      			$("#contact").css("border-color", 'red');
      		}else{
      			$("#contact").css("border-color", '');
      		}

          if(password == ""){
            $("#password").css("border-color", 'red');
          }else{
             $("#password").css("border-color", '');
          }

      	}else{
      		formSubmit();
      	}
      });

      function formSubmit(){
        var fname 		 = $("#first_name").val();
        var user_id      = $('#user_id').val();
      	var lname 		 = $("#last_name").val();
      	var contact      = $("#contact").val();
      	var password     = $("#password").val();
        var profile_path = $("#profile_path").val();
      	$.ajax({
      		url: '<?php echo e(route("editUserSvd")); ?>',
          type: 'POST',
          data: {'id':user_id,'fname':fname,'lname':lname,'contact':contact,'pwd':password, 'profile_path':profile_path},
          success: function(response) {
            if(response > 0){
             	  $(".fade").removeClass('modal-backdrop');
                strawberry.toast.show("Save successfully", "success");
                window.location.reload();
              }else{
            } 
          }
      	})
      }


      $('.validate_blur').blur(function (){
        var name       	= $('#first_name').val();
        var last_name  	= $('#last_name').val();
        var contact     = $("#contact").val();
        var password    = $("#password").val();
      
        if(name != ""){      
             $("#first_name").css("border-color", '');
            
        }
        if(last_name != ""){      
               $("#last_name").css("border-color", '');
              
        }
        if(contact != ""){
            $("#contact").css("border-color", '');
        }
        if(password != ""){
             $("#password").css("border-color", '');
        }
      });

      function showModal() {
        $('#mdProfilePic').modal('show');
      }

    	$('input[name="contact"]').keyup(function(e)                            {
    	  if (/\D/g.test(this.value)){
    	    // Filter non-digits from input value.
    	    this.value = this.value.replace(/\D/g, '');
    	  }
    	});
      	 
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
            
              if(response['logoPath'] == undefined) {
                $('.error_image').html('This filename should be a file of type jpeg, png, jpg, gif, svg.');
              } else {
                $("#showProfilePicAdd").attr("src", logoPath);
                $("#showProfilePicEdit").attr("src", logoPath);
                $("#profile_path").val(response['logoPath']);
                $('.error_image').html('');
              }
              //$("#profile_path_edit").val(response['logoPath']);
            }
         });
      });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>