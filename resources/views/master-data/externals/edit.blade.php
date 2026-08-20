@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">แก้ไขข้อมูลผู้ว่าจ้าง/บุคคลภายนอก</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.externals.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">แบบฟอร์มแก้ไขข้อมูลผู้ว่าจ้าง/บุคคลภายนอก</h3>
    </div>

    <form action="{{ route('master-data.externals.update', $external->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="prefix_id">คำนำหน้า <span class="text-danger">*</span></label>
                    <select class="form-control select2-basic @error('prefix_id') is-invalid @enderror" id="prefix_id" name="prefix_id" required style="width: 100%;">
                        <option value="">-- เลือก --</option>
                        @foreach($prefixes as $prefix)
                            <option value="{{ $prefix->id }}" {{ old('prefix_id', $external->prefix_id) == $prefix->id ? 'selected' : '' }}>
                                {{ $prefix->name_th }}
                            </option>
                        @endforeach
                    </select>
                    @error('prefix_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-5 form-group">
                    <label for="firstname">ชื่อ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ old('firstname', $external->firstname) }}" required autofocus>
                    @error('firstname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-5 form-group">
                    <label for="lastname">นามสกุล <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname', $external->lastname) }}" required>
                    @error('lastname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12 form-group">
                    <label for="department">สังกัด/หน่วยงาน <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department', $external->department) }}" placeholder="เช่น มหาวิทยาลัยสงขลานครินทร์" required>
                    @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="phone">เบอร์โทรศัพท์</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $external->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="email">อีเมล</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $external->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 form-group">
                    <label for="description">รายละเอียดเพิ่มเติม</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $external->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 form-group mt-2">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $external->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> อัปเดตข้อมูล</button>
            <a href="{{ route('master-data.externals.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/master-data/externals/edit.js?v=' . time()) }}"></script>
    
    
@endsection