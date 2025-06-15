@extends('layouts.main_all')
@section('content')
@if(session()->get('warning'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please check your username password.!',
    })
</script>
@elseif(session()->get('rights'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'คุณไม่ได้รับสิทธิเข้าถึง',
    })
</script>
@endif
<br><br><br>
<div class="row">
    <div class="col"></div>
    <div class="row justify-content-center">
        
        <div class="card card-secondary">
            <div class="card card-header">
                <h3 class="card-title">Sign in</h3>
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
                    <button type="submit" class="btn btn-primary">Login</button>
                    <div>
                        <br>
                        <label>Note : </label> <a> If you are unable to log in, please contact 8071.</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br>
    <div class="col"></div>
</div>
@endsection