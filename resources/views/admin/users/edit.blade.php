@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-user-edit"></i> แก้ไขข้อมูลผู้ใช้งาน: {{ $user->name }}</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-2">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card shadow-sm border-0 mt-3">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>ชื่อ - สกุล <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required placeholder="ระบุชื่อและนามสกุล">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required placeholder="ระบุชื่อผู้ใช้งานสำหรับเข้าระบบ">
                    @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>รหัสบุคลากร <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('personid') is-invalid @enderror" name="personid" value="{{ old('personid', $user->personid) }}" required placeholder="ระบุรหัสบุคลากร">
                    @error('personid')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required placeholder="example@psu.ac.th">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>รหัสผ่านใหม่ <small class="text-muted">(กรอกเฉพาะกรณีต้องการเปลี่ยนรหัสผ่าน)</small></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="อย่างน้อย 6 ตัวอักษร (ปล่อยว่างถ้าไม่เปลี่ยน)">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>สิทธิการใช้งานระบบ (Role) <span class="text-danger">*</span></label>
                    <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                        <option value="">-- เลือกสิทธิการใช้งาน --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ (old('role', $userRole) == $role->name) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                ยกเลิก
            </a>
        </div>
    </form>
</div>
@endsection
