@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-folder-plus"></i> เพิ่มหมวดหมู่หลักรายจ่าย</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.budget-expenses.main.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <form action="{{ route('master-data.budget-expenses.main.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>ชื่อหมวดหมู่หลัก (ภาษาไทย) <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name_th') is-invalid @enderror" name="name_th" value="{{ old('name_th') }}" required autofocus>
                @error('name_th') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                    <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกข้อมูล</button>
            <a href="{{ route('master-data.budget-expenses.main.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection
