     $('#btnSubmit').click(function () {
  
    var name       = $('#first_name').val();
    var last_name  = $('#last_name').val();
    var email      = $('#email').val();
    var order_about = $('#order_about').val();
    var chkAgreement = document.getElementById('chkAgreement').checked;
    var regionSelect     =   $('.checkbox:checked').map(function(_, el) {
        return $(el).val();
    }).get();


 
   
    var emailRegax = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

    if(name == "" || last_name == ""|| email == "" || regionSelect == "" || order_about == "" || chkAgreement == "" ){    

      if(name == "")
      {
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
      
   
      if(email == "")
      {
        $("#email").css("border-color", 'red');
        $("#validEmail").text('Please enter the email');
        $("#validEmail").css('color', 'red');
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
         $("#chkAgreementError").text('I agree to the Privacy Policy.');
          $("#chkAgreementError").css('color', 'red');
      }else{
          $("#chkAgreement").css("border-color", '');
          $("#chkAgreementError").text('');
          $("#chkAgreementError").css('color', '');
      }
      if(regionSelect == ""){

          $(".checkbox").css("border-color", 'red');
          $("#validRegion").text('Please select any one region.');
          $("#validRegion").css('color', 'red');
      }else{
           $(".checkbox").css("border-color", '');
          $("#validRegion").text('');
          $("#validRegion").css('color', '');
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
    var formCPA = $('#cpa_register').serialize();
    $.ajax({
      url:'{{route("save_reg_cpa")}}',
      type:'POST',
      data:formCPA,
        success: function(response) {
          console.log(response);
                // if(response > 0){
                //     window.location  = WEBURL+'/register/sentStdSuccess';
                // }else{
                //   window.location  = WEBURL+'/auth/login';
                // }
                
              }
    });

  }