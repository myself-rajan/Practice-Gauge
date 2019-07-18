@extends('layouts.app_layout')

@section('content')
<div id="workspace" style="transition: all 0.3s ease 0s; transform: none; opacity: 1;">
  <div id="navData" data-title="General Settings - Practice Gauge" data-page-header="General Settings"></div>

  <div class="row ">
    <div class="col-sm-12 col-lg-6 col-xl-5 col-xxl-5">

      <div class="card bg-white border-0 shadow-sm rounded">
        <div class="card-body">
          <i class="fas fa-info-circle mr-1 text-info"></i>
          Manage your overall settings here
        </div>
        <div class="card-body border-top">
          <div class="form-group">
            <div class="row">
              <div class="col">
                <label>Accounting method</label>
              </div>
              <div class="col">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="rdAccMethod_accural" name="rdAccMethod" class="custom-control-input" checked="" value="1" <?php if(isset($setList)) { if($setList->accounting_method == 1) { echo "checked"; } }?>>
                  <label class="custom-control-label" for="rdAccMethod_accural">Accural</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="rdAccMethod_cash" name="rdAccMethod" class="custom-control-input" value="2" <?php if(isset($setList)) { if($setList->accounting_method == 2) { echo "checked"; } }?>>
                  <label class="custom-control-label" for="rdAccMethod_cash">Cash</label>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col">
                <label>Category</label>
              </div>
              <div class="col">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="rdCategory_class" name="rdCategory" class="custom-control-input" checked="" value="1" <?php if(isset($setList)) { if($setList->category == 1) { echo "checked"; } }?>>
                  <label class="custom-control-label" for="rdCategory_class">Class</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="rdCategory_location" name="rdCategory" class="custom-control-input" value="2" <?php if(isset($setList)) { if($setList->category == 2) { echo "checked"; } }?>>
                  <label class="custom-control-label" for="rdCategory_location">Location</label>
                </div>
              </div>
            </div>
          </div>
          <div class="text-right">
            <button class="btn btn-sm btn-success px-3" id="btnSaveSettings">Save</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $('#btnSaveSettings').click(function() {
    _btn = $(this);
    var rdMethod= $("input:radio[name=rdAccMethod]:checked").val();
    var rdCategory= $("input:radio[name=rdCategory]:checked").val();

    _btn = $(this);
    _btn.text('Saving...');
    _btn.addClass('btn-processing');
    _btn.prop('disabled', true);

    _btn.popover({
      html: true,
      content: "<i class=fas fa-check-circle mr-2'></i> Information saved successfully",
      trigger: 'focus',
      placement: 'top',
      template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    }).popover('show');

    $.ajax({
      url: "{{ route('save_general_settings') }}",
      type: 'POST',
      data: {
        rdMethod: rdMethod,
        rdCategory: rdCategory,
        "_token": "{{ csrf_token() }}",
      },

      success: function(response) {
        strawberry.toast.show("Information saved successfully", "success");
        _btn.text('Save');
        _btn.removeClass('btn-processing');
        _btn.prop('disabled', false);

        _btn.popover('dispose');
        strawberry.toast.hide();

      }
    });

  });
</script>
@endsection