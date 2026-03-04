<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>ACADEMIC SERVICE</title>
  <link href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('adminlte/font/psu-stidti.css?')}}{{sha1(time())}}" rel="stylesheet">
  <link href="{{ asset('adminlte/dist/css/adminlte.min.css?')}}{{sha1(time())}}" rel="stylesheet">
  <link href="{{ asset('adminlte/plugins/ionicons/ionicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
  <link href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}" rel="stylesheet">
  <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
</head>

<body class="control-sidebar-slide-open sidebar-collapse text-sm">
  <div class="wrapper">
    @include('layouts.header_all')
    <div class="content-wrapper">
      <div class="content">
        <div class="container-fluid">
          @yield('content')
          <div class="container-fluid"></div>
        </div>
      </div>
    </div>
    @include('layouts.footer')
  </div>
  @yield('script')
</body>

</html>