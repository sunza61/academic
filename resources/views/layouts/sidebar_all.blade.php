<!-- Main Sidebar Container -->
<!--เปลี่ยนสี sidebar "main-sidebar elevation-4 sidebar-light-primary"สีขาว
"main-sidebar sidebar-dark-primary elevation-4"สีdark-->
<aside class="main-sidebar elevation-4 sidebar-light-primary">
  <!-- Brand Logo -->
  <!-- เปลี่ยนสีพื้นหลัง logo -->
  <a href="./" class="brand-link">
    <!--<img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">-->
    <i class="fas fa-laptop-code fa-1x"></i>
    <span class="brand-text font-weight-light">MASTER</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
  
    <!-- Sidebar user panel (optional) -->
    <!--<div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>-->
      
    @section('sidebar')



    @guest
    @if (Route::has('login'))
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
        with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="/sun/ldap/public/" class="nav-link">
            <p>
              ซ่อมเครื่องมือวิทยาศาสตร์
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/blownglass_index" class="nav-link">
            <p>
              บริการเป่าแก้ว
            </p>
          </a>
        </li>
      </ul>
    </nav>
    @endif
    @else
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

        @can('repairnoti')
        <li class="nav-item has-treeview menu">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-qrcode"></i>
            <p>
              แจ้งซ่อม
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="repairnoti" class="nav-link">
                <i class="far fa-circle"></i>
                <p>ซ่อมเครื่องมือวิทยาศาสตร์</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>บริการเป่าแก้ว</p>
              </a>
            </li>
          </ul>
        </li>
        @endcan
        @can('requestlist')
        <li class="nav-item has-treeview menu">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>
              รายการซ่อม
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>ซ่อมเครื่องมือวิทยาศาสตร์</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>บริการเป่าแก้ว</p>
              </a>
            </li>
          </ul>
        </li>
        @endcan
        @can('repairnotihistory')
        <li class="nav-item has-treeview menu">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-history"></i>
            <p>
              ประวัติแจ้งซ่อมทั้งคณะ
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>ซ่อมเครื่องมือวิทยาศาสตร์</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle"></i>
                <p>บริการเป่าแก้ว</p>
              </a>
            </li>
          </ul>
        </li>
        @endcan
        @can('assign')
        <li class="nav-item">
          <a href="assign" class="nav-link">
            <i class="nav-icon fab fa-atlassian"></i>
            <p>
              มอบหมายงาน
            </p>
          </a>
        </li>
        @endcan
        @can('getjob')
        <li class="nav-item">
          <a href="getjob" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>
              รับมอบหมายงาน
            </p>
          </a>
        </li>
        @endcan
        @can('workschedule')
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>
              ตารางงานช่าง
            </p>
          </a>
        </li>
        @endcan
        @can('evaluation')
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
              ผลประเมิน
            </p>
          </a>
        </li>
        @endcan
        @can('historytechnician')
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-monument"></i>
            <p>
              ประวัติการซ่อมของช่าง
            </p>
          </a>
        </li>
        @endcan
        @can('report')
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>
              รายงาน
            </p>
          </a>
        </li>
        @endcan
        @csrf
        @endguest
        @show

        <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>