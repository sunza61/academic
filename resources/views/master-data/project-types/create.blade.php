@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">เพิ่มข้อมูลประเภทโครงการ</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.project-types.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">แบบฟอร์มเพิ่มประเภทโครงการ</h3>
    </div>
    
    <form action="{{ route('master-data.project-types.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name_th">ชื่อประเภทโครงการ <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name_th') is-invalid @enderror" 
                       id="name_th" name="name_th" value="{{ old('name_th') }}" 
                       placeholder="กรุณากรอกชื่อประเภทโครงการ (เช่น โครงการวิจัย, บริการวิชาการ)" required autofocus>
                
                @error('name_th')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกข้อมูล</button>
            <a href="{{ route('master-data.project-types.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection
