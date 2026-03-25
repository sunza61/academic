@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">เพิ่มข้อมูลกลุ่มเป้าหมาย (Target Group)</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.target-groups.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">แบบฟอร์มเพิ่มกลุ่มเป้าหมาย</h3>
    </div>

    <form action="{{ route('master-data.target-groups.store') }}" method="POST">
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label for="parent_id">ตำแหน่งของกลุ่มนี้ (อยู่ภายใต้กลุ่มใด) <span class="text-muted font-weight-normal">(ปล่อยว่างได้หากเป็นกลุ่มหลัก)</span></label>
                <select class="form-control select2-basic @error('parent_id') is-invalid @enderror"
                    id="parent_id" name="parent_id" style="width: 100%;">
                    <option value="">-- สร้างเป็นกลุ่มหลัก (Level 1) --</option>
                    @foreach($allGroups as $group)
                    <option value="{{ $group->id }}" {{ old('parent_id') == $group->id ? 'selected' : '' }}>
                        {{ $group->full_path }}
                    </option>
                    @endforeach
                </select>
                @error('parent_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name_th">ชื่อกลุ่มเป้าหมาย (ภาษาไทย) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_th') is-invalid @enderror"
                            id="name_th" name="name_th" value="{{ old('name_th') }}"
                            placeholder="เช่น ชั้น ม.2, โรงเรียนอนุบาลตรัง" required autofocus>
                        @error('name_th')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name_en">ชื่อกลุ่มเป้าหมาย (ภาษาอังกฤษ) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                            id="name_en" name="name_en" value="{{ old('name_en') }}"
                            placeholder="เช่น Mathayom 2">
                        @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="group_type">ป้ายกำกับประเภท (Group Type) <span class="text-primary font-weight-normal">(เพื่อประโยชน์ตอนออกรายงาน)</span></label>
                <input type="text" class="form-control @error('group_type') is-invalid @enderror"
                    id="group_type" name="group_type" value="{{ old('group_type') }}"
                    placeholder="เช่น school, class_m1">
                @error('group_type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">รายละเอียดเพิ่มเติม</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                    id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
            <div class="form-group custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
            </div>
            </div>
        </div>

        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกข้อมูล</button>
            <a href="{{ route('master-data.target-groups.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection

@section('script')
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
<script>
    $(document).ready(function() {
        if ($('.select2-basic').length) {
            $('.select2-basic').select2({
                placeholder: "-- ค้นหาตำแหน่งที่ต้องการ --",
                allowClear: true
            });
        }
    });
</script>
@endsection