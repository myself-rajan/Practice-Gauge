<div class="list-group fetch-account-data">
  @foreach($accountsList as $row)
    <a class="list-group-item list-group-item-action">
      <div class="media-body">
        <h6 class="mt-0 mb-1">
          @if($row->account_type == 'Income' || $row->account_type == 'Other Income')
            <small class="d-block text-success float-right mt-1">{{ $row->account_type }}</small>
          @else
            <small class="d-block text-danger float-right mt-1">{{ $row->account_type }}</small>
          @endif
          <strong class="mr-2">{{ $row->account_num }}</strong>
          {{ $row->account_name }}

        </h6>
      </div>
    </a>
  @endforeach
</div>

      