@extends('layouts.main_all')

@section('content')
<div class="row mb-3 mt-2">
    <div class="col-sm-6">
        <h3 class="m-0 font-weight-bold">สร้างโครงการใหม่: <span class="text-primary">{{ $projectType->name_th ?? 'ฝึกอบรม' }}</span></h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('trainings.projects.index', ['type_id' => $projectType->id ?? 1]) }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> ยกเลิก
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> <strong>เกิดข้อผิดพลาด!</strong> กรุณาตรวจสอบข้อมูลในฟอร์มด้านล่าง
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <ul class="nav nav-pills nav-justified wizard-nav" id="project-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? 'tab1') == 'tab1' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab1" role="tab">
                    <i class="fas fa-info-circle mr-1"></i> 1. ข้อมูลพื้นฐาน
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') == 'tab2' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab2" role="tab">
                    <i class="fas fa-chalkboard-teacher mr-1"></i> 2. ข้อมูลเฉพาะ & บุคคล
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') == 'tab3' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab3" role="tab">
                    <i class="fas fa-calendar-alt mr-1"></i> 3. กำหนดการ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') == 'tab4' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab4" role="tab">
                    <i class="fas fa-coins mr-1"></i> 4. งบประมาณ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') == 'tab5' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab5" role="tab">
                    <i class="fas fa-chart-line mr-1"></i> 5. ผลลัพธ์ & ประเมิน
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') == 'tab6' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab6" role="tab">
                    <i class="fas fa-clipboard-check mr-1"></i> 6. ภาพรวม
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="project-tabs-content">

    <div class="tab-pane fade {{ ($activeTab ?? 'tab1') == 'tab1' ? 'show active' : '' }}" id="tab1" role="tabpanel">
        <form action="{{ route('trainings.projects.store') }}" method="POST" id="wizard-form-step1">
            @csrf
            <input type="hidden" name="project_type_id" value="{{ $projectType->id ?? 1 }}">
            <input type="hidden" name="step" value="1">

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white">
                    <h5 class="card-title mb-0">ส่วนที่ 1: ข้อมูลพื้นฐานโครงการ</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>รหัสโครงการ</label>
                            <input type="text" class="form-control text-center font-weight-bold text-success" value="*ระบบสร้างอัตโนมัติ*" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        
                        <div class="col-md-3 form-group">
                            <label>ปีงบประมาณ <span class="text-danger">*</span></label>
                            <select class="form-control @error('fiscal_year_id') is-invalid @enderror" name="fiscal_year_id" required>
                                <option value="">-- เลือกปีงบประมาณ --</option>
                                @foreach($fiscalYears as $year)
                                    <option value="{{ $year->id }}" {{ old('fiscal_year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->fiscal_year_be }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fiscal_year_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label>ชื่อโครงการ (ภาษาไทย) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name_th') is-invalid @enderror" name="name_th" value="{{ old('name_th') }}" placeholder="ระบุชื่อโครงการ..." required autofocus>
                            @error('name_th') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label>หน่วยงานผู้รับผิดชอบ <span class="text-danger">* เลือกได้มากกว่า 1</span></label>
                            <select class="form-control select2-multiple @error('department_ids') is-invalid @enderror" name="department_ids[]" multiple="multiple" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->DEPARTMENT_ID }}" {{ in_array($dept->DEPARTMENT_ID, old('department_ids', [])) ? 'selected' : '' }}>
                                        {{ $dept->DEPARTMENT_NAME_TH }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_ids') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label>หลักสูตร <span class="text-danger">* เลือกได้มากกว่า 1</span></label>
                            <select class="form-control select2-multiple @error('course_ids') is-invalid @enderror" name="course_ids[]" multiple="multiple" required>
                                @foreach($divisions as $div)
                                    <option value="{{ $div->DIVISION_ID }}" {{ in_array($div->DIVISION_ID, old('course_ids', [])) ? 'selected' : '' }}>
                                        {{ $div->DIVISION_NAME }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_ids') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label>ศูนย์ (Center)</label>
                            <select class="form-control @error('center_id') is-invalid @enderror" name="center_id">
                                <option value="">-- เลือกศูนย์ (ถ้ามี) --</option>
                                @foreach($centers as $center)
                                    <option value="{{ $center->id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>
                                        {{ $center->name_th }}
                                    </option>
                                @endforeach
                            </select>
                            @error('center_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label>ระดับโครงการ</label>
                            <select class="form-control @error('region_type') is-invalid @enderror" name="region_type">
                                <option value="1" {{ old('region_type') == '1' ? 'selected' : '' }}>ระดับชาติ</option>
                                <option value="2" {{ old('region_type') == '2' ? 'selected' : '' }}>ระดับนานาชาติ</option>
                            </select>
                            @error('region_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-12 form-group">
                            <label>วัตถุประสงค์ <span class="text-danger">* เลือกได้มากกว่า 1</span></label>
                            <select class="form-control select2-multiple @error('target_group_ids') is-invalid @enderror" name="target_group_ids[]" multiple="multiple" required>
                                @foreach($targetGroups as $tg)
                                    <option value="{{ $tg->id }}" {{ in_array($tg->id, old('target_group_ids', [])) ? 'selected' : '' }}>
                                        {{ $tg->name_th }}
                                    </option>
                                @endforeach
                            </select>
                            @error('target_group_ids') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-md-12 form-group">
                            <label>รายละเอียดโครงการโดยย่อ</label>
                            <textarea class="form-control @error('brief_description') is-invalid @enderror" name="brief_description" rows="2" placeholder="สรุปใจความสำคัญของโครงการสั้นๆ...">{{ old('brief_description') }}</textarea>
                            @error('brief_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 form-group">
                            <label>หลักการและเหตุผล</label>
                            <textarea class="form-control @error('rationale') is-invalid @enderror" name="rationale" rows="3" placeholder="อธิบายหลักการและเหตุผล...">{{ old('rationale') }}</textarea>
                            @error('rationale') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label>วันที่เริ่มต้น <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" placeholder="วว/ดด/ปปปป" required>
                            @error('start_date') <div class="invalid-feedback" style="display: block;">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label>วันที่สิ้นสุด <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}" placeholder="วว/ดด/ปปปป" required>
                            @error('end_date') <div class="invalid-feedback" style="display: block;">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white text-right py-3 border-top">
                    <button type="submit" class="btn btn-success btn-lg shadow-sm">
                        <i class="fas fa-save mr-1"></i> บันทึกเป็นฉบับร่าง & ถัดไป <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="tab-pane fade" id="tab2" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4 project-section">
            <div class="card-body text-center py-5">
                <i class="fas fa-lock fa-3x mb-3 text-muted"></i>
                <h5 class="text-muted">กรุณาบันทึก "ข้อมูลพื้นฐาน" ก่อน</h5>
                <p class="text-muted">เพื่อปลดล็อคส่วนที่ 2 (ข้อมูลเฉพาะ & บุคคล)</p>
                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="$('.wizard-nav a[href=\'#tab1\']').tab('show')">กลับไปหน้าแรก</button>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab3" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4 project-section">
            <div class="card-body text-center py-5">
                <i class="fas fa-lock fa-3x mb-3 text-muted"></i>
                <h5 class="text-muted">กรุณาบันทึก "ข้อมูลพื้นฐาน" ก่อน</h5>
                <p class="text-muted">เพื่อปลดล็อคส่วนที่ 3 (กำหนดการ)</p>
                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="$('.wizard-nav a[href=\'#tab1\']').tab('show')">กลับไปหน้าแรก</button>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab4" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4 project-section">
            <div class="card-body text-center py-5">
                <i class="fas fa-lock fa-3x mb-3 text-muted"></i>
                <h5 class="text-muted">กรุณาบันทึก "ข้อมูลพื้นฐาน" ก่อน</h5>
                <p class="text-muted">เพื่อปลดล็อคส่วนที่ 4 (งบประมาณ)</p>
                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="$('.wizard-nav a[href=\'#tab1\']').tab('show')">กลับไปหน้าแรก</button>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab5" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4 project-section">
            <div class="card-body text-center py-5">
                <i class="fas fa-lock fa-3x mb-3 text-muted"></i>
                <h5 class="text-muted">กรุณาบันทึก "ข้อมูลพื้นฐาน" ก่อน</h5>
                <p class="text-muted">เพื่อปลดล็อคส่วนที่ 5 (ผลลัพธ์ & ประเมิน)</p>
                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="$('.wizard-nav a[href=\'#tab1\']').tab('show')">กลับไปหน้าแรก</button>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab6" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4 project-section">
            <div class="card-body text-center py-5">
                <i class="fas fa-lock fa-3x mb-3 text-muted"></i>
                <h5 class="text-muted">กรุณาบันทึก "ข้อมูลพื้นฐาน" ก่อน</h5>
                <p class="text-muted">เพื่อดูภาพรวมทั้งหมดของโครงการ</p>
                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="$('.wizard-nav a[href=\'#tab1\']').tab('show')">กลับไปหน้าแรก</button>
            </div>
        </div>
    </div>

</div> <style>
    /* 🌟 จัดระเบียบเมนูแท็บให้สีและขนาดเหมือนกันทั้งระบบ */
    .wizard-nav .nav-link {
        border-radius: 0;
        border-bottom: 4px solid #dee2e6;
        color: #6c757d;
        font-size: 14px; 
        transition: all 0.3s ease;
    }

    .wizard-nav .nav-link:hover {
        color: #dc3545;
        background-color: #f8f9fa;
    }

    .wizard-nav .nav-link.active {
        border-bottom: 4px solid #dc3545;
        background: transparent;
        color: #dc3545 !important;
    }

    /* 🌟 ปุ่ม Select2 สีเข้ม */
    .select2-container .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #343a40 !important;
        border: 1px solid #23272b !important;
        color: #ffffff !important;
        border-radius: 4px;
        padding: 2px 8px;
        margin-top: 6px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ced4da !important;
        margin-right: 5px;
        border-right: none !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffffff !important;
        background-color: transparent !important;
    }
</style>
@endsection

@section('script')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
<script>
    $(document).ready(function() {
        // 1. ตั้งค่า Select2
        $('.select2-multiple').select2({
            width: '100%',
            placeholder: "คลิกเพื่อเลือกข้อมูล (เลือกได้มากกว่า 1)"
        });

        // 2. ตั้งค่า Datepicker
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d", 
            altInput: true,
            altFormat: "d/m/Y",  
            locale: "th",
            allowInput: true
        });
    });
</script>
@endsection