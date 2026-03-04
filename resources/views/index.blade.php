@extends('layouts.main_all')
@section('content')

<head>
    <link href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css?')}}{{sha1(time())}}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css?')}}{{sha1(time())}}" rel="stylesheet">


    @endsection

    @section('script')
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>

    @endsection