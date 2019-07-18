window.workspaceScript = new function () {
  _this = this;

  _this.onLoad = function () {

    $("input[type=radio][name=rdGroup]").checkboxradio();

    $('#btnSaveIncomeStatement').click(function () {
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
          content: "<i class=fas fa-check-circle mr-2'></i> Information saved successfully",
          trigger: 'focus',
          placement: 'left',
          template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }).popover('show');

        setTimeout(function () {
          _btn.popover('dispose');
        }, 6000);

        strawberry.toast.show("Information saved successfully", "success");
      }, 3000);
    })

  }
};
