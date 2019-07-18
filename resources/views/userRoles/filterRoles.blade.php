@foreach($userRoles as $key => $row)
  <a class="list-group-item list-group-item-action">
    <div class="media-body">
      <h6 class="mt-0 mb-1">
        <small class="badge badge-pill badge-success float-right mt-1">{{isset($user_count[$row['id']])?$user_count[$row['id']]:0}}</small>
        {{$row['name']}}
      </h6>
    </div>
  </a>
@endforeach