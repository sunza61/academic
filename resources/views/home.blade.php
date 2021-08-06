@extends('layouts.main_all')

@section('content')


                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @role('admin')
                    
                    <div class="btn btn-primary btn-lg">
                          You have admin Access
                        </div>
                        
                    @elserole('manager')
                        <div class="btn btn-primary btn-lg">
                          You have manager Access
                        </div>
                    @elserole('technician')
                        <div class="btn btn-primary btn-lg">
                          You have technician Access
                        </div>
                    @else
                        <div class="btn btn-info btn-lg">
                          You have User Access
                        </div>
                    @endrole
                    <br>
                   
                    
                    <!--
                    LINK>
                    <br>
                    
                    <a href="admin" class="nav-link">admin</a> 
                    <a href="manager" class="nav-link">manager</a> 
                    <a href="technician" class="nav-link">technician</a> 
                    <a href="user" class="nav-link">user</a>
-->
                </div>
  
@endsection
