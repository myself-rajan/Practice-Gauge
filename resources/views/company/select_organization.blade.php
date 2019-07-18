@extends('layouts.auth_layout')

@section('content')
  <div id="loginSlider" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <!-- Select Company panel -->
        <div class="my-2">
          <h4 class="text-center mb-3">
            <small class="d-block text-muted">Select an organization</small>
          </h4>
          <form class="my-2 mb-0">
            @csrf
           <input class="form-control" id="search-content" type="text" placeholder="Search..." placeholder="Search" onkeyup="search_value()">
          </form>
          <div class="list-group rounded mb-4"  id="secCompanyList" style="overflow-y: scroll;height: 200px;">
           @foreach($organization_select as $organization)
            <a type="button" class="list-group-item" 
            href="{{ route('select_company').'?org_id='.$organization['id'] }}" style="text-align: center;text-decoration: none;">
               {{$organization['company_name']}}
            </a>
           @endforeach
          </div>
        </div>

        <div style="margin-left:-25px;margin-right:-25px" class="py-3 text-center ">
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
  function search_value(){
    var search_key = $('#search-content').val();
    var search_from = 'reg';

    $.ajax({
      url: '{{route("search_organization")}}',
      type: 'GET',
      data: {'search_key' : search_key, 'search_from' : search_from},
      success: function(response) {
        $('#secCompanyList').html(response)
      }
    });
  } 
</script>