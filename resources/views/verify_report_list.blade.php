@extends('layouts.app_layout')
@section('content')

<?php
use App\Models\Company;
use App\Models\User;
use App\Models\UserCompanyMap;
use Illuminate\Foundation\Auth\RegistersUsers;
?>
  <script src="{{ asset('/js/views/home.js') }} "></script>
  <link rel="stylesheet" href=" {{ asset('/css/app_modules/dashboard.css') }}">

  <div class="row"> 
      <div class="col-12 col-md-8">
        <div class="card border-0 shadow-sm mb-4 table_sm">
          <div class="card-body">
            <h4 class="gradient-title">Practices Status
            </h4>
            
              <table class="table table-sm" data-column-right=".2.3.4.5.">
                <thead class="bg-light">
                  <tr>
                    <th class="border-top-0">Practices Name</th>
                    <th class="border-top-0">Status</th>
                    @if($role_id == 1) 
                      <th class="border-top-0">Subscription</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  
                  @if($role_id == 1) 
                    @php($cpaList = User::where('deleted', '=', 0)->whereIn('role_id', [3])->get())
                  @else 
                    @php($cpaList = User::where('deleted', '=', 0)->where('id', $user_id)->get())
                  @endif
                  @foreach($cpaList as $key => $row) 
                    <tr>
                      <td><b>Organization Name: {{ $row->company_name }}</b></td>
                      <td></td>
                      @if($role_id == 1) 
                        <td>
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input subscription" name="chkUserActive_{{ $key }}" data-userid="{{ $row->id }}" id="chkUserActive_{{ $key }}" tabindex="6" <?php if($row->is_subscription == 1) {?> checked <?php }?>>
                            <label class="custom-control-label" for="chkUserActive_{{ $key }}" style="margin-bottom: 20px;"></label>
                          </div>
                        </td>
                      @endif
                    </tr>
                    <?php 
                    //$companyList = User::join('company', 'company.user_id', '=' , 'users.id')->where('users.deleted', '=', 0)->where('users.parent_id', '=', $row->id)->get();
                    $companyList = UserCompanyMap::join('company', 'company.id', '=', 'user_company_mapping.company_id')->where('user_company_mapping.user_id', $row->id)->select('company.*')->get();
                    ?>
                    @foreach($companyList as $cRow)
                      <tr>
                          <td>{{ $cRow->name }}</td>
                        @if($cRow->is_report_verified == 1)
                          
                          <td style="color: lightgreen">Verified</td>
                        @else
                          <td>Yet to verify</td>
                        @endif
                          <td></td>
                      </tr>
                    @endforeach
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                  </tr>
                </tfoot>
              </table> 
          </div>
        </div>
      </div>
  </div>

  <script type="text/javascript">
    $('.subscription').click(function() {
      var user_id = $(this).data('userid');
      if($(this).prop("checked") == true){
        checked = 1;
      } else {
        checked = 0;
      }
      strawberry.dialog.confirm({
        title: 'Subscription Status',
        body : 'Are you sure you want to change subscription status?',
        yes  : confirmReport(user_id, checked),
      })
    });

    function confirmReport(user_id, checked) {   
      $.ajax({
        url: "{{ route('update_subscription') }}",
        type: 'POST',
        data: {
          user_id: user_id,
          checked : checked,
          "_token": "{{ csrf_token() }}",
        },

        success: function(response) { 
        }
      });
    }
  </script>
 @endsection