window.workspaceScript = new function () {
  _this = this;

  _this.onLoad = function () {

    $('#btnAddGroup').click(function () {

      var _btn = $(this);
      if ($('#txtCustomName').val().trim().length === 0) {
        return;
      }

      _btn.text('Adding...');
      _btn.addClass('btn-processing');
      _btn.prop('diasbled', true);

      setTimeout(function () {
        _btn.text('Add Group');
        _btn.removeClass('btn-processing');
        _btn.prop('diasbled', false);

        $('#clCustomGroups').append('<div class="list-group-item"><span class="badge badge-success badge-pill mr-2 text-center text-monospace" data-toggle="tooltip" title="Balance Sheet">B</span><button class="btn btn-sm float-right text-danger" onclick="workspaceScript.removeItem(this)" data-toggle="tooltip"><i class="fas fa-times"></i></button>' + $('#txtCustomName').val().trim() + '</div>');
        strawberry.tooltipToggler();
        $('#txtCustomName').val('');
        _btn.popover({
          html: true,
          content: "<i class=fas fa-check-circle mr-2'></i> Reporting group added successfully",
          trigger: 'focus',
          placement: 'top',
          template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }).popover('show');

        setTimeout(function () {
          _btn.popover('dispose');
          _btn.closest('.dropdown-menu').dropdown('hide');
        }, 6000);
        
        strawberry.toast.show("Reporting group added successfully", "success");
      }, 3000);
    })

  };

  _this.removeItem = function (x) {
    $(x).closest('.list-group-item').remove();
    strawberry.toast.show("Custom reporting group was removed", "info");
  };
};
