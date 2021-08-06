@extends('layouts.main_all')

@section('content')
<div class="row">
    <div class="col"></div>
    <div class="row justify-content-center">
        
        <div class="card card-secondary">
            <div class="card card-header">
                <h3 class="card-title">เข้าสู่ระบบ</h3>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="PSUPassport">PSU Passport</label>
                        <input id="username" type="username" class="form-control" name="username" placeholder="Enter PSU Passport" value="{{ old('username') }}" required autocomplete="username" autofocus>
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Enter Password" value="{{ old('password') }}" required autocomplete="password" autofocus>
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                    <div>
                        <br>
                        <label>หมายเหตุ</label> <a> หากไม่สามารถเข้าสู่ระบบได้ติดต่อ 8071</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br>
    <div class="col"></div>
</div>
@endsection