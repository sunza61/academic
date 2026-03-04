<nav class="main-header navbar navbar-expand-md navbar-dark text-sm">
  <div class="container-fluid">
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
      <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"
        class="brand-image img-circle elevation-3"
        style="opacity: .8; border-radius: 50%; width: 40px; height: 40px; object-fit: cover;">
      &nbsp;&nbsp;
      <span class="brand-text academic-text">ACADEMIC SERVICE</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav ml-auto">
        @guest
        @if (Route::has('login'))
        <li class="nav-item">
          <a href="{{ route('login') }}" class="nav-link">Login</a>
        </li>
        @endif

        @else
        @if(auth()->user()->hasRole("admin"))
        <li class="nav-item">
          <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">หน้าแรก</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">ข้อมูลพื้นฐาน</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('projects.create') }}" class="nav-link {{ request()->routeIs('projects.create') ? 'active' : '' }}">จัดโครงการ/รับงานบริการวิชาการ</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">เปิดให้บริการวิชาการ</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">บันทึกรายงาน</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">รายงาน</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">ค้นหา/ตรวจสอบสถานะ</a>
        </li>
        @endif
        @if(auth()->user()->hasRole("manager"))
        <li class="nav-item">
          <a href="#" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">List Academic</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Research Areas</a>
        </li>
        @endif
        <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ Auth::user()->name }}
          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        </li>
        @endguest
      </ul>
</nav>