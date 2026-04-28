@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">เพิ่มข้อมูลบุคคลภายนอก</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.externals.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ย้อนกลับ</a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <form action="{{ route('master-data.externals.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 form-group">
                    <label>คำนำหน้า <span class="text-danger">*</span></label>
                    <select class="form-control select2-basic @error('prefix_id') is-invalid @enderror" name="prefix_id" required>
                        <option value="">-- เลือก --</option>
                        @foreach($prefixes as $prefix)
                            <option value="{{ $prefix->id }}" {{ old('prefix_id') == $prefix->id ? 'selected' : '' }}>{{ $prefix->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 form-group">
                    <label>ชื่อ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" required>
                </div>
                <div class="col-md-5 form-group">
                    <label>นามสกุล <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required>
                </div>
                
                <div class="col-md-12 form-group">
                    <label>สังกัด/หน่วยงาน <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('department') is-invalid @enderror" name="department" value="{{ old('department') }}" required>
                </div>

                <div class="col-md-6 form-group">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>อีเมล</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                </div>

                <div class="col-md-12 form-group">
                    <label>รายละเอียดเพิ่มเติม</label>
                    <textarea class="form-control" name="description" rows="2">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-12 form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                    <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกข้อมูล</button>
            <a href="{{ route('master-data.externals.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
