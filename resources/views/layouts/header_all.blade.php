<nav class="main-header navbar navbar-expand-md navbar-dark bg-custom-dark text-sm border-bottom-0">
  <div class="container-fluid">
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
      <span class="brand-text font-weight-bold text-white tracking-wide">ACADEMIC SERVICE</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav ml-auto">

        @guest
          @if (Route::has('login'))
          <li class="nav-item">
            <a href="{{ route('login') }}" class="nav-link">เข้าสู่ระบบ</a>
          </li>
          @endif
        @else

          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              หน้าแรก 
              @if(auth()->user()->hasRole("user")) (User) 
              @elseif(auth()->user()->hasRole("staff")) (เจ้าหน้าที่) 
              @elseif(auth()->user()->hasRole("manager")) (ผู้บริหาร) 
              @elseif(auth()->user()->hasRole("admin")) (admin) 
              @endif
            </a>
          </li>

          @if(auth()->user()->hasRole("admin") || auth()->user()->hasRole("staff"))
          <li class="nav-item dropdown">
            <a id="masterDataDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle {{ request()->is('master-data/*') ? 'active' : '' }}">
              ข้อมูลพื้นฐาน
            </a>
            <ul aria-labelledby="masterDataDropdown" class="dropdown-menu border-0 shadow">
              <li><a href="{{ route('master-data.project-types.index') }}" class="dropdown-item">ข้อมูลประเภทโครงการ</a></li>
              <li><a href="{{ route('master-data.employers.index') }}" class="dropdown-item">ข้อมูลผู้ว่าจ้าง</a></li>
              <li><a href="{{ route('master-data.target-groups.index') }}" class="dropdown-item">ข้อมูลกลุ่มเป้าหมาย</a></li>
              <li><a href="{{ route('master-data.budget-categories.index') }}" class="dropdown-item">หมวดหมู่งบประมาณ</a></li>
              <li><a href="{{ route('master-data.externals.index') }}" class="dropdown-item">ข้อมูลบุคคลภายนอก</a></li>
              <li><a href="{{ route('master-data.project-positions.index') }}" class="dropdown-item">ข้อมูลตำแหน่งในโครงการ</a></li>
              <li><a href="{{ route('master-data.budget-incomes.index') }}" class="dropdown-item">หมวดหมู่รายรับ</a></li>

              <li><a href="{{ route('master-data.sdgs.index') }}" class="dropdown-item">ตั้งค่า SDGs</a></li>
              <li class="dropdown-divider"></li>
              <li><a href="#" class="dropdown-item">จัดการผู้ใช้งาน</a></li>
            </ul>
          </li>
          @endif

          <li class="nav-item">
            <a href="{{ route('projects.select-type') }}" class="nav-link {{ request()->is(['projects/*','trainings/*']) ? 'active' : '' }}">จัดโครงการ/รับงานบริการวิชาการ</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('trainings.projects.index') }}" class="nav-link {{ request()->is('trainings2/*') ? 'active' : '' }}">เปิดให้บริการวิชาการ</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('deliveries.index') }}" class="nav-link">บันทึกรายงาน</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('reports.index') }}" class="nav-link">รายงาน</a>
          </li>

          @if(auth()->user()->hasRole("admin"))
          <li class="nav-item">
            <a href="{{ route('admin.approvals.index') }}" class="nav-link {{ request()->routeIs('admin.approvals.*') ? 'active' : '' }}">
              พิจารณาอนุมัติโครงการ
              @if(isset($pendingCount) && $pendingCount > 0)
                <span class="badge badge-warning ml-1">{{ $pendingCount }}</span>
              @endif
            </a>
          </li>
          @endif

          <li class="nav-item">
            <a href="#" class="nav-link">ค้นหา/ตรวจสอบสถานะ</a>
          </li>

          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle font-weight-bold text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i> โปรไฟล์ของฉัน</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt mr-2"></i> {{ __('ออกจากระบบ') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
          </li>

        @endguest
      </ul>
    </div>
  </div>
</nav>

<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>