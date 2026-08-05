@extends('layouts.main_all')

@section('content')
<style>
    /* 1. เปลี่ยนสีพื้นหลังและกรอบของกล่องที่ถูกเลือก (Tag) */
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #343a40 !important; /* ใช้สีเทาดำเข้มๆ ให้เข้ากับธีม bg-custom-dark ของระบบ */
        border: 1px solid #23272b !important;
        color: #ffffff !important; /* สีตัวอักษรเป็นสีขาว */
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 14px;
    }

    /* 2. เปลี่ยนสีของปุ่มกากบาท (x) สำหรับกดลบ */
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff !important; /* เปลี่ยนสีกากบาทเป็นสีขาว */
        margin-right: 8px;
        border-right: 1px solid rgba(255, 255, 255, 0.3) !important; /* เส้นคั่นกากบาทบางๆ */
        padding-right: 6px;
    }

    /* 3. เอฟเฟกต์ตอนเอาเมาส์ไปชี้ที่ปุ่มกากบาท (Hover) */
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background-color: transparent !important;
        color: #ff6b6b !important; /* เปลี่ยนกากบาทเป็นสีแดงอ่อนๆ ตอนชี้เมาส์ */
    }
</style>
<div class="row mb-3 mt-2">
    <div class="col-sm-6">
        <h3 class="m-0 font-weight-bold">สร้างโครงการใหม่: <span class="text-primary">{{ $projectType->name_th ?? 'บริการวิชาการ/สัญญาจ้าง' }}</span></h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('contracts.projects.index', ['type_id' => $projectType->id ?? 3]) }}" class="btn btn-secondary shadow-sm">
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
                <a class="nav-link active font-weight-bold py-3" data-toggle="pill" href="#tab1" role="tab">
                    <i class="fas fa-info-circle mr-1"></i> 1. ข้อมูลพื้นฐาน
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold py-3" data-toggle="pill" href="#tab2" role="tab">
                    <i class="fas fa-user-tie mr-1"></i> 2. ข้อมูลเฉพาะ & บุคคล
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold py-3" data-toggle="pill" href="#tab3" role="tab">
                    <i class="fas fa-coins mr-1"></i> 3. งบประมาณ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold py-3" data-toggle="pill" href="#tab4" role="tab">
                    <i class="fas fa-chart-line mr-1"></i> 4. ผลลัพธ์ & ประเมิน
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold py-3" data-toggle="pill" href="#tab5" role="tab">
                    <i class="fas fa-clipboard-check mr-1"></i> 5. ภาพรวม
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="project-tabs-content">

    <!-- แท็บที่ 1: ข้อมูลพื้นฐาน -->
    <div class="tab-pane fade show active" id="tab1" role="tabpanel">
        <form action="{{ route('contracts.projects.store') }}" method="POST" id="wizard-form-step1">
            @csrf
            <input type="hidden" name="project_type_id" value="{{ $projectType->id ?? 3 }}">
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
                            <label>หน่วยงานผู้รับผิดชอบ <span class="text-danger">*</span></label>
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

                        <div class="col-md-12 form-group">
                            <label>ระดับโครงการ</label>
                            <select class="form-control @error('region_type') is-invalid @enderror" name="region_type">
                                <option value="1" {{ old('region_type') == '1' ? 'selected' : '' }}>ระดับชาติ</option>
                                <option value="2" {{ old('region_type') == '2' ? 'selected' : '' }}>ระดับนานาชาติ</option>
                            </select>
                            @error('region_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- 🎯 วัตถุประสงค์โครงการ (Header Only Once) -->
                        <div class="col-md-12 form-group mt-2">
                            <label class="font-weight-bold">วัตถุประสงค์โครงการ <span class="text-danger">*</span></label>
                            <div class="sub-card" style="border: 1px solid #ced4da; border-radius: 8px; overflow: hidden;">
                                <div class="sub-card-header d-flex justify-content-end align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #ced4da; padding: 8px 15px;">
                                    <button type="button" class="btn btn-xs rounded-pill px-3 shadow-sm bg-custom-dark text-white" id="btn-add-objective" style="font-size: 0.75rem;">
                                        <i class="fas fa-plus-circle mr-1"></i> เพิ่มวัตถุประสงค์
                                    </button>
                                </div>
                                <div id="objective-container" class="bg-white p-2">
                                    <!-- Header Row (Desktop Only) -->
                                    <div class="row px-3 mb-2 d-none d-md-flex objective-header-row">
                                        <div class="col-md-3">
                                            <label class="small font-weight-bold text-muted">กลุ่มผู้ว่าจ้าง/แหล่งทุน <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="small font-weight-bold text-muted">รายละเอียดวัตถุประสงค์</label>
                                        </div>
                                        <div class="col-md-1"></div>
                                    </div>

                                    <div class="objective-box p-2 border-bottom">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <!-- Mobile Label -->
                                                <label class="small font-weight-bold text-muted d-md-none mb-1">กลุ่มผู้ว่าจ้าง/แหล่งทุน <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2-objective" name="objectives[0][group_id]" required>
                                                    <option value="">-- เลือกกลุ่ม --</option>
                                                    @foreach($customerGroups as $group)
                                                        <option value="{{ $group->id }}">{{ $group->name_th }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-8 col-10">
                                                <!-- Mobile Label -->
                                                <label class="small font-weight-bold text-muted d-md-none mb-1">รายละเอียดวัตถุประสงค์</label>
                                                <input type="text" class="form-control form-control-sm w-100" name="objectives[0][detail]" placeholder="เช่น เพื่อพัฒนาสารตั้งต้นตัวอย่าง...">
                                            </div>
                                            <div class="col-md-1 col-2 text-right pl-0">
                                                <button type="button" class="btn btn-sm btn-link text-danger btn-remove-objective px-1" title="ลบข้อนี้" style="display: none;">
                                                    <i class="fas fa-trash-alt fa-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 form-group">
                            <label>รายละเอียดโครงการโดยย่อ</label>
                            <textarea class="form-control @error('brief_description') is-invalid @enderror" name="brief_description" rows="2" placeholder="สรุปใจความสำคัญของโครงการสั้นๆ...">{{ old('brief_description') }}</textarea>
                            @error('brief_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 form-group">
                            <label>หลักการและเหตุผล</label>
                            <textarea class="form-control @error('rationale') is-invalid @enderror" name="rationale" rows="3" placeholder="ระบุเหตุผลความจำเป็น...">{{ old('rationale') }}</textarea>
                            @error('rationale') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label>วันที่เริ่มต้นโครงการ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" placeholder="วว/ดด/ปปปป" required>
                            @error('start_date') <div class="invalid-feedback" style="display: block;">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label>วันที่สิ้นสุดโครงการ <span class="text-danger">*</span></label>
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

    <!-- ส่วนแท็บอื่นๆ (Locked State) -->
    @foreach(['tab2' => 'ข้อมูลเฉพาะ & บุคคล', 'tab3' => 'งบประมาณ', 'tab4' => 'ผลลัพธ์ & ประเมิน', 'tab5' => 'ภาพรวม'] as $key => $title)
    <div class="tab-pane fade" id="{{ $key }}" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-lock fa-3x mb-3 text-muted"></i>
                <h5 class="text-muted">กรุณาบันทึก "ข้อมูลพื้นฐาน" ก่อน</h5>
                <p class="text-muted small">เพื่อปลดล็อคส่วน {{ $title }}</p>
                <button type="button" class="btn btn-sm btn-primary mt-2 rounded-pill px-4" onclick="$('#project-tabs a[href=\'#tab1\']').tab('show')">กลับไปหน้าแรก</button>
            </div>
        </div>
    </div>
    @endforeach

</div>

<style>
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

    .bg-custom-dark {
        background-color: #343a40 !important;
    }
</style>
@endsection

@section('script')
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/flatpickr/flatpickr.min.css') }}">
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('plugins/flatpickr/th.js') }}"></script>

<script>
    // ส่งผ่านข้อมูลจาก Blade ไปยังไฟล์ JS แยก
    window.CUSTOMER_GROUPS = @json($customerGroups);
</script>

<script src="{{ asset('js/contracts/projects/create.js?v=' . time()) }}"></script>
@endsection
