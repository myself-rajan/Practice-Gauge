window.workspaceScript = new function () {
  _this = this;

  _this.onLoad = function () {

    $('.collapse').on('shown.bs.collapse', function () {
      strawberry.dropdownSelectToggler();
    })

    $('.modal').on('shown.bs.modal', function () {
      strawberry.dropdownSelectToggler();
    })

    $('#selCompanyGroup').change(function(){
      $('#accordionUserAccessmd').show();
      $('#cdPreview').show();
    })

    $('#btnNewUserAccess').click(function () {
      $('#mdNewUserAccess').modal();
    })



    $('#btnBatchAccess').click(function(){
      $('#mdBatchAccess').modal();

    })

    $('.accordion').click(function () {
      strawberry.utility.addButtonFlash($('#btnSaveAccess:not(.btn-flash)'), 'You have unsaved changes');
    })

    $('#btnProfilePicture').click(function () {
      $('#mdProfilePic').modal();
      document.getElementById('frmProfilePic').reset();
      $('#cropProfilePic').html("");
      $('#btnSaveProfilePic').prop('disabled', true);
    })

    $('#fileProfilePciture').on('change', function (ev) {
      console.log(ev);
      if (this.files.length > 0) {
        var file = this.files[0];
        var fileReader = new FileReader();

        var blob = URL.createObjectURL(file);
        console.log(file.name);

        $('[for=fileProfilePciture]').text(file.name);

        var _html = "<img src='" + blob + "' id='profilePicCrop' class='img-fluid mx-auto img-fluid'/>";
        //_html += "<div class='text-right mt-3'><button class='btn btn-sm btn-success' id='btnSaveProfilePic'>Save</button></div>";
        $('#cropProfilePic').html(_html);

        var $image = $('#profilePicCrop');

        var $cropper = $image.cropper({
          aspectRatio: 1,
          //crop: function(event) {

          //}
        });


        $('#btnSaveProfilePic').prop('disabled', false);

      }
      else {
        $('#cropProfilePic').html("");
        $('#btnSaveProfilePic').prop('disabled', true);
      }
    })

  };
}
