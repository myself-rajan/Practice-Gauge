window.workspaceScript = new function () {
  _this = this;

  _this.onLoad = function () {

    $('.connected-available .list-group-item,.connected-selected .list-group-item').each(function () {
      //$(this).append('<button class="float-right btn-move"><i class="fas fa-chevron-right"></i></button>');
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

    $("#clVerticals, #listCat1, #listCat2, #listCat3, #listCat4, #listCat5, #listCat6, #listCat7, #listCat8, #listCat9, #listCat10, #listCat11, #listCat12, #listCat13, #listCat14, #listCat15, #listCat16, #listCat17, #listCat18, #listCat19, #listCat20, #listCat21, #listCat22, #listCat23, #listCat24, #listCat25, #listCat26, #listCat27, #listCat28").sortable({
      connectWith: ".verticals-sortable",
    }).disableSelection();

    $("#clModels, #clModelsSelected").sortable({
      connectWith: ".models-sortable",
    }).disableSelection();


    
    $('#txtCatSearchKey').keyup(function () {
      $('.connected-list-group strong').each(function () {
        if ($(this).text().toLowerCase().indexOf($('#txtCatSearchKey').val().toLowerCase()) != -1) {
          $(this).closest('div').show(200);
        }
        else {
          $(this).closest('div').hide(200);
        }
      });

    });


  };
};
