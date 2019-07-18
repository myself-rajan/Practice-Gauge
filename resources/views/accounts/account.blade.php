@extends('layouts.app_layout')

@section('content')
  <div class="row ">
    <div class="col-sm-12 col-lg-7">
      <div class="card bg-white border-0 shadow-sm rounded">

        <div class="card-body">
          <a class="btn btn-sm btn-primary float-right" href="{{ route('account_mapping') }}"><i
              class="fas fa-sitemap mr-1"></i> Account Mapping</a>
          <i class="fas fa-info-circle mr-1 text-info"></i>
          Below is a list of available accounts
        </div>

        <div class="card-body border-top">
          <div class="form-group mb-2">
            <input class="form-control form-control-sm search" type="text" placeholder="Type here to search">
          </div>
          <div class="list-group fetch-account-data">
            @if(isset($accountsList))
            @foreach($accountsList as $row)
              <a class="list-group-item list-group-item-action">

                <div class="media-body">
                  <h6 class="mt-0 mb-1">
                    @if($row['account_type'] == 'Income' || $row['account_type'] == 'Other Income')
                      <small class="d-block text-success float-right mt-1">{{ $row['account_type'] }}</small>
                    @else
                      <small class="d-block text-danger float-right mt-1">{{ $row['account_type'] }}</small>
                    @endif
                    <strong class="mr-2">{{ $row['account_num'] }}</strong>
                    {{ $row['account_name'] }}

                  </h6>
                </div>
              </a>
            @endforeach
            @else
              Records not found.
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-lg-4">
    </div>
  </div>

  <script type="text/javascript">
    $('.search').keyup(function() {
       $.ajax({
        url: "{{ route('search_accounts') }}",
        type: 'POST',
        data: {
          search: $('.search').val(),
          "_token": "{{ csrf_token() }}",
        },

        success: function(response) {
          $('.fetch-account-data').html(response);
        }
      });
    })
  </script>
@endsection