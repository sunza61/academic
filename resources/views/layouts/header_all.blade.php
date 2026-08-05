<style>
  /* 1. เมื่อเอาเมาส์ไปเลือก/ชี้ (Hover) ให้เข้มขึ้นนิดหน่อยกำลังดี ไม่มืดเกินไป */
  .navbar-nav .dropdown-menu .dropdown-item:hover,
  .navbar-nav .dropdown-menu .dropdown-item:focus {
    background-color: #e9ecef !important;
    color: #1a202c !important;
  }

  /* 2. หน้าปัจจุบันที่อยู่ (Active) ให้สีเข้มชัดเจนขึ้นอย่างเหมาะสม */
  .navbar-nav .dropdown-menu .dropdown-item.active {
    background-color: #d6d8db !important;
    color: #111827 !important;
    font-weight: 600;
  }
</style>

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
              @elseif(auth()->user()->hasRole("finance")) (การเงิน) 
              @elseif(auth()->user()->hasRole("plan")) (งานแผน) 
              @endif
            </a>
          </li>

          @if(auth()->user()->hasAnyRole(['admin', 'staff', 'user', 'finance', 'plan', 'manager']))
          <li class="nav-item dropdown">
            <a id="projectDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle {{ request()->is(['projects/*','trainings/*','trainings2/*']) || request()->routeIs('admin.approvals.*') ? 'active' : '' }}">
              จัดการโครงการ
              @if(auth()->user()->hasRole("admin") && isset($pendingCount) && $pendingCount > 0)
                <span class="badge badge-warning ml-1">{{ $pendingCount }}</span>
              @endif
            </a>
            <ul aria-labelledby="projectDropdown" class="dropdown-menu border-0 shadow">
              
              <li><a href="{{ route('projects.select-type') }}" class="dropdown-item {{ request()->is(['projects/*','trainings/*']) ? 'active' : '' }}">จัดโครงการ/รับงานบริการวิชาการ</a></li>
              
              @if(auth()->user()->hasAnyRole(['admin', 'staff', 'user', 'manager']))
              <li><a href="{{ route('trainings.projects.index') }}" class="dropdown-item {{ request()->is('trainings2/*') ? 'active' : '' }}">เปิดให้บริการวิชาการ</a></li>
              <li><a href="{{ route('deliveries.index') }}" class="dropdown-item">บันทึกรายงาน</a></li>
              @endif

              @if(auth()->user()->hasRole("admin"))
              <li class="dropdown-divider"></li>
              <li>
                <a href="{{ route('admin.approvals.index') }}" class="dropdown-item {{ request()->routeIs('admin.approvals.*') ? 'active' : '' }}">
                  พิจารณาอนุมัติโครงการ
                  @if(isset($pendingCount) && $pendingCount > 0)
                    <span class="badge badge-warning ml-1">{{ $pendingCount }}</span>
                  @endif
                </a>
              </li>
              @endif

            </ul>
          </li>
          @endif

          <li class="nav-item dropdown">
            <a id="reportDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle {{ request()->routeIs(['reports.index', 'finance.dashboard', 'plan.dashboard']) ? 'active' : '' }}">
              ติดตาม & รายงาน
            </a>
            <ul aria-labelledby="reportDropdown" class="dropdown-menu border-0 shadow">
              <li><a href="#" class="dropdown-item">ค้นหา/ตรวจสอบสถานะ</a></li>
              <li><a href="{{ route('reports.index') }}" class="dropdown-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">รายงาน</a></li>

              @if(auth()->user()->hasRole("finance") || auth()->user()->hasRole("admin"))
              <li class="dropdown-divider"></li>
              <li><a href="{{ route('finance.dashboard') }}" class="dropdown-item {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">ตรวจสอบงบประมาณ</a></li>
              @endif

              @if(auth()->user()->hasRole("plan") || auth()->user()->hasRole("admin"))
              <li><a href="{{ route('plan.dashboard') }}" class="dropdown-item {{ request()->routeIs('plan.dashboard') ? 'active' : '' }}">ตรวจสอบแผนงาน</a></li>
              @endif
            </ul>
          </li>

          @if(auth()->user()->hasRole("admin") || auth()->user()->hasRole("staff"))
          <li class="nav-item dropdown">
            <a id="masterDataDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle {{ request()->is('master-data/*') ? 'active' : '' }}">
              ข้อมูลพื้นฐาน
            </a>
            <ul aria-labelledby="masterDataDropdown" class="dropdown-menu border-0 shadow">
              <li><a href="{{ route('master-data.project-types.index') }}" class="dropdown-item {{ request()->routeIs('master-data.project-types.*') ? 'active' : '' }}">ข้อมูลประเภทโครงการ</a></li>
              <li><a href="{{ route('master-data.employers.index') }}" class="dropdown-item {{ request()->routeIs('master-data.employers.*') ? 'active' : '' }}">ข้อมูลผู้ว่าจ้าง</a></li>
              <li><a href="{{ route('master-data.target-groups.index') }}" class="dropdown-item {{ request()->routeIs('master-data.target-groups.*') ? 'active' : '' }}">ข้อมูลกลุ่มเป้าหมาย</a></li>
              <li><a href="{{ route('master-data.budget-categories.index') }}" class="dropdown-item {{ request()->routeIs('master-data.budget-categories.*') ? 'active' : '' }}">หมวดหมู่งบประมาณ</a></li>
              <li><a href="{{ route('master-data.externals.index') }}" class="dropdown-item {{ request()->routeIs('master-data.externals.*') ? 'active' : '' }}">ข้อมูลบุคคลภายนอก</a></li>
              <li><a href="{{ route('master-data.project-positions.index') }}" class="dropdown-item {{ request()->routeIs('master-data.project-positions.*') ? 'active' : '' }}">ข้อมูลตำแหน่งในโครงการ</a></li>
              <li><a href="{{ route('master-data.budget-incomes.index') }}" class="dropdown-item {{ request()->routeIs('master-data.budget-incomes.*') ? 'active' : '' }}">หมวดหมู่รายรับ</a></li>

              <li><a href="{{ route('master-data.sdgs.index') }}" class="dropdown-item {{ request()->routeIs('master-data.sdgs.*') ? 'active' : '' }}">ตั้งค่า SDGs</a></li>
              <li class="dropdown-divider"></li>
              <li><a href="#" class="dropdown-item">จัดการผู้ใช้งาน</a></li>
            </ul>
          </li>
          @endif

          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle font-weight-bold text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="navbarDropdown">
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