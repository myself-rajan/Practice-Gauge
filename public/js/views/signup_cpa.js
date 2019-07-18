$(document).ready(function () {
  $('#rngPractices').on('input', function () {
    $('#rngPractices_target').text($(this).val());
  })

  $('#btnSaveBasic').on('click', function () {
    window.location.href = "signup_cpa_done.html";
  });
})