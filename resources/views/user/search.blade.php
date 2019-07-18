<div class="list-unstyled list-group list-group-flush">
  @foreach($users as $row)
    <a class="media d-flex list-group-item list-group-item-action userValId" id="userValId" data-id="{{$row->id}}">
      <img class="rounded-circle bg-light mr-3 border p-1 shadow-sm"    
        style="width:52px;height:52px;position:relative;top:-2px;" src="{{ $row->profile_image }}">
      <div class="media-body">
        <h5 class="mt-0 mb-1">
        {{$row->first_name}}

        </h5>
        <div>{{$row->email}}</div>
      </div>

      <small class="d-block text-warning float-right mt-1" data-toggle="tooltip" title="" data-original-title="Waiting for client to accept invite and configure.">{{$row->name}}</small>
    </a>
  @endforeach
</div>