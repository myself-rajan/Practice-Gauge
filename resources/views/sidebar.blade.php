 @section('content')
 <div id="sidebar-wrapper" class="d-none d-lg-block">
      <ul class="sidebar-nav">
        <li class="sidebar-brand">
          <a href="/" class='p-0'>

            <img class="sidebar-logo" src="/assets/img/logo.svg" />
          </a>
        </li>
        <li class="sidebar-user clearfix">
          <span class="sidebar-user-image">
            <img src="assets/img/astrid_farnsworth.jpg" />
          </span>
          <span class="sidebar-user-name">
            <h6 class="mb-0">Astrid Farnsworth</h6>
            <small class="sidebar-user-role">Admin</small>
          </span>
          <a class="sidebar-user-edit float-right" data-toggle="tooltip" title="Edit Profile" data-placement="bottom"><i
              class="fas fa-pencil-alt fa-fw"></i></a>
        </li>
        <li class="d-none">
          <a href="#" data-workspace-src="empty" data-title="Coming Soon - My Practice Gauge"
            data-page-header="Redirect">
            Empty
          </a>
        </li>
        <li class="active">
          <a href="#" data-workspace-src="home" data-title="Home - My Practice Gauge" data-page-header="Home">
            <i class="fas fa-home fa-fw mr-1"></i> Home
          </a>
        </li>
        <li class="clearfix">
          <a href="#" data-workspace-src="accounts" data-title="Accounts - My Practice Gauge"
            data-page-header="Accounts">
            <i class="fas fa-user fa-fw mr-1"></i> Accounts
          </a>

        </li>

        <li class="clearfix">
          <a href="#" data-workspace-src="practices" data-title="Practices - My Practice Gauge"
            data-page-header="Practices">
            <i class="fas fa-hospital fa-fw mr-1"></i> Practices
          </a>

        </li>


        <li class="clearfix">
          <a href="#" data-workspace-src="UserwithDetail" data-title="Users - My Practice Gauge"
            data-page-header="Users">
            <i class="fas fa-users fa-fw mr-1"></i> Users
          </a>

        </li>

        <li class="clearix">
          <div class="submenu-wrapper">
            <a class="" data-toggle="submenu" data-target="#ddSettings" aria-haspopup="true" aria-expanded="false"
              href="#">
              <i class="fas fa-cogs fa-fw mr-1"></i> Settings
              <span class="fas fa-caret-right float-right"></span>
            </a>
            <div class="submenu" id="ddSettings" data-parent=".sidebar-nav">
                <a class="submenu-item" href="#" data-workspace-src="accountsmapping"
                data-title="Account Mapping - My Practice Gauge" data-page-header="Account Mapping">Account mapping</a>
              <a class="submenu-item d-none" href="#" data-workspace-src="categories"
                data-title="Categories - My Practice Gauge" data-page-header="Categories">Categories</a>
              <a class="submenu-item d-none" href="#" data-workspace-src="specialities"
                data-title="Specialities - My Practice Gauge" data-page-header="Specialities">Specialities</a>
              <a class="submenu-item" href="#" data-workspace-src="qbonew"
                data-title="QBO Connection - My Practice Gauge" data-page-header="QBO Connection">QBO Connection</a>
              <a class="submenu-item" href="#" data-workspace-src="generalsettings"
                data-title="General Settings - My Practice Gauge" data-page-header="General Settings">General
                Settings</a>
              <a class="submenu-item" href="#" data-workspace-src="userRoles"
                data-title="User Roles - My Practice Gauge" data-page-header="User Roles">User Roles</a>

            </div>
          </div>
        </li>

      </ul>
    </div>
@endsection