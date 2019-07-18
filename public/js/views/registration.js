var slider;
var errorIndex = 0;

$(document).ready(function () {
  strawberry.dropdownSelectToggler();
  strawberry.dropdownFormBubbler();
  $('[data-toggle=tooltip]').tooltip();

  slider = $('.carousel').carousel({
    interval: false
  });

  slider.showBasic = function () {
    var _btn = $('#btnSaveBasic');
    _btn.html('<i class="fas fa-check mr-1"></i> Save and coninue');
    _btn.removeClass('btn-processing');
    _btn.prop('diasbled', false);

    slider.carousel(0);
  }

  slider.showIndustry = function () {
    var _btn = $('#btnSaveIndustry');
    _btn.html('<i class="fas fa-check mr-1"></i> Save and coninue');
    _btn.removeClass('btn-processing');
    _btn.prop('diasbled', false);
    slider.carousel(1);
  }

  slider.showBusiness = function () {
    var _btn = $('#btnSaveBusiness');
    _btn.html('<i class="fas fa-check mr-1"></i> Save and coninue');
    _btn.removeClass('btn-processing');
    _btn.prop('diasbled', false);
    slider.carousel(2);
  }

  slider.showQB = function () {
    slider.carousel(2);
  }

  //Use this to show error message
  slider.showError = function (msg) {
    $('#lblError .text').text(msg);
    $('#lblError').slideToggle();
  }

  //Below part only for mock. Will be diffrerent for Dev - Jones
  $('#btnSaveBasic').click(function () {
    var pName   = $.trim($("#pName").val());
    var address = $.trim($("#address").val());
    var state   = $.trim($("#state").val());
    var city    = $.trim($("#city").val());
    var zipcode = $.trim($("#zipcode").val());
    var cName   = $.trim($("#cName").val());
    var cPhone  = $.trim($("#cPhone").val()); 
    var cEmail  = $.trim($("#cEmail").val());
    var rdbPreferred_email = document.getElementById('rdbPreferred_email').checked;
    var rdbPreferred_text   = document.getElementById('rdbPreferred_email').checked;
    if(pName == "" || address == "" || state == "" || city == "" || zipcode == "" || cName == "" || cPhone =="" || cEmail =="" ){    

      if(pName == ""){ 
         $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the practice name.</label>');  
         return;
      }else{
        $("#errorMsg").html('');  
      }

      if(address == ""){  
            $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the address.</label>');
            return;
      }else{
        $("#errorMsg").html('');
      }

      if(city == ""){
           $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the city.</label>');
           return;
      }else{
        $("#errorMsg").html('');
      }

      if(state == ""){
            $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the state.</label>');
            return;
      }else{
        $("#errorMsg").html('');
      }
     
      if(zipcode == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the zipcode.</label>');
            return;
      }else{
        $("#errorMsg").html('');
      }

      if(cName == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the contact person name.</label>');
          return;
      }else{
          $("#errorMsg").html('');
      }

      if(cPhone == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the contact phone number.</label>');
           return;
      }else{
           $("#errorMsg").html('');
      }

      if(cEmail == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the contact email.</label>');
        return; 
      }else{
          $("#errorMsg").html('');
      }

      return false;
    }else{
      $("#errorMsg").html('');
          
      var _btn = $(this);

      _btn.text('Saving... Please wait');
      _btn.addClass('btn-processing');
      _btn.prop('diasbled', true);

      setTimeout(function () {
        // _btn.html('<i class="fas fa-check mr-1"></i> Save and coninue');
        // _btn.removeClass('btn-processing');
        // _btn.prop('diasbled', false);

        _btn.popover({
          html: true,
          content: "<i class='fas fa-check-circle mr-2'></i> Basic details saved successfully",
          trigger: 'focus',
          placement: 'top',
          template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }).popover('show');
      }, 2000);
    }

    var formData = $('#basic_information').serialize();
    $.ajax({
      url:WEBURL+'/basicInformationSvd',
      type:'POST',
      data:formData,
      success: function(response) {
        if(response > 0){
          setTimeout(function () {
             _btn.popover('dispose');
            slider.showIndustry();
            strawberry.dropdownSelectToggler();
          }, 2000);
        }
      }
    });
  })

  $('#btnSaveIndustry').click(function () {
    var year                 = $("#year").text();
    var operatories_count    = $("#operatories_count").val();
    var entity_count         = $("#entity_count").val();
    var practices_count      = $("#practices_count").val();
    var owners_count         = $("#owners_count").val();
    var employee_count       = $("#employee_count").val();
    var fte_count            = $("#fte_count").val(); 
    //var rdgMill              = $( "input[name*='rdgMill']").val();
    //var rdgImplants          = $( "input[name*='rdgImplants']").val();
    //var rdgAligner           = $( "input[name*='rdgAligner']").val();
    var rdgMill              = $("input[name='rdgMill']:checked").val();
    var rdgImplants          = $("input[name='rdgImplants']:checked").val();
    var rdgAligner           = $("input[name='rdgAligner']:checked").val();
    
    if(year == "" || operatories_count == "" || entity_count =="" || practices_count==""  | owners_count == "" || employee_count =="" || fte_count =="" || rdgMill=="" || rdgImplants =="" || rdgAligner == ""){
      if(year == ""){
       $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select the year.</label>');  
       return;
      }else{
        $("#errorMsg").html('');  
      }

      if(operatories_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select operatories count.</label>');  
        return;
      }else{
          $("#errorMsg").html('');  
      }

      if(entity_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select entity count.</label>');  
       return;
      }else{
        $("#errorMsg").html('');  
      }

      if(practices_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select practices count.</label>');  
       return;
      }else{
        $("#errorMsg").html('');  
      }

      if(owners_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select owners count.</label>');  
        return;
      }else{
        $("#errorMsg").html('');  
      }

      if(employee_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select employee count.</label>');  
        return;
      }else{
        return false;
      }

      if(fte_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select fte count.</label>');  
       return;
      }else{
      $("#errorMsg").html('');  
      }

      if(rdgMill == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please choose practices mill units.</label>');  
       return;
      }else{
        $("#errorMsg").html('');  
      }

      if(rdgImplants == ""){
         $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please choose offer implants.</label>');  
       return;
      }else{
          $("#errorMsg").html('');  
      }

      if(rdgAligner == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please choose clean aligner services.</label>');  
       return;
      }else{
        $("#errorMsg").html('');  
      }
    }else{
      $("#errorMsg").html('');  

    var _btn = $(this);
    _btn.text('Saving... Please wait');
    _btn.addClass('btn-processing');
    _btn.prop('diasbled', true);

    setTimeout(function () {
      // _btn.html('<i class="fas fa-check mr-1"></i> Save and coninue');
      // _btn.removeClass('btn-processing');
      // _btn.prop('diasbled', false);
      _btn.popover({
        html: true,
        content: "<i class='fas fa-check-circle mr-2'></i> Practice Specfifics saved successfully",
        trigger: 'focus',
        placement: 'top',
        template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
      }).popover('show');
    }, 2000);

    var formData = {
      'user_id'           : $("#user_id").val(),
      'year'              : $("#year").text(),
      'operatories_count' : $("#operatories_count").val(),
      'entity_count'      : $("#entity_count").val(),
      'practices_count'   : $("#practices_count").val(),
      'owners_count'      : $("#owners_count").val(),
      'employee_count'    : $("#employee_count").val(),
      'fte_count'         : $("#fte_count").val(),
      'rdgMill'           : $("input[name='rdgMill']:checked").val(),
      'rdgImplants'       : $("input[name='rdgImplants']:checked").val(),
      'rdgAligner'        : $("input[name='rdgAligner']:checked").val(),
      }
      $.ajax({
        url:WEBURL+'/practiceSpecificsSvd',
        type:'POST',
        data:formData,
        success: function(response) {
          if(response > 0){
             setTimeout(function () {
              _btn.popover('dispose');
                slider.showQB();
            }, 2000);
         }
        }
      });
    }
  })

  $('#btnSaveBusiness').click(function () {
    var _btn = $(this);
    _btn.text('Saving... Please wait');
    _btn.addClass('btn-processing');
    _btn.prop('diasbled', true);

    setTimeout(function () {
      // _btn.html('<i class="fas fa-check mr-1"></i> Save and coninue');
      // _btn.removeClass('btn-processing');
      // _btn.prop('diasbled', false);

      _btn.popover({
        html: true,
        content: "<i class='fas fa-check-circle mr-2'></i> Busines models saved successfully",
        trigger: 'focus',
        placement: 'top',
        template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
      }).popover('show');

      setTimeout(function () {
        _btn.popover('dispose');
        slider.showQB();
      }, 2000);
    }, 2000);
  })

  $('.btn-qb').click(function () {
    $('#chkQB_wrapper').addClass('disabled');
    console.log('Hiding first div showing second');
    $('#divQB_0').slideToggle();
    $('#divQB_1').slideToggle();

    setTimeout(function () {
      console.log('Hiding second div showing third');
      $('#divQB_1').slideToggle();
      $('#divQB_2').slideToggle();

      setTimeout(function () {
        console.log('Hiding third div showing fourth');
        $('#divQB_2').slideToggle();
        $('#divQB_3').slideToggle();

        setTimeout(function () {
          $('#chkQB_wrapper').removeClass('disabled');
          $('#divTheEnd').removeClass('d-none');
          $('#prgEnd > div:last-child').remove();
          $('#prgEnd > div:first-child').css('width', '100%');
          //location.href = "/";
          window.isComplete = true;
        }, 1000)

      }, 4000)
    }, 3000)
  })

  $('#btnLogout').click(function () {
    slider.showLogin();
  })

  $('.list-group-item-action').click(function () {
    window.location.href = "/";
  })

  $('#secIndustry .btn-back').click(slider.showBasic);
  $('#secBusiness .btn-back').click(slider.showIndustry);
  $('#secQB .btn-back').click(slider.showIndustry);

  $('.connected-available .list-group-item,.connected-selected .list-group-item').each(function () {
    $(this).append('<button class="float-right btn-move"><i class="fas fa-chevron-right"></i></button>');
  });

  $('.connected-available').on('click', '.btn-move', function () {
    var _item = $(this).closest('.list-group-item');
    $(this).closest('.connected-list').find('.connected-selected').append(_item);
  })

  $('.connected-selected').on('click', '.btn-move', function () {
    var _item = $(this).closest('.list-group-item');
    $(this).closest('.connected-list').find('.connected-available').append(_item);
  })

  $('.connected-list').on('click', '.btn-clear-all', function () {
    var _items = $(this).closest('.connected-list').find('.connected-selected .list-group-item');

    $(this).closest('.connected-list').find('.connected-available').append(_items);
  })

  $('.connected-list').on('click', '.btn-select-all', function () {
    var _items = $(this).closest('.connected-list').find('.connected-available .list-group-item');

    $(this).closest('.connected-list').find('.connected-selected').append(_items);
  })

  $("#clVerticals, #clVerticalsSelected").sortable({
    connectWith: ".verticals-sortable",
  }).disableSelection();


  $("#clModels, #clModelsSelected").sortable({
    connectWith: ".models-sortable",
  }).disableSelection();

  //Data-toggle=range
  $('select[data-toggle=range]').each(function () {
    var _sel = $(this);
    var _start = parseInt(_sel.data('min')) || 0;
    var _end = parseInt(_sel.data('max')) || 10;

    for (i = _start; i <= _end; i++) {
      _sel.append("<option value='" + i + "'>" + i + "</option>");
    }
  })

  $('#chkQB_yes').on('click', function () {
    if (this.checked) {
      $('#divQB').removeClass('disabled');
    }
    else {
      $('#divQB').addClass('disabled');
    }
  })
})



