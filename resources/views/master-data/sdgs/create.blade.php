@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">เพิ่มข้อมูล SDG</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.sdgs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">แบบฟอร์มเพิ่มข้อมูล SDG</h3>
    </div>
    
    <form action="{{ route('master-data.sdgs.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="name_th">ชื่อเป้าหมาย (ภาษาไทย) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name_th') is-invalid @enderror" id="name_th" name="name_th" value="{{ old('name_th') }}" required autofocus>
                    @error('name_th') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 form-group">
                    <label for="name_en">ชื่อเป้าหมาย (ภาษาอังกฤษ) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}" required>
                    @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="icon_url">URL ไอคอน (ถ้ามี)</label>
                <input type="text" class="form-control" id="icon_url" name="icon_url" value="{{ old('icon_url') }}" placeholder="เช่น https://example.com/sdg1.png">
            </div>

            <div class="form-group">
                <label for="description">รายละเอียดเพิ่มเติม</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="form-group custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
            </div>
        </div>
        
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกข้อมูล</button>
            <a href="{{ route('master-data.sdgs.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection
@section('script')
  
@endsection