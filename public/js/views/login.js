

var loginSlider;
var errorIndex = 0;

$(document).ready(function () {


  loginSlider = $('.carousel').carousel({
    interval: false
  });



  loginSlider.showLogin = function () {
    loginSlider.carousel(0);
    $('#lblError').hide();
    $('#secCompanyList').hide();

  }

  loginSlider.showLoader = function () {
    loginSlider.carousel(1);
  }

  loginSlider.showCompanyList = function () {
    $('#lblError').hide();

    loginSlider.carousel(2);
    $('#secCompanyList').slideToggle();
  }

  //Use this to show error message
  loginSlider.showError = function (msg) {
    $('#lblError .text').text(msg);
    $('#lblError').slideToggle();
  }


  //Below part only for mock. Will be diffrerent for Dev - Jones
  // $('#btnLogin').click(function () {

  //   if ($('#lblError:visible').length > 0) {
  //     $($('#lblError').slideToggle(400, function () {
  //       loginSlider.showLoader();

  //       setTimeout(function () {
  //         errorIndex++;
  //         if (errorIndex % 2 == 0) {
  //           loginSlider.showCompanyList();
  //         }
  //         else {
  //           loginSlider.showLogin();
  //           loginSlider.showError("Incorrect email or password");
  //         }
  //       }, 2000);
  //     }))
  //   }
  //   else {
  //     loginSlider.showLoader();

  //     setTimeout(function () {
  //       errorIndex++;
  //       if (errorIndex % 2 == 0) {
  //         loginSlider.showCompanyList();
  //       }
  //       else {
  //         loginSlider.showLogin();
  //         loginSlider.showError("Incorrect email or password");
  //       }
  //     }, 2000);
  //   }




  // });


  $('#btnLogout').click(function () {
    loginSlider.showLogin();
  })

  $('.list-group-item-action').click(function () {
    window.location.href = "/";

  })



})
