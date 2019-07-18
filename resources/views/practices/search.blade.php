<div class="list-group">
  @foreach($practicesList as $row)

  <a class="list-group-item list-group-item-action">
    <span data-toggle="modal"  id="mEditPractice_model" data-target="#mEditPractice" onclick='editPracticeDetails("{{$row->id}}", "{{$row->company_id}}")' style="cursor: pointer;color: blue">
      <h6>
        {{ $row->name }}
      </h6>
    </span>
    @if($row->active == 1)
      <button class="btn btn-sm btn-secondary float-right mr-1" id="sendLogin" data-id="{{$row->id}}" data-toggle="tooltip" title="Send client login" onclick='sendLogin("{{$row->id}}")' style="margin-top: -30px;">
        <i class="fas fa-paper-plane"></i>
      </button>
    @else
      <small class="d-block text-warning float-right mr-1" data-toggle="tooltip" title="Waiting for client to accept invite and configure." style="margin-top: -20px;">waiting for client</small>
    @endif 
  </a>
  @endforeach
</div>