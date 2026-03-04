<aside class="main-sidebar elevation-4 sidebar-light-primary">
  <a href="./" class="brand-link">
    <i class="fas fa-laptop-code fa-1x"></i>
    <span class="brand-text font-weight-light">MASTER</span>
  </a>
  <div class="sidebar">
    @section('sidebar')
    @guest
    @if (Route::has('login'))
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <p>
              xxxxxxxxx
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <p>
              xxxxxxxxxxxxx
            </p>
          </a>
        </li>
      </ul>
    </nav>
    @endif
    @else
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @can('permission_1')
        <li class="nav-item has-treeview menu">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-qrcode"></i>
            <p>
              xxxxxxxx
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>xxxxxxxxxxxxxx</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>xxxxxxxxxxxxxx</p>
              </a>
            </li>
          </ul>
        </li>
        @endcan
        @can('permission_2')
        <li class="nav-item has-treeview menu">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>
              xxxxxxxxxxxxxxxxxxxx
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>xxxxxxxxxxxxxx</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>xxxxxxxxxxxxxxxxxxxxx</p>
              </a>
            </li>
          </ul>
        </li>
        @endcan
        @can('permission_3')
        <li class="nav-item has-treeview menu">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-history"></i>
            <p>
              xxxxxxxxxxxxxxxxxxxx
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>xxxxxxxxxxxxxxxxxxx</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>xxxxxxxxxxxxxxxxxxxxxxx</p>
              </a>
            </li>
          </ul>
        </li>
        @endcan
        @can('permission_4')
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fab fa-atlassian"></i>
            <p>
              xxxxxxxxxxxx
            </p>
          </a>
        </li>
        @endcan
        @can('permission_5')
        <li class="nav-item">
          <a href="getjob" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>
              xxxxxxxxxxxxx
            </p>
          </a>
        </li>
        @endcan
        @csrf
        @endguest
        @show
  </div>
</aside>