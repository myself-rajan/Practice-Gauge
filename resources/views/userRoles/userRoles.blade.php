@extends('layouts.app_layout')

@section('content')
<div class="row ">
  <div class="col-sm-6 col-lg-4">
    <div class="card bg-white border-0 shadow-sm rounded">
      <div class="card-body">
        <i class="fas fa-info-circle mr-1 text-info"></i>
        Below is a list of available user roles
      </div>

      <div class="card-body border-top">
        <div class="form-group mb-2">
          <input class="form-control form-control-sm" id="userRoles" type="text" onkeyup="searchRoles()" placeholder="Type here to search">
        </div>
        <div class="list-group" id="userFilter">
           @foreach($userRoles as $key => $row)
	          <a class="list-group-item list-group-item-action" >
	            <div class="media-body">
	              <h6 class="mt-0 mb-1">
	                <small class="badge badge-pill badge-success float-right mt-1">{{isset($user_count[$row['id']])?$user_count[$row['id']]:0}}</small>
	                {{$row['name']}}
	              </h6>
	            </div>
	          </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	function searchRoles(){
		var userRoles = $("#userRoles").val();
		$.ajax({
      url: '{{ route("userRolesFilters") }}',
      type: 'GET',
      data: {'userRoles' : userRoles},
      success: function(response) {
        console.log(response);
        $("#userFilter").html(response);        
      }
    });
	}
</script>
@endsection
