<nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
  <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
    <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"
      class="brand-image img-circle elevation-3"
      style="opacity: .8; border-radius: 50%; width: 40px; height: 40px; object-fit: cover;">
    &nbsp;&nbsp;
    <span class="brand-text scire-text">SCIRE</span>
  </a>

  <ul class="navbar-nav ml-auto">
    @guest
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{route('academic-list')}}" class="nav-link {{ request()->routeIs('academic-list', 'researcher') ? 'active' : '' }}">List Academic</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
    <a href="{{route('research-area-list')}}" class="nav-link {{ request()->routeIs('research-area-list', 'research-area-detail') ? 'active' : '' }}">Research Areas</a>
    </li>
   

    @if (Route::has('login'))
    <li class="nav-item">
      <a href="{{ route('login') }}" class="nav-link">Login</a>
    </li>
    @endif

    @else
    @if(auth()->user()->hasRole("admin"))
    <li class="nav-item d-none d-sm-inline-block">
    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">List Academic</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Research Areas</a>
    </li>
    @endif
    @if(auth()->user()->hasRole("manager"))
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">List Academic</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
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