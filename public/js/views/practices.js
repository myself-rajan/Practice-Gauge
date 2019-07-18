window.workspaceScript = new function () {
  _this = this;
  _this.SelfInviteWindow;
  _this.selfInviteSuccess = false;

  _this.onLoad = function () {

    $('#txtCName').on('input', function () {
      $('#spnCName').text(this.value);
      $('#spnCName1').text(this.value);
    })

    $('#txtPName').on('input', function () {
      $('#spnPName').text(this.value);
    })


    $('#btnSend').click(function () {

     var email      = $.trim($('#email').val());
     var first_name = $.trim($('#txtCName').val());
     var last_name  = $.trim($("#txtCLName").val());
     var txtPName   = $.trim($("#txtPName").val());
     var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
     if(email == "" || first_name == "" || last_name == "" || txtPName == ""){
        
      if(email == "")
      {
        $("#email").css("border-color", 'red');
      }
      else
      {
        $("#email").css("border-color", '');
         $("#validEmail").text('');
          $("#validEmail").css('color', '');
        if(emailRegax.test(email) == false)
        {
          $("#email").css("border-color", 'red');
          $("#validEmail").text('Please given valid email address');
          $("#validEmail").css('color', 'red');
        }else
        {
          $("#email").css("border-color", '');
          $("#validEmail").text('');
          $("#validEmail").css('color', '');
        } 
      }
       if(first_name == "")
      {
        $("#txtCName").css("border-color", 'red');
    
      }else{
         $("#txtCName").css("border-color", '');
         $("#validName").text('');
          $("#validName").css('color', '');
      }
      if(last_name == ""){
          $("#txtCLName").css("border-color", 'red');
       
      }else{
         $("#txtCLName").css("border-color", '');
          $("#validLastName").text('');
          $("#validLastName").css('color', '');
      }
       if(txtPName == ""){
          $("#txtPName").css("border-color", 'red');
       
      }else{
         $("#txtPName").css("border-color", '');
         
      }
      return false;

    }
    else
    {  
     formSubmit();
   }
 })

    $('#lnkSelfInvite').click(function () {
      _this.selfInviteSuccess = false;
      _this.SelfInviteWindow = window.open("/selfInvite.html", "self_invite");
      _this.SelfInviteWindow.isComplete = false;
      _this.checkChildWindow(_this.SelfInviteWindow, _this.selfInviteComplete);
    })


    $('#lnkWindowAgain').click(function () {
      _this.selfInviteSuccess = false;
      _this.SelfInviteWindow = window.open("/selfInvite.html", "self_invite");
      _this.SelfInviteWindow.isComplete = false;
      _this.checkChildWindow(_this.SelfInviteWindow, _this.selfInviteComplete);
    })

    $('#btnCancel').click(function () {
      _this.SelfInviteWindow.close();
      ;
    })
  };

  _this.selfInviteComplete = function () {
    //debugger;
    if (_this.SelfInviteWindow.isComplete) {

      $('#iconSpin').removeClass('text-primary').removeClass('fa-spinner').removeClass('fa-spin').addClass('text-success').addClass('fa-check-circle');
      $('#lblProgressMsg').text("New Practice Configuration Complete. Please wait...");
      $('#lnkWindowAgain_wrapper').hide();
      $('#mSelfInvite .modal-footer').remove();
      setTimeout(function () {
        location.reload();
      }, 3000);
    }
    else {
      //_this.SelfInviteWindow.close();
      $('#mSelfInvite').modal('hide');
      strawberry.toast.show("New Practice configuration has been cancelled", "info");
    }
  }

  _this.checkChildWindow = function (win, onclose) {
    var w = win;
    var cb = onclose;
    var t = setTimeout(function () { _this.checkChildWindow(w, cb); }, 500);
    var closing = false;
    try {
      //_this.selfInviteComplete = win.isComplete;
      if (win.closed || win.top == null) //happens when window is closed in FF/Chrome/Safari
        closing = true;
    } catch (e) { //happens when window is closed in IE        
      closing = true;
    }
    if (closing) {
      clearTimeout(t);
      onclose();
    }
  }
}

function formSubmit(){
  strawberry.loader.showFull();
  $("#mNewPractice").hide();
  $("#email").css("border-color", '');
  $("#validEmail").text('');
  $("#validEmail").css('color', '');
  var formData = {
    'email' :$("#email").val(),
    'first_name' : $("#txtCName").val(),
    'last_name' : $("#txtCLName").val(),
    'txtPName'  : $("#txtPName").val(),
    'practices_name': $("#txtPName").val(),
    'welcome_msg' : $("#clientMsg").html(),
    'welcome_msg_sep' : $('#welcome_msg').text(),
    'req_practice' : $("#req_practice").text(),
    'req_send' : $("#req_send").text(),
    'prag' : $("#prag").text(),
    'dName' : $("#dName").text(),
    'cName' : $("#cName").text(),
    'company' : $("#company").text(),
  }

  $.ajax({
    url:WEBURL+'/request/email',
    type:'POST',
    data:formData,
    success: function(response) {
      strawberry.loader.hideFull()
       console.log(response);
      $(".fade").removeClass('modal-backdrop');
      strawberry.toast.show("Invitation sent successfully", "success");
      $("#email").val('');
       $("#txtCName").val('');
       $("#last_name").val('');
        $("#txtPName").val('');

    }
  });
}

function checkEmail(){
  var email = $('#email').val();
  
  var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
  if(emailRegax.test(email) == false) {
    $("#email").css("border-color", 'red');
    $("#validEmail").text('Please provide valid email address');
    $("#validEmail").css('color', 'red');
    $("#btnSend").addClass('alpha-disabled');
  }else{
    $("#email").css("border-color", '');
    $("#validEmail").text('');
    $("#validEmail").css('color', '');
    $("#btnSend").removeClass('alpha-disabled');
    $.ajax({
      url: WEBURL+'/register/checkEmailStd',
      type: 'GET',
      data: {'email' : email},
      success: function(response) {
        if(response > 0){
          $('#checkEmailVal').html('Email is already available.Please try another.');
          $("#checkEmailVal").css('color', 'red');
          $("#btnSend").addClass('alpha-disabled');
          
        }else{
          $('#checkEmailVal').html('');
          $("#checkEmailVal").css('color', '');
           $("#btnSend").removeClass('alpha-disabled');
        }
        
      }
    });
  }
}

function checkEmailExist(){
  var email = $('#email').val();

  var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
  $('#txtCName').val('');
  $("#txtCLName").val('');
  $("#txtCName").removeClass('alpha-disabled');
  $("#txtCLName").removeClass('alpha-disabled');
  if(emailRegax.test(email) == false) {
    $("#email").css("border-color", 'red');
    $("#validEmail").text('Please provide valid email address');
    $("#validEmail").css('color', 'red');
    $("#btnSend").addClass('alpha-disabled');
  } else {
    $("#email").css("border-color", '');
    $("#validEmail").text('');
    $("#validEmail").css('color', '');
    $("#btnSend").removeClass('alpha-disabled');

    $.ajax({
      url: WEBURL+'/register/checkEmailExistStd',
      type: 'GET',
      data: {'email' : email},
      success: function(response) {
        console.log(response);
        $('#checkEmailVal').html('');
        $("#checkEmailVal").css('color', '');
        $("#btnSend").removeClass('alpha-disabled');

        if(response.first_name != undefined || response.last_name != undefined){
          if(response.role_id == 3){
            $('#checkEmailVal').html('Email is already available.Please try another.');
            $("#checkEmailVal").css('color', 'red');
            $("#btnSend").addClass('alpha-disabled'); 
          } else {
            $('#txtCName').val(response.first_name);
            $("#txtCLName").val(response.last_name);
            $("#txtCName").addClass('alpha-disabled');
            $("#txtCLName").addClass('alpha-disabled');
          }
        }else{
          $('#txtCName').val('');
          $("#txtCLName").val('');
          $("#txtCName").removeClass('alpha-disabled');
          $("#txtCLName").removeClass('alpha-disabled');
        }
        
      }
    });
  }
}

function checkPractNameExist(){

  var practiceName = $('#txtPName').val();

  /*$('#txtCName').val('');
  $("#txtCLName").val('');
  $("#txtCName").removeClass('alpha-disabled');
  $("#txtCLName").removeClass('alpha-disabled');*/
 
    $.ajax({
      url: WEBURL+'/register/checkPracticeNameExist',
      type: 'GET',
      data: {'practiceName' : practiceName},
      success: function(response) {
        console.log(response);
        $('#validPracticesName').html('');
        $("#validPracticesName").css('color', '');
        $("#btnSend").removeClass('alpha-disabled');
        if(response != '') {
          $('#validPracticesName').html('Practice Name is already available.Please try another.');
          $("#validPracticesName").css('color', 'red');
          $("#btnSend").addClass('alpha-disabled'); 
        } else {
          $('#validPracticesName').html('');
          $("#validPracticesName").css('color', '');
          $("#btnSend").removeClass('alpha-disabled'); 
        }
        
      }
    });
  
}

function newSelf(){

     var email      = $.trim($('#email').val());
     var first_name = $.trim($('#txtCName').val());
     var last_name  = $.trim($("#txtCLName").val());
     var txtPName   = $.trim($("#txtPName").val());
     var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
     if(email == "" || first_name == "" || last_name == "" || txtPName == "" || emailRegax == ""){
        
      if(email == "")
      {
        $("#email").css("border-color", 'red');
       
      }
      else
      {
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
       if(first_name == "")
      {
        $("#txtCName").css("border-color", 'red');
      
      }else{
         $("#txtCName").css("border-color", '');
      
      }
      if(last_name == ""){
          $("#txtCLName").css("border-color", 'red');
        
      }else{
         $("#txtCLName").css("border-color", '');
       
      }
       if(txtPName == ""){
          $("#txtPName").css("border-color", 'red');
         
      }else{
         $("#txtPName").css("border-color", '');
       
      }
      return false;

    }
    else
    {  
      var formData = {
      'roleID'          :4,
      'email'           :$("#email").val(),
      'first_name'      :$("#txtCName").val(),
      'last_name'       : $("#txtCLName").val(),
       'practices_name' : $("#txtPName").val(),
      }

     $.ajax({
             url: WEBURL+'/self_inviteNew',
                type: 'GET',
                data: formData,
                success: function(response) { 

                var resEncode =   response.user_id;
                var resCompayEncode = response.company_id;
                var b64 = btoa(unescape(encodeURIComponent(resEncode)));   
                var b64_company = btoa(unescape(encodeURIComponent(resCompayEncode)));   
                  if(response.user_id > 0){
                    window.location.href = WEBURL+'/selfRegister/form?confirmation_code='+b64+'&practices='+b64_company;
                  }else{
                    
                  }
                  
                }   
      })
   }
  
}

