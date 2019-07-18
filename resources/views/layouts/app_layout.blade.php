@php
  use App\Models\User;
  use App\Models\Menu;
  use App\Models\UserRoles;
  use App\Http\Controllers\CompanyController;

  $role_id = Auth::User()->role_id;
  $role= UserRoles::where('id',$role_id)->get();
  $roleName = $role[0]->name;
  $user_name = Auth::User()->first_name;
@endphp

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>My Practice Gauge</title>
    <!-- Animate.CSS CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.0/css/all.css"
      integrity="sha384-Mmxa0mLqhmOeaE8vgOSbKacftZcsNYDjQzuCOm6D02luYSzBG8vpaOykv9lFQ51Y" crossorigin="anonymous">

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <!-- jQuery-UI Framework -->
    <script src="{{asset('js\jQueryUI\jquery-ui.min.js')}}"></script>
    <link href="{{asset('js\jQueryUI\jquery-ui.structure.min.css')}}" rel="stylesheet" />
    <link href="{{asset('js\jQueryUI\jquery-ui.min.css')}}" rel="stylesheet" />

    <!-- Popper CSN (Bootstrap 4 requires this to work)-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
      integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
      crossorigin="anonymous"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    <!-- Bootstrap CDN -->

    <link href="https://bootswatch.com/4/cosmo/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Cosmo from bootswatch -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
      integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
      crossorigin="anonymous"></script>


    <!-- Bootstrap Overrides - JONES -->
    <link href="{{asset('css/bootstrap.overrides.css')}}" rel="stylesheet" />
    <link href="{{asset('css/bootstrap.xxl.css')}}" rel="stylesheet" />

    <!-- Strawberry CSS - JONES -->
    <link href="{{asset('css/strawberry.css')}}" rel="stylesheet" />
    <link href="{{asset('css/strawberry.min.768.css')}}" rel="stylesheet" />
    <!-- Responsive CSS -->
    <link href="{{asset('css/strawberry.utilities.css')}}" rel="stylesheet" />
    <link href="{{asset('css/common.css')}}" rel="stylesheet" />
    <!-- Utility CSS -->

    <!-- Strawberry JS - JONES -->
    <script src="{{asset('js/strawberry.core.js')}}"></script>
    <script src="{{asset('js/strawberry.js')}}"></script>

    <!-- Chartjs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script src="https://www.chartjs.org/samples/latest/utils.js"></script>

    <link href="{{asset('css/app_modules/connected.list.css')}}" rel="stylesheet">
    <script src="{{asset('js/views/company_industry.js')}}"></script>

    <script type="text/javascript" async="" src="{{asset('js/views/company_industry.js')}}" class="dynamic"></script>
    <script type="text/javascript">
      //Globally Set Web URL
      var l      = window.location;
      var WEBURL = l.protocol + "//" + l.host ;
      
      //Ajax Setup Token
       //Ajax Setup Token
      $(function () { 
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        })
      });
    </script>
  </head>

  <body>
    <div id="wrapper" class="toggled">     
      <div id="sidebar-wrapper" class="d-none d-lg-block">
        <ul class="sidebar-nav">
          <li class="sidebar-brand">
            <a href="/" class='p-0'>

              <img class="sidebar-logo" src="{{asset('/img/logo.svg')}}" />
            </a>
          </li>
          <li class="sidebar-user clearfix">
            <span class="sidebar-user-image">

              @php($profile_image = Auth::user()->profile_image)
              <img src="{{ isset($profile_image) ? $profile_image : asset('img/Oliver_Queen.jpg') }}" />
            </span>
            <span class="sidebar-user-name" style="width: 65%;">
              <h6 class="mb-0">{{isset($user_name)?ucfirst($user_name):''}}</h6>
              <small class="sidebar-user-role" style="line-height: 1;">{{isset($roleName)?ucfirst($roleName):''}}</small>
            </span>
            <!--  <a class="sidebar-user-edit float-right" data-toggle="modal" data-target="#exampleModal"title="Edit Profile" data-placement="bottom"><i
                class="fas fa-pencil-alt fa-fw"></i></a>
            </li> -->

            <a class="sidebar-user-edit float-right" data-toggle="modal" title="Edit Profile" data-placement="bottom" href="{{route('editUser')}}" style="margin-top: 25px;"><i
                class="fas fa-pencil-alt fa-fw"></i></a>
          </li>
          
          <li class="d-none">
            <a href="#" data-workspace-src="empty" data-title="Coming Soon - My Practice Gauge"
              data-page-header="Redirect">
              Empty
            </a>
          </li>
          <?php
            $menu_val = [];
            $sub_menu = [];
            $menu_array_query = Menu::where('deleted',0)->orderBy('order_by');

            if(Auth::user()->role_id == 1) {
              $menu_array_query->whereNotIn('id', [10]);
            }
            else if(Auth::user()->role_id == 2) {
              $menu_array_query->whereIn('id', [1,2,3]);
            }
            else if(Auth::user()->role_id == 5) {
              $menu_array_query->whereIn('id', [1,2]);
            }
            
            $menu_array = $menu_array_query->get();

            foreach($menu_array as $menu) {
                if($menu['parent_id'] != 0) {
                    $sub_menu[$menu['parent_id']][]  = $menu['menu_name'];
                    $sub_menu_url[$menu['parent_id']][]  = $menu['routes'];
                }
            }

            foreach($menu_array as $key => $menu) {
              if($menu['parent_id'] == 0) {
                $menu_val[$key]['parent']  = $menu['menu_name'];
                $menu_val[$key]['icons']   = $menu['icons'];
                $menu_val[$key]['menu_url']   = $menu['routes'];
                $menu_val[$key]['sub_menu'] = isset($sub_menu[$menu['id']])?$sub_menu[$menu['id']]:'';
                $menu_val[$key]['sub_menu_url'] = isset($sub_menu_url[$menu['id']])?$sub_menu_url[$menu['id']]:'';
              }
            }
            $menu_array = $menu_val;
          ?>

          @if(isset($menu_array))
           @foreach($menu_array as $menu) 
            <li class="clearfix menu_active">
                @if(!$menu['sub_menu'])
                  @if($menu['menu_url'] != '')
                     @php($menu_url = $menu['menu_url'])
                  @else
                    @php($menu_url = '#')
                  @endif

                  <a href="{{ ($menu_url != '#') ? route($menu_url) : route('empty_page') }}" data-workspace-src="{{$menu['parent']}}" data-title="{{$menu['parent']}} - My Practice Gauge" data-page-header="{{$menu['parent']}}">
                      <i class="{{$menu['icons']}}"></i> {{$menu['parent']}}
                  </a>
                @elseif($menu['sub_menu'])
                  <div class="submenu-wrapper">
                    <a class="" data-toggle="submenu" data-target="#ddSettings" aria-haspopup="true" aria-expanded="false" href="#">
                      <i class="{{$menu['icons']}}"></i> {{$menu['parent']}}
                      <span class="fas fa-caret-right float-right"></span>
                    </a>

                    <div class="submenu show" id="ddSettings" data-parent=".sidebar-nav">
                      @foreach($menu['sub_menu']  as $key => $sub_menu)       
                        @if($menu['sub_menu_url'][$key] != '') 
                          @php($sub_menu_url = $menu['sub_menu_url'][$key])
                        @else 
                          @php($sub_menu_url ='#')
                        @endif 

                        @if($sub_menu_url != 'qbo_integration' || Session::get('company')['company_id'] != 0) 
                          <a class="submenu-item" href="{{ ($sub_menu_url != '#') ? route($sub_menu_url) : route('empty_page') }}" data-workspace-src="{{$sub_menu}}"
                              data-title="{{$sub_menu}} - My Practice Gauge" data-page-header="{{$sub_menu}}">{{$sub_menu}}</a>
                        @endif
                      @endforeach
                    </div>
                  </div>
                @endif
              @endforeach
            @endif
        </ul>
      </div>
      <div id="page-content-wrapper">
        <div class="page-header clearfix">
          <div class="float-left">
            <i class="fas fa-bars menu-toggle"></i>
            <div class="header-title">
            {{ isset($global_page_title) ? $global_page_title : 'Home' }}
            </div>
          </div>
          <div class="float-right">
            <span class="align-items-center d-md-flex d-none justify-content-center">
              <div class="dropdown d-none d-md-inline-block dropdown-company-change">
                @if(Session::get('company')['company_id'] != 0)
                <button id="btnGroupDrop3" type="button" class="btn btn-warning btn-sm" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    @if(isset(Session::get('company')['qbo_connection']) && Session::get('company')['qbo_connection'] == 1 )
                    <span class="led green mr-2 active" data-toggle="tooltip" title="QuickBooks Connected"></span>
                    @endif
                  Company : <span class="global_company_name"> {{Session::get('company')['global_company_name']}} </span> </button>
                @endif
                <div class="dropdown-menu  dropdown-menu-right py-0 shadow-sm" aria-labelledby="btnGroupDrop3" style="max-height:270px;overflow-y: auto;width: max-content;"> 
                   
                  @if(isset(Session::get('company')['org_id']))
                    @php($user_id = Session::get('company')['org_id'])
                  @else 
                    @php($user_id = Auth::user()->id)
                  @endif

                  @php($mfaData = User::where('id', $user_id)->select('company_name')->first())

                  <input type="hidden" name="org_id" id="org_id" value="{{ $user_id }}">
                  <div class="text-muted py-1 px-2">
                    <em>Organization: {{ $mfaData->company_name}}  &nbsp;</em>
                    @if(Auth::user()->role_id == 1)
                      <a class="btn btn-sm btn-primary float-right" href="{{ route('select_organization') }}" style="border-radius: 15px;">Switch
                      </a>
                    @endif
                  </div>
                    
                  @php($companies = CompanyController::viewAllCompanies())

                  @foreach($companies as $company)
                    @if(Session::get('company')['global_company_name'] != $company->name)
                      <a class="dropdown-item list_company_{{ $company->id }}" href="#" onclick="companyRedirect({{ $company->id }})" >{{$company->name}}</a>
                    @endif
                  @endforeach
                </div>
              </div>
          
              <div class="dropdown d-inline-block">
                <div class="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v right-menu-toggle"></i>
                </div>
                <div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="{{route('editUser')}}"><i class="fas fa-user-circle mr-2"></i>Profile</a>
                  <a class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();" href="#"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                  </form>
                </div>
              </div>
            </span>
          </div>
          <div class="float-right">
          </div>
        </div>

        <div class="breadcrumb-wrapper" aria-label="Breadcrumb">
         {{ Breadcrumbs::render(Route::getCurrentRoute()->getName()) }}
        </div>

        <div class="workspace p-4" id="workspace">
          <!-- Main content is loaded here -->
          @yield('content')
        </div>
      </div>  
    </div>
  </body>
</html>

<script type="text/javascript">
  $(function () {
    setNavigation();
  });

  function setNavigation() {
    var path = window.location.href;
    $(".menu_active a").each(function () {
      var href = $(this).attr('href');
      
      if (path === href && window.location.pathname != '/empty') {
        $(this).closest('a').addClass('active');
        $(this).closest('.submenu').addClass('show');
        $(this).closest('.submenu-wrapper').addClass('open');
      }
    });
  }

  function companyRedirect(company_id) {
    var global_company_id = "{{ Session::get('company')['company_id'] }}";

    var check_role = "{{ Auth::user()->role }}";

    var roleArr = check_role.split(',');

    if($.inArray("4", roleArr) == 0) {
      return false;
    }

    if(company_id == global_company_id) {
      confirmSwitch();
      return false;
    }

    strawberry.dialog.confirm({
      title: 'Switching Company',
      body : 'Are you sure to switch this company?',
      yes  : confirmSwitch,
    })

    function confirmSwitch() {
      $.ajax({
          url: '{{route("company_redirect")}}',
          type: 'GET',
          data: {
            'company_id' : company_id,
            'org_id' : $('#org_id').val()
          },
          success: function(response) {
            window.location.href = '{{ route(\Route::currentRouteName()) }}';
          }
      });
    }
  }

</script>