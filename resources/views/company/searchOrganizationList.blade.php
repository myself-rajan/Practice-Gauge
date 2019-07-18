<div class="list-group rounded mb-4"  id="secCompanyList">
 @foreach($organization as $org)
  <a type="button" class="list-group-item" 
  href="{{ route('select_company').'?org_id='.$org['id'] }}" style="text-align: center;text-decoration: none;color: black">
     {{$org['company_name']}}
  </a>
 @endforeach
</div>