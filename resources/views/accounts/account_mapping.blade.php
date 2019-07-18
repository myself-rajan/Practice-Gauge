@extends('layouts.app_layout')

@section('content')
    <div class="workspace p-4" id="workspace" style="transition: all 0.3s ease 0s; transform: none; opacity: 1;"><div id="navData" data-title="Accounts Mapping - Practice Gauge" data-page-header="Accounts Mapping"></div>
    <link href="{{ asset('css/app_modules/connected.list.css') }}" rel="stylesheet">
    <script src="{{ asset('js/views/company_industry.js') }}"></script>
    <div class="card bg-white shadow-sm rounded border-0">
      <div class="card-body">
        <input class="form-control form-control-sm float-right w-auto" placeholder="Type here to search categories" id="txtCatSearchKey">
        <div class="text-muted"><i class="fas fa-info-circle mr-1 animated flash text-warning " style="animation-iteration-count:3"></i>
          Drag the accounts
          you require into the
          categories you choose.</div>
      </div>

      <div class="card-body border-top">
        <div class="connected-list">
            <div class="row">
                <div class="col-4 col-xl-3">
                  <strong class="mb-1 d-block clearfix">Available Accounts
                  </strong><meta name="csrf-token" content="{{ csrf_token() }}">
              
                  <ul id="clVerticals" class="verticals-sortable list-group connected-available ui-sortable">
                    @if(isset($accountsList))
                    @foreach ($accountsList as $key => $row) 

                      <li class="list-group-item ui-sortable-handle" data-account_id="{{ $row->id }}" data-values="{{ $row->values }}">{{ $row->account_num }}  {{ $row->account_name }} <button class="float-right btn-move"><i class="fas fa-chevron-right"></i></li><!-- [account_id => {{ $row->id }} , values => {{ $row->values }}] -->
                    @endforeach
                    @else
                     Not record found
                    @endif
                  </ul>
                </div>
                <div class="col-8 col-xl-9 connected-list-group">
                  <div class="row">
                    @foreach($categories as $key => $row)

                      @php($displayStyle = "display:block")
                      @if($row->id == 14 || $row->id == 6) <!-- Net Operating Income -->
                        @php($displayStyle = "display:none")
                      @endif

                        @php($labelCategory = '')
                        @if($row->parent_category_id == 6)
                          @php($labelCategory = 'Employee Costs: ')
                        @endif
                        <div class="col-5 col-lg-4" style="{{ $displayStyle  }}">
                          <strong class="mb-1 clearfix d-block">{{ $labelCategory }}{{ $row->name}}
                          </strong>
                          <ul id="listCat{{ $row->id }}" data-category_id="{{ $row->id }}" class="accounts_list verticals-sortable list-group connected-selected list-group-sm ui-sortable">
                              @foreach($mappingList as $mappingKey => $mappingRow)

                                  @if($mappingRow->category_id == $row->id)
                                      <li class="list-group-item ui-sortable-handle" data-account_id="{{ $mappingRow->accounts_id }}" data-values="{{ $mappingRow->values }}"> {{ $mappingRow->account_name }} <button class="float-right btn-move"><i class="fas fa-chevron-right"></i></li>
                                        <!-- [values => {{ $mappingRow->values }} , account_id => {{ $mappingRow->account_id }} ] -->
                                  @endif
                              @endforeach
                          </ul>
                        </div>
                      
                    @endforeach
                  </div>

                </div>
            </div>
        </div>
      </div>

      <div class="card-footer text-right">
        <button class="btn btn-sm btn-success px-3" id="btnSaveVerticals">Save</button>
      </div>
      </div>
    </div>

    <script type="text/javascript">
      $( document ).ready(function() {
        window.workspaceScript.onLoad();
      });

        $('#btnSaveVerticals,#btnSaveModels').click(function () {
          saveMapAccount();
          /*flag = 0;
          $( "#clVerticals li" ).each(function( index ) {
            if($( this ).data('values') != '') {
              flag = flag+1;
            }
          });

          if(flag > 0) {
              strawberry.toast.show("Please map account with category", "warning");
              strawberry.toast.hide();
              return false;
          } else {
              saveMapAccount();
          }*/
        });

        function saveMapAccount() {
          var _btn = $('#btnSaveVerticals,#btnSaveModels');
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

          /*var categoryIdArr = new Array();
          $('.accounts_list').each(function () {
            var accountIdArr = new Array();
            var id = $(this).data('category_id');

            $('#listCat'+id+' li').each(function () {
              //console.log(id+' '+$(this).data('account_id'));
              accountIdArr.push($(this).data('account_id'));
            });
            categoryIdArr.push(accountIdArr);
          });
          console.log(categoryIdArr);*/

          var categoryIdArr = new Array(); accountIdArr=[],i=0;
          $('.accounts_list').each(function () {
            var cat_id = $(this).data('category_id');

            $(this).find("li").each(function () {
              accountIdArr[i] = $(this).data('account_id');
              i++;
            });
            categoryIdArr[cat_id] = accountIdArr;
            accountIdArr=[],i=0;
          });
          console.log(categoryIdArr);

          $.ajax({
            url: '{{ route("save_account_mapping") }}',
            type: 'POST',
            data: { 
              "_token": "{{ csrf_token() }}",
              'categoryIdArr' : categoryIdArr
            },
            success: function (result) {   
              strawberry.toast.show("Information saved successfully", "success");
              _btn.text('Save');
              _btn.removeClass('btn-processing');
              _btn.prop('disabled', false);

              _btn.popover('dispose');
              strawberry.toast.hide();
                
            }
          });
        }

    </script>
@endsection