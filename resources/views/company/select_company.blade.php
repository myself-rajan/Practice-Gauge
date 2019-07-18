@extends('layouts.auth_layout')

@section('content')
  <div id="loginSlider" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <!-- Select Company panel -->
        <div class="my-2">
          <h4 class="text-center mb-3">Hi, {{$user->first_name}}
            <small class="d-block text-muted">Select a Practice to continue</small>
          </h4>
          <form class="my-2 mb-0">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="org_id" id="org_id" value="{{ $org_id }}">

           <input class="form-control" id="search-content" type="text" placeholder="Search..." placeholder="Search" onkeyup="search_value()">
          </form>
          <div class="list-group rounded mb-4"  id="secCompanyList" style="overflow-y: scroll;height: 200px;">
           @foreach($company_select as $company)
            <button type="button" class="list-group-item" 
            onclick="companyRedirect({{$company['id']}})">
               {{$company['name']}}
              <small class="d-block text-muted">Last used: {{ date('M d, Y', strtotime($company['updated_at'])) }}</small>
            </button>
           @endforeach
          </div>
        </div>

        <div style="margin-left:-25px;margin-right:-25px" class="py-3 text-center ">
          @if(Auth::user()->role_id == 1)
            <a class="btn btn-outline-primary mb-1 shadow-sm" id="btnLogout" 
            href='{{ route("select_organization") }}' style="width: 71px">
              Back
            </a>&nbsp;&nbsp;&nbsp;&nbsp;
          @endif
          <button class="btn btn-outline-primary mb-1 shadow-sm" id="btnLogout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            Logout
          </button>
           <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
            </form>
        </div>

        <div style="margin-left:-25px;margin-right:-25px" class="py-3 text-center d-none">
          <button class="btn btn-light rounded mb-1 shadow-sm">
            Start a new company subscription
          </button>
        </div>
      </div>
    </div>
  </div>

@endsection
<script type="text/javascript">
  function companyRedirect(company_id){
    
    $.ajax({
      url: '{{ route("company_redirect") }}',
      type: 'GET',
      data: {'company_id' : company_id,
              'org_id' : $('#org_id').val() },
      success: function(response) {
        if(response.steps == 1){
          window.location.href = '{{ route("home") }}';
        }else{
          window.location.href = '{{ route("register") }}';
        }
       
      }
    });
  }

  function search_value(){
    var search_key = $('#search-content').val();
    var search_from = 'reg';

    $.ajax({
      url: '{{route("search_company")}}',
      type: 'GET',
      data: {'search_key' : search_key, 'search_from' : search_from},
      success: function(response) {
        $('#secCompanyList').html(response)
      }
    });
  } 
</script>