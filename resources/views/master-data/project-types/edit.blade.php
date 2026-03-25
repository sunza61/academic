@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">แก้ไขข้อมูลประเภทโครงการ</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.project-types.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">แบบฟอร์มแก้ไขประเภทโครงการ</h3>
    </div>
    
    <form action="{{ route('master-data.project-types.update', $projectType->id) }}" method="POST">
        @csrf
        @method('PUT') <div class="card-body">
            <div class="form-group">
                <label for="name_th">ชื่อประเภทโครงการ <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name_th') is-invalid @enderror" 
                       id="name_th" name="name_th" value="{{ old('name_th', $projectType->name_th) }}" 
                       required autofocus>
                
                @error('name_th')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> อัปเดตข้อมูล</button>
            <a href="{{ route('master-data.project-types.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection
@section('script')
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endsection