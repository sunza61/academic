<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>ACADEMIC SERVICE</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if(session('success'))
  <meta name="flash-success" content="{{ session('success') }}">
  @endif
  @if(session('error'))
  <meta name="flash-error" content="{{ session('error') }}">
  @endif

  <link href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('adminlte/font/psu-stidti.css?')}}{{sha1(time())}}" rel="stylesheet">
  <link href="{{ asset('adminlte/dist/css/adminlte.min.css?')}}{{sha1(time())}}" rel="stylesheet">
  <link href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
  <link href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}" rel="stylesheet">


  <style>
    .bg-custom-dark {
      background-color: #2c3136 !important;
    }

    .main-header .nav-link {
      font-weight: 400;
      font-size: 15px;
    }
  </style>
</head>

<body class="layout-top-nav text-sm">
  <div class="wrapper">

    @auth
    @include('layouts.header_all')
    @endauth
    <div class="content-wrapper">
      <div class="content pt-4">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </div>

    @include('layouts.footer')

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/custom-crud.js') }}?v={{ time() }}"></script>
    @yield('script')

</body>

</html>