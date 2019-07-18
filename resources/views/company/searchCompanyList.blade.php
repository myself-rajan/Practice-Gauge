<div class="list-group rounded mb-4" id="company-content">
  @if(isset($companies) && sizeof($companies) > 0)
  @foreach($companies as $company)
    <button type="button" class="list-group-item" onclick="companyRedirect('{{$company->id}}')" >
      	{{ $company->name }}
        @if($search_from == 'auth')
        		@if(isset(Session::get('company')['company_id']) && Session::get('company')['company_id'] == $company->id )
        		<span class="text-success float-right">(Current)</span>
        		@elseif($company->qbo_connection == 0)
        		<i class="float-right far fa-dot-circle text-danger animated pulse infinite" data-toggle="tooltip" title="This company has problesm syncing to QuickBooks"></i>
        		@endif
        @endif
        <small class="text-muted d-block font-italic">Last used: {{ date('M d, Y', strtotime($company->updated_at)) }}</small>
    </button>
  @endforeach
  @endif
</div>