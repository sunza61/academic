@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">แก้ไขข้อมูลกลุ่มเป้าหมาย</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.target-groups.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">แบบฟอร์มแก้ไขข้อมูลกลุ่มเป้าหมาย</h3>
    </div>
    
    <form action="{{ route('master-data.target-groups.update', $targetGroup->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card-body">
            
            <div class="form-group">
                <label for="parent_id">ตำแหน่งของกลุ่มนี้ (อยู่ภายใต้กลุ่มใด)</label>
                <select class="form-control select2-basic @error('parent_id') is-invalid @enderror" 
                        id="parent_id" name="parent_id" style="width: 100%;">
                    <option value="">-- เป็นกลุ่มหลัก (Level 1) --</option>
                    @foreach($allGroups as $group)
                        <option value="{{ $group->id }}" 
                            {{ old('parent_id', $targetGroup->parent_id) == $group->id ? 'selected' : '' }}>
                            {{ $group->full_path }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="name_th">ชื่อกลุ่มเป้าหมาย (ภาษาไทย) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name_th') is-invalid @enderror" 
                           id="name_th" name="name_th" value="{{ old('name_th', $targetGroup->name_th) }}" required autofocus>
                    @error('name_th') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="name_en">ชื่อกลุ่มเป้าหมาย (ภาษาอังกฤษ)</label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                           id="name_en" name="name_en" value="{{ old('name_en', $targetGroup->name_en) }}">
                    @error('name_en') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="group_type">ป้ายกำกับประเภท (Group Type)</label>
                <input type="text" class="form-control @error('group_type') is-invalid @enderror" 
                       id="group_type" name="group_type" value="{{ old('group_type', $targetGroup->group_type) }}">
            </div>

            <div class="form-group">
                <label for="description">รายละเอียดเพิ่มเติม</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $targetGroup->description) }}</textarea>
            </div>

            <div class="form-group custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $targetGroup->is_active) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
            </div>

        </div>
        
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> อัปเดตข้อมูล</button>
            <a href="{{ route('master-data.target-groups.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            if($('.select2-basic').length) {
                $('.select2-basic').select2({
                    theme: 'bootstrap4',
                    placeholder: "-- เปลี่ยนตำแหน่งที่ต้องการ (ถ้ามี) --",
                    allowClear: true
                });
            }
        });
    </script>
@endsection