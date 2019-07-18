window.workspaceScript = new function () {
  _this = this;


  _this.onLoad = function () {

    if (window.innerWidth < 1400) {
      strawberry.sidebar.toggle();
    }

    $('#reportWriter .list-group-item:not(.header) [class^=col-]:first-child').append(
      "<span class='fas fa-grip-lines sort-handle'></span>");


    window.reportWriter = $("#reportWriter .list-group");
    window.columnWriter = $('#columnWriter table');
    window.colWriterEnabled = false;

    window.reportWriter.sortable({
      handle: ".sort-handle",
      items: '.list-group-item:not(.header)',
      stop: _this.refreshLinks
    });

    $("#reportWriter .list-group .list-group-item").disableSelection();


    $('[data-report-rows]').on('click', 'button', function (e) {
      $('.tooltip').remove();

      var _btn = $(this);
      // var _record = _btn.closest('button');
      var _record = _btn;//.closest('button');

      var _id = _record.data('id');
      var _name = _record.data('name');
      var _type_id = _record.data('type-id');
      var _type_name = _record.data('type-name');

      var _newRecord = $('#rwStandard').clone();

      _newRecord.find('.rw-name').text(_name);
      _newRecord.find('.rw-name').tooltip({ title: _type_name });
      _newRecord.find('.rw-name').text(_name);
      _newRecord.find('.rw-display').prop('checked', true);
      _newRecord.find('.rw-display').attr('id', 'chkDisp_' + _type_id + _id);
      _newRecord.find('.rw-display').next('label').attr('for', 'chkDisp_' + _type_id + _id);
      _newRecord.removeAttr('id');
      _newRecord.find('.row.main > [class^=col-]:last-child').append("<span class='fas fa-trash text-muted btn-del fa-fw'></span>");
      _newRecord.find('.row.main > [class^=col-]:first-child').append("<span class='fas fa-grip-lines sort-handle'></span>");
      _newRecord.find('.btn-del').tooltip({ title: 'Delete row' });
      _newRecord.find('.fa-magic').tooltip({ title: 'Row Style' });
      $('#reportWriter .list-group').append(_newRecord);
      _this.refreshLinks();
    })


    $('#btnAddBlank').on('click', function (e) {
      $('.tooltip').remove();
      var _newRecord = $('#rwBlank').clone();

      _newRecord.removeAttr('id');
      _newRecord.find('.row.main > [class^=col-]:first-child').append("<span class='fas fa-grip-lines sort-handle'></span>");
      _newRecord.find('.row.main > [class^=col-]:last-child').append("<span class='fas fa-trash text-muted btn-del fa-fw float-right'></span>");
      _newRecord.find('.btn-del').tooltip({ title: 'Delete row' });
      $('#reportWriter .list-group').append(_newRecord);
      _this.refreshLinks();

    })

    $('#btnAddResult').on('click', function (e) {
      $('.tooltip').remove();
      var _newRecord = $('#rwResult').clone();
      var _chkId = new Date().getTime();
      _newRecord.attr('id', 'rw_result_' + new Date().getTime());
      _newRecord.find('.rw-display').attr('id', 'chkDisp_' + _chkId);
      _newRecord.find('.rw-display').next('label').attr('for', 'chkDisp_' + _chkId);
      _newRecord.find('.row.main > [class^=col-]:first-child').append("<span class='fas fa-grip-lines sort-handle'></span>");
      _newRecord.find('.row.main > [class^=col-]:last-child').append("<span class='fas fa-link fa-fw text-warning btn-reference'></span>");
      _newRecord.find('.row.main > [class^=col-]:last-child').append("<span class='fas fa-trash fa-fw text-muted btn-del'></span>");

      _newRecord.find('.btn-del').tooltip({ title: 'Delete row' });
      _newRecord.find('.btn-reference').tooltip({ title: 'Add a reference to this result' });
      _newRecord.find('.fa-magic').tooltip({ title: 'Row Style' });
      $('#reportWriter .list-group').append(_newRecord);
      _this.refreshLinks();
    })

    $("#reportWriter").on('click', '.btn-del', function () {
      $('.tooltip').remove();
      var _btn = $(this);
      var _record = $(this).closest('.list-group-item');

      if ($("[data-reference=" + _record.attr('id') + "]").length > 0) {
        _btn.popover({
          html: true,
          content: "<i class='fas fa-times mr-2 text-danger'></i> Please remove references to this row before deleting",
          trigger: 'focus',
          placement: 'top',
          template: '<div class="popover border border-danger text-white shadow" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }).popover('show');

        setTimeout(function () {
          _btn.popover('dispose');
        }, 4000);
      }
      else {
        $(this).closest('.list-group-item').remove();
      }

      _this.refreshLinks();
    });

    $("#reportWriter").on('click', '.btn-reference', function () {
      $('.tooltip').remove();
      var _result = $(this).closest('.list-group-item');
      var _refId = _result.attr('id');

      var _newRecord = $('#rwResultReference').clone();
      var _chkId = new Date().getTime();
      _newRecord.attr('id', 'rw_reference_' + new Date().getTime());
      _newRecord.attr('data-reference', _refId);
      _newRecord.find('.rw-display').attr('id', 'chkDisp_' + _chkId);
      _newRecord.find('.rw-display').next('label').attr('for', 'chkDisp_' + _chkId);
      _newRecord.find('.row.main > [class^=col-]:first-child').append("<span class='fas fa-grip-lines sort-handle'></span>");
      _newRecord.find('.row.main > [class^=col-]:last-child').append("<span class='fas fa-link fa-fw text-warning btn-reference'></span>");
      _newRecord.find('.row.main > [class^=col-]:last-child').append("<span class='fas fa-trash fa-fw text-muted btn-del'></span>");

      _newRecord.find('.btn-del').tooltip({ title: 'Delete row' });
      _newRecord.find('.btn-reference').tooltip({ title: 'Add a reference to this result' });

      $('#reportWriter .list-group').append(_newRecord);
      _this.refreshLinks();

    });


    //INITIALIZE COLUMN WRITER HERE ON LOAD TO CHECK FOR EMPTY EDITOR !!! IMPORTANT !!!
    _this.initColumnWriter();



  };

  $('#chkCW_enable').on('change', function () {
    var _chkBox = this;
    if (_chkBox.checked) {

      $(_chkBox).popover({
        html: true,
        content: "<i class=fas fa-info-circle mr-2'></i> Enabling this option will disable some filters while viewing the report.",
        trigger: 'focus',
        placement: 'top',
        template: '<div class="popover border-primary" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
      }).popover('show');

      setTimeout(function () {
        $(_chkBox).popover('dispose');
      }, 4000);
    }
  })

  $('#btnSaveDB').click(function () {

    var _btn = $(this);

    _btn.text('Saving...');
    _btn.addClass('btn-processing');
    _btn.prop('diasbled', true);

    setTimeout(function () {
      _btn.text('Save');
      _btn.removeClass('btn-processing');
      _btn.prop('diasbled', false);

      _btn.popover({
        html: true,
        content: "<i class=fas fa-check-circle mr-2'></i> Dashboard card type saved successfully",
        trigger: 'focus',
        placement: 'left',
        template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
      }).popover('show');

      setTimeout(function () {
        _btn.popover('dispose');
        _btn.closest('.dropdown-menu').dropdown('hide');
      }, 4000);

      strawberry.toast.show("Dashboard card type saved successfully", "success");
    }, 3000);
  })


  $('#reportWriter').on('mouseenter', '.list-group-item.rw-result-reference', function () {
    var _reference = $(this);
    var _line = $(".reference-line[data-reference-id=" + _reference.attr('id') + "]");
    $(".reference-line").removeClass('active');
    _line.addClass('active');
  })


  $('#reportWriter').on('mouseleave', '.list-group-item.rw-result-reference', function () {
    var _reference = $(this);
    var _line = $(".reference-line[data-reference-id=" + _reference.attr('id') + "]");

    _line.removeClass('active');
  })

  _this.refreshLinks = function () {

    //Dropdown form bubbler not working for the when only one row is added - NOT FIXED YET - Jones

    console.info("refreshed links");

    strawberry.dropdownSelectToggler();
    strawberry.dropdownFormBubbler();


    _this.checkEmptyRow();
    _this.attractSave();
    $('.reference-line').remove();
    $('#reportWriter .rw-result-reference').each(function () {
      var _reference = $(this);
      var _source = $('#' + _reference.attr('data-reference'));
      var _height = _reference.offset().top - _source.offset().top;
      //alert(_height);


      if (_height > 0) {
        $('#reportWriter').append('<div class="reference-line" style="height:' + _height + 'px;top:' + (_source.offset().top - window.reportWriter.offset().top) + 'px;border-color:' + strawberry.color.getRandomHSL() + '" data-reference-id="' + _reference.attr('id') + '"></div>');
      }
      else {

        window.reportWriter.find('.list-group-item').css('opacity', '0.5');
        window.reportWriter.find('.list-group-item').css('pointer-events', 'none');
        window.reportWriter.find('.list-group-item').css('cursor', 'not-allowed');

        _reference.css('opacity', '1');
        _reference.popover({
          html: true,
          content: "<i class='fas fa-times mr-2 text-danger'></i> Cannot use a reference before its original source",
          trigger: 'focus',
          placement: 'top',
          template: '<div class="popover border border-danger text-white shadow" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }).popover('show');

        setTimeout(function () {
          window.reportWriter.find('.list-group-item').css('opacity', '1');
          window.reportWriter.find('.list-group-item').css('pointer-events', 'auto');
          window.reportWriter.find('.list-group-item').css('cursor', 'auto');
          _reference.popover('dispose');
          window.reportWriter.sortable('cancel');
          _this.refreshLinks();
        }, 5000);

      }

    })
  }

  _this.attractSave = function () {
    strawberry.utility.addButtonFlash($('#btnSaveReport:not(.btn-flash)'), 'You have unsaved changes');
  }

  _this.checkEmptyRow = function () {
    if (window.reportWriter.find('li:not(.header)').length > 0) {
      window.reportWriter.closest('div').removeClass('empty');
    }
    else {
      window.reportWriter.closest('div').addClass('empty');
    }
  }


  _this.initColumnWriter = function () {
    if (window.columnWriter.find('tr').length > 0) {
      window.columnWriter.closest('#columnWriter').removeClass('empty');
      $('#divColWriterControls').show();
      window.colWriterEnabled = true;
    }
    else {
      window.columnWriter.closest('#columnWriter').addClass('empty');
      $('#divColWriterControls').hide();
      window.colWriterEnabled = false;
    }

    _this.updateColumnWriter();

    $('#selPeriodType option:first-child')[0].selected = true;
    $('#colDataMonthSumNumber').hide();
    $("#rangeColPeriod").slider({
      min: 0,
      max: 48,
      slide: function (event, ui) {
        if (ui.value == 0) {
          $('#lblColPeriod').text("Period: As-on-date");
        }
        else {
          $('#lblColPeriod').text("Period: -" + ui.value);

        }
      }
    });

  }

  _this.updateColumnWriter = function () {
    if (window.colWriterEnabled) {
      window.columnWriter.closest('#columnWriter').removeClass('empty');
      $('#divColWriterControls').show();

      //Dropdown bubbler
      strawberry.dropdownFormBubbler();

      //Show popover on what sample data is
      $('#ddSampleAOD:visible').popover({
        html: true,
        content: "<i class='fas fa-info-circle mr-1 text-warning animated flash'></i> Select a sample As-on-date here and the system will emulate the report periods accordingly, to provide clear idea on how the columns will be populated.",
        trigger: 'focus',
        placement: 'bottom',
        template: '<div class="popover border border-primary text-white shadow-lg" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
      }).popover('show');

      setTimeout(function () {
        $('#ddSampleAOD').popover('dispose');
      }, 8000);
    }
    else {
      window.columnWriter.closest('#columnWriter').addClass('empty');
      $('#divColWriterControls').hide();
    }
    document.getElementById('chkEnableColWriter').checked = window.colWriterEnabled;
  }

  $('#btnEnableFixedPeriods').on('click', function () {
    window.colWriterEnabled = true;
    _this.updateColumnWriter();
  })

  $('#chkEnableColWriter').on('change', function () {
    window.colWriterEnabled = document.getElementById('chkEnableColWriter').checked;
    _this.updateColumnWriter();
  })


  $('[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    //e.relatedTarget // previous active tab
    console.log(e.target);
    var _tab = $(e.target) // newly activated tab
    if (_tab.attr('id') == "colWriter") {

    }

    if (_tab.attr('id') == 'tabColData') {
      $('#selPeriodType option:first-child')[0].checked = true;
      $('#colDataMonthSumNumber').hide();
    }
  })

  $('[data-toggle="pill"]').on('shown.bs.tab', function (e) {
    //e.relatedTarget // previous active tab
    console.log(e.target.href);
    var _tab = $(e.target) // newly activated tab
    if (_tab.attr('id') == "colWriter") {

    }

    if (_tab.attr('href') == '#tabColCalc') {
      strawberry.dropdownSelectToggler();
    }
  })

  $('#ddSampleAOD').on('click', function () {
    $('#ddSampleAOD').popover('dispose');
  })


  $('#btnApplySampleAOD').on('click', function () {
    if ($('#selSampleAOD_month option:selected').attr('value') == "0" || $('#selSampleAOD_year option:selected').attr('value') == "0") {
      //dont sapply
    }
    else {
      $('#lblAOD').text(": " + $('#selSampleAOD_month option:selected').text() + " " + $('#selSampleAOD_year option:selected').text());
    }
  })



  $('#selPeriodType').on('change', function () {
    if ($(this).find('option:selected').attr('value') == "m") {
      $('#lblColPeriod').text("Period: As-on-date");
      $("#rangeColPeriod").slider('destroy').slider({
        min: 0,
        max: 48,
        slide: function (event, ui) {
          if (ui.value == 0) {
            $('#lblColPeriod').text("Period: As-on-date");
          }
          else {
            $('#lblColPeriod').text("Period: -" + ui.value);

          }
        }
      });
    }
    else {
      $('#lblColPeriod').text("Period: 0 - 3");
      $("#rangeColPeriod").slider('destroy').slider({
        range: true,
        min: 0,
        max: 48,
        values: [0, 3],
        slide: function (event, ui) {
          if (ui.values[0] >= ui.values[1])
            return false;
          $('#lblColPeriod').text("Period: " + ui.values[0] + " - " + ui.values[1]);
        }
      });

    }
  })

};
