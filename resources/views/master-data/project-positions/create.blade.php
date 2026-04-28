@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">เพิ่มข้อมูลตำแหน่งในโครงการ</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.project-positions.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ย้อนกลับ</a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <form action="{{ route('master-data.project-positions.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>ชื่อตำแหน่ง (ภาษาไทย) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name_th') is-invalid @enderror" name="name_th" value="{{ old('name_th') }}" placeholder="เช่น หัวหน้าโครงการ" required autofocus>
                </div>
                <div class="col-md-6 form-group">
                    <label>ชื่อตำแหน่ง (ภาษาอังกฤษ)  <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en') }}" placeholder="เช่น Project Leader" required autofocus>
                </div>
                
                <div class="col-md-12 form-group">
                    <label>รายละเอียดเพิ่มเติม</label>
                    <textarea class="form-control" name="description" rows="2">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-12 form-group mt-2">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_unique" name="is_unique" value="1" {{ old('is_unique') ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-bold text-warning" for="is_unique">
                            <i class="fas fa-star"></i> เป็นตำแหน่งที่มีได้คนเดียวในโครงการ (Is Unique)
                        </label>
                        <small class="form-text text-muted">ตัวอย่างเช่น "หัวหน้าโครงการ" ที่ระบบจะล็อกไม่ให้เลือกซ้ำ</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกข้อมูล</button>
            <a href="{{ route('master-data.project-positions.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection

@section('script')
<script src="{{ asset('js/master-data/project-positions/create.js?v=' . time()) }}"></script>
@endsection