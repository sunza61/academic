@extends('layouts.main_all')

@section('content')


                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @role('role_1')

                    <div class="btn btn-primary btn-lg">
                          You have role_1 Access
                        </div>

                    @elserole('role_2')
                        <div class="btn btn-primary btn-lg">
                          You have role_2 Access
                        </div>
                    @elserole('role_3')
                        <div class="btn btn-primary btn-lg">
                          You have role_3 Access
                        </div>
                    @else
                        <div class="btn btn-info btn-lg">
                          You have role_4 Access
                        </div>
                    @endrole
                    <br>
                </div>

@endsection
