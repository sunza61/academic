@extends('layouts.main_all')

@section('content')
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

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        border-bottom: 4px solid #dc3545 !important;
        background-color: transparent !important;
        color: #dc3545 !important;
        font-weight: bold;
    }

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

    .form-control-sm {
        font-size: 0.85rem;
    }

    .card-header {
        padding: 0.5rem 1rem !important;
    }

    .card-body {
        padding: 1rem !important;
    }

    label {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
</style>
<div class="row mb-3 mt-2">
    <div class="col-sm-8">
        <h3 class="m-0 font-weight-bold">
            แก้ไขโครงการ: <span class="text-primary">{{ $projectType->name_th ?? 'บริการวิชาการ/สัญญาจ้าง' }}</span>
        </h3>
        <p class="text-muted mb-0 mt-1">
            รหัสโครงการ: <span class="badge badge-success px-2 py-1" style="font-size: 1em;">{{ $project->id }}</span>
            <span class="mx-2">|</span>
            สถานะ:
            <strong class="text-dark">
                @if($project->overall_status == 100) เตรียมการ / ฉบับร่าง
                @elseif($project->overall_status == 110) ตีกลับ
                @elseif($project->overall_status == 200) เสนอขออนุมัติ
                @elseif($project->overall_status == 300) อนุมัติแล้ว
                @elseif($project->overall_status == 400) เปิดรับสมัคร
                @elseif($project->overall_status == 500) ปิดรับสมัคร
                @elseif($project->overall_status == 600) กำลังดำเนินการ
                @elseif($project->overall_status == 700) รอประเมินผล
                @elseif($project->overall_status == 800) เสร็จสิ้นโครงการ
                @elseif($project->overall_status == 900) ยกเลิกโครงการ
                @else {{ $project->overall_status }} @endif
            </strong>
        </p>
    </div>
    <div class="col-sm-4 text-right">
        <a href="{{ route('contracts.projects.index', ['type_id' => $project->project_type_id]) }}" class="btn btn-secondary shadow-sm mt-2">
            <i class="fas fa-arrow-left"></i> กลับไปหน้าตาราง
        </a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-exclamation-circle mr-2"></i> <strong>เกิดข้อผิดพลาด!</strong> กรุณาตรวจสอบข้อมูลในฟอร์ม
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <ul class="nav nav-pills nav-justified wizard-nav" id="project-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'tab1' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab1" role="tab">
                    <i class="fas fa-info-circle mr-1"></i> 1. ข้อมูลพื้นฐาน
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'tab2' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab2" role="tab">
                    <i class="fas fa-chalkboard-teacher mr-1"></i> 2. ข้อมูลเฉพาะ & บุคคล
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'tab3' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab3" role="tab">
                    <i class="fas fa-coins mr-1"></i> 3. งบประมาณ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'tab4' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab4" role="tab">
                    <i class="fas fa-chart-line mr-1"></i> 4. ผลลัพธ์ & ประเมิน
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'tab5' ? 'active' : '' }} font-weight-bold py-3" data-toggle="pill" href="#tab5" role="tab">
                    <i class="fas fa-clipboard-check mr-1"></i> 5. ภาพรวม
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="project-tabs-content">

    <!-- แท็บที่ 1: ข้อมูลพื้นฐาน -->
    <div class="tab-pane fade {{ $activeTab == 'tab1' ? 'show active' : '' }}" id="tab1" role="tabpanel">
        <form action="{{ route('contracts.projects.update', $project->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="step" value="1">
            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white">
                    <h5 class="card-title mb-0">ส่วนที่ 1: ข้อมูลพื้นฐานโครงการ</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>ปีงบประมาณ <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm @error('fiscal_year_id') is-invalid @enderror" name="fiscal_year_id" required>
                                <option value="">-- เลือกปีงบประมาณ --</option>
                                @foreach($fiscalYears as $year)
                                <option value="{{ $year->id }}" {{ old('fiscal_year_id', $project->fiscal_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->fiscal_year_be }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-9 form-group">
                            <label>ชื่อโครงการ (ภาษาไทย) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm @error('name_th') is-invalid @enderror" name="name_th" value="{{ old('name_th', $project->name_th) }}" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>หน่วยงานผู้รับผิดชอบ <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm select2-multiple" name="department_ids[]" multiple="multiple" required>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->DEPARTMENT_ID }}" {{ in_array($dept->DEPARTMENT_ID, old('department_ids', $selectedDepartments)) ? 'selected' : '' }}>
                                    {{ $dept->DEPARTMENT_NAME_TH }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>ศูนย์ (Center)</label>
                            <select class="form-control form-control-sm" name="center_id">
                                <option value="">-- เลือกศูนย์ (ถ้ามี) --</option>
                                @foreach($centers as $center)
                                <option value="{{ $center->id }}" {{ old('center_id', $project->center_id) == $center->id ? 'selected' : '' }}>
                                    {{ $center->name_th }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>ระดับโครงการ</label>
                            <select class="form-control form-control-sm" name="region_type">
                                <option value="1" {{ old('region_type', $project->region_type) == '1' ? 'selected' : '' }}>ระดับชาติ</option>
                                <option value="2" {{ old('region_type', $project->region_type) == '2' ? 'selected' : '' }}>ระดับนานาชาติ</option>
                            </select>
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
                                    <div class="row px-3 mb-2 d-none d-md-flex objective-header-row">
                                        <div class="col-md-3 text-muted small font-weight-bold">กลุ่มผู้ว่าจ้าง/แหล่งทุน <span class="text-danger">*</span></div>
                                        <div class="col-md-8 text-muted small font-weight-bold">รายละเอียดวัตถุประสงค์</div>
                                        <div class="col-md-1"></div>
                                    </div>

                                    @foreach($savedObjectives as $index => $obj)
                                    <div class="objective-box p-2 border-bottom">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <label class="small font-weight-bold text-muted d-md-none mb-1">กลุ่มผู้ว่าจ้าง/แหล่งทุน *</label>
                                                <select class="form-control form-control-sm select2-objective" name="objectives[{{ $index }}][group_id]" required>
                                                    @foreach($customerGroups as $group)
                                                    <option value="{{ $group->id }}" {{ $obj->target_group_id == $group->id ? 'selected' : '' }}>{{ $group->name_th }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-8 col-10">
                                                <label class="small font-weight-bold text-muted d-md-none mb-1">รายละเอียดวัตถุประสงค์</label>
                                                <input type="text" class="form-control form-control-sm w-100" name="objectives[{{ $index }}][detail]" value="{{ $obj->detail }}" placeholder="เช่น เพื่อพัฒนาสารตั้งต้นตัวอย่าง...">
                                            </div>
                                            <div class="col-md-1 col-2 text-right pl-0">
                                                <button type="button" class="btn btn-sm btn-link text-danger btn-remove-objective px-1" title="ลบข้อนี้" {{ count($savedObjectives) == 1 ? 'style=display:none;' : '' }}>
                                                    <i class="fas fa-trash-alt fa-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            <label>รายละเอียดโครงการโดยย่อ</label>
                            <textarea class="form-control" name="brief_description" rows="2">{{ old('brief_description', $project->brief_description) }}</textarea>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>หลักการและเหตุผล</label>
                            <textarea class="form-control" name="rationale" rows="3">{{ old('rationale', $project->rationale) }}</textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>วันที่เริ่มต้น <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="start_date" value="{{ old('start_date', $project->start_date) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>วันที่สิ้นสุด <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="end_date" value="{{ old('end_date', $project->end_date) }}" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-right py-3">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fas fa-save mr-1"></i> บันทึกข้อมูลพื้นฐาน & ถัดไป <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- แท็บที่ 2: ข้อมูลเฉพาะ & บุคคล -->
    <div class="tab-pane fade {{ $activeTab == 'tab2' ? 'show active' : '' }}" id="tab2" role="tabpanel">
        <form action="{{ route('contracts.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="step" value="2">

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white py-2">
                    <h5 class="card-title mb-0 mt-1"><i class="fas fa-info-circle mr-2"></i> ส่วนที่ 2.1: รายละเอียดข้อมูลสัญญาจ้าง / บริการวิชาการ</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>เลขที่สัญญา (ถ้ามี)</label>
                            <input type="text" name="contract_number" class="form-control" value="{{ old('contract_number', $projectContract->contract_number ?? '') }}" placeholder="ระบุเลขที่สัญญา...">
                        </div>

                        <div class="col-md-6 form-group">
                            <label>เอกสารสัญญา (PDF) (ถ้ามี)</label>
                            <input type="file" name="contract_file" class="form-control-file" accept=".pdf">
                            @if(!empty($projectContract->contract_file_path))
                            <div id="file-link-zone" class="mt-2 p-2 border rounded bg-white">
                                <small class="text-success d-block"><i class="fas fa-check-circle"></i> มีไฟล์แนบแล้ว: <a href="{{ asset('storage/'.$projectContract->contract_file_path) }}" target="_blank">ดูไฟล์เดิม</a></small>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-1 btn-remove-file" data-field="remove_contract_file">
                                    <i class="fas fa-trash"></i> ลบไฟล์นี้
                                </button>
                            </div>
                            <input type="hidden" name="remove_contract_file" id="remove_contract_file" value="0">
                            @endif
                        </div>
                        <div class="col-md-6 form-group">
                            <label>ลิ้งค์เอกสารประกอบ (ถ้ามี)</label>
                            <input type="url" name="contract_file_link" class="form-control" value="{{ old('contract_file_link', $projectContract->contract_file_link ?? '') }}" placeholder="https://...">
                        </div>

                        <div class="col-md-12 form-group">
                            <label>เกี่ยวข้องกับ SDGs <span class="text-danger">*</span></label>
                            <select name="sdgs[]" class="form-control select2-multiple" multiple="multiple" required>
                                @foreach($sdgs as $sdg)
                                <option value="{{ $sdg->id }}" {{ in_array($sdg->id, old('sdgs', $selectedSdgs)) ? 'selected' : '' }}>
                                    SDGs {{ $sdg->id }} : {{ $sdg->name_th }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white d-flex align-items-center py-2">
                    <h5 class="card-title mb-0 d-inline-block mt-1"><i class="fas fa-users mr-2"></i> ส่วนที่ 2.2: กลุ่มผู้ว่าจ้าง/แหล่งทุน</h5>
                    <button type="button" class="btn btn-sm btn-success shadow-sm ml-auto" id="btn-add-target">
                        <i class="fas fa-plus"></i> เพิ่มกลุ่มผู้ว่าจ้าง/แหล่งทุน
                    </button>
                </div>
                <div class="card-body bg-light p-0">
                    <table class="table table-bordered table-hover mb-0" id="table-target-group">
                        <thead class="bg-white">
                            <tr>
                                <th width="55%">กลุ่มเป้าหมาย <span class="text-danger">*</span></th>
                                <th width="35%">เชื้อชาติ <span class="text-danger">*</span></th>
                                <th width="10%" class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($savedTargetGroups as $stg)
                            <tr>
                                <td>
                                    <select name="target_groups[customer_group_id][]" class="form-control select2-customer" required>
                                        <option value="">-- ค้นหากลุ่มเป้าหมาย --</option>
                                        @foreach($filteredTargetGroups as $tg)
                                        <option value="{{ $tg->id }}" {{ $stg->target_group_id == $tg->id ? 'selected' : '' }}>
                                            {{ $tg->full_path }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="target_groups[nationality_id][]" class="form-control" required>
                                        <option value="">-- เลือกเชื้อชาติ --</option>
                                        @foreach($nationalities as $nat)
                                        <option value="{{ $nat->id }}" {{ $stg->nationality_id == $nat->id ? 'selected' : '' }}>{{ $nat->name_th }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" {{ $loop->first ? 'disabled' : '' }}><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td>
                                    <select name="target_groups[customer_group_id][]" class="form-control select2-customer" required>
                                        <option value="">-- ค้นหากลุ่มเป้าหมาย --</option>
                                        @foreach($filteredTargetGroups as $tg)
                                        <option value="{{ $tg->id }}">{{ $tg->full_path }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="target_groups[nationality_id][]" class="form-control" required>
                                        <option value="">-- เลือกเชื้อชาติ --</option>
                                        @foreach($nationalities as $nat)
                                        <option value="{{ $nat->id }}">{{ $nat->name_th }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" disabled><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white d-flex align-items-center py-2">
                    <h5 class="card-title mb-0 d-inline-block mt-1"><i class="fas fa-user-tie mr-2"></i> ส่วนที่ 2.3: คณะทำงานในโครงการ</h5>
                    <button type="button" class="btn btn-sm btn-success shadow-sm ml-auto" id="btn-add-committee">
                        <i class="fas fa-plus"></i> เพิ่มคณะทำงาน
                    </button>
                </div>
                <div class="card-body bg-light p-0">
                    <table class="table table-bordered mb-0" id="table-committee">
                        <thead class="bg-white text-center">
                            <tr>
                                <th width="15%">ประเภทบุคลากร</th>
                                <th width="45%">ข้อมูลบุคคล <span class="text-danger">*</span></th>
                                <th width="20%">ตำแหน่ง <span class="text-danger">*</span></th>
                                <th width="15%">ค่าตอบแทน (บาท)</th>
                                <th width="5%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($savedCommittees as $comm)
                            <tr class="committee-row">
                                <td class="align-top">
                                    <select name="committees[member_type][]" class="form-control member-type-select">
                                        <option value="1" {{ $comm->member_type == '1' ? 'selected' : '' }}>บุคลากรในคณะ</option>
                                        <option value="0" {{ $comm->member_type == '0' ? 'selected' : '' }}>บุคคลภายนอก</option>
                                    </select>
                                </td>
                                <td class="align-top">
                                    <div class="internal-zone" style="{{ $comm->member_type == '0' ? 'display: none;' : '' }}">
                                        <select name="committees[personnel_id][]" class="form-control select2-staff" {{ $comm->member_type == '1' ? 'required' : '' }}>
                                            <option value="">-- ค้นหาชื่อบุคลากรในคณะ --</option>
                                            @foreach($staffs as $staff)
                                            <option value="{{ $staff->STAFF_ID }}" {{ $comm->personnel_id == $staff->STAFF_ID ? 'selected' : '' }}>
                                                {{ $staff->ACADEMIC_ABBR ?: $staff->TITLE_TH }}{{ $staff->NAME_TH }} {{ $staff->SURNAME_TH }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="external-zone" style="{{ $comm->member_type == '1' ? 'display: none;' : '' }}">
                                        <select name="committees[external_id][]" class="form-control select2-external" {{ $comm->member_type == '0' ? 'required' : '' }}>
                                            <option value="">-- ค้นหาชื่อบุคคลภายนอก --</option>
                                            @foreach($externals as $ext)
                                            <option value="{{ $ext->id }}" {{ $comm->external_id == $ext->id ? 'selected' : '' }}>
                                            {{ $ext->prefix->name_th ?? '' }}{{ $ext->firstname }} {{ $ext->lastname }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td class="align-top">
                                    <select name="committees[project_position_id][]" class="form-control position-select" required>
                                        <option value="">-- เลือกตำแหน่ง --</option>
                                        @foreach($projectPositions as $pos)
                                        <option value="{{ $pos->id }}" data-unique="{{ $pos->is_unique }}" {{ $comm->project_position_id == $pos->id ? 'selected' : '' }}>{{ $pos->name_th }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="align-top">
                                    <input type="number" name="committees[remuneration_total][]" class="form-control text-right" value="{{ $comm->remuneration_total }}" step="0.01" min="0">
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" {{ $loop->first ? 'disabled' : '' }}><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr class="committee-row">
                                <td class="align-top">
                                    <select name="committees[member_type][]" class="form-control member-type-select">
                                        <option value="1">บุคลากรในคณะ</option>
                                        <option value="0">บุคคลภายนอก</option>
                                    </select>
                                </td>
                                <td class="align-top">
                                    <div class="internal-zone">
                                        <select name="committees[personnel_id][]" class="form-control select2-staff" required>
                                            <option value="">-- ค้นหาชื่อบุคลากรในคณะ --</option>
                                            @foreach($staffs as $staff)
                                            <option value="{{ $staff->STAFF_ID }}">{{ $staff->ACADEMIC_ABBR ?: $staff->TITLE_TH }}{{ $staff->NAME_TH }} {{ $staff->SURNAME_TH }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="external-zone" style="display: none;">
                                        <select name="committees[external_id][]" class="form-control select2-external">
                                            <option value="">-- ค้นหาชื่อบุคคลภายนอก --</option>
                                            @foreach($externals as $ext)
                                            <option value="{{ $ext->id }}">{{ $ext->prefix->name_th ?? '' }}{{ $ext->firstname }} {{ $ext->lastname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td class="align-top">
                                    <select name="committees[project_position_id][]" class="form-control position-select" required>
                                        <option value="">-- เลือกตำแหน่ง --</option>
                                        @foreach($projectPositions as $pos)
                                        <option value="{{ $pos->id }}" data-unique="{{ $pos->is_unique }}">{{ $pos->name_th }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="align-top">
                                    <input type="number" name="committees[remuneration_total][]" class="form-control text-right" step="0.01" min="0">
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" disabled><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white text-right py-3 border-top">
                    <button type="button" class="btn btn-secondary mr-2" onclick="$('a[href=\'#tab1\']').tab('show')">
                        <i class="fas fa-arrow-left"></i> ย้อนกลับ
                    </button>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fas fa-save mr-1"></i> บันทึกข้อมูลเฉพาะและคณะทำงาน & ถัดไป <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- แท็บที่ 3: งบประมาณ -->
    <div class="tab-pane fade {{ ($activeTab ?? '') == 'tab3' ? 'show active' : '' }}" id="tab3" role="tabpanel">
        @php
            $currentStatus = $project->overall_status ?? 0;
            // ล็อกเมื่อสถานะอยู่ระหว่าง 600-700 และ ผู้ใช้ "ไม่ใช่" admin
            $isBudgetLocked = ($currentStatus >= 600 && $currentStatus <= 700) && !auth()->user()->hasRole('admin');
        @endphp

        @if($isBudgetLocked)
            <div class="alert alert-warning shadow-sm mt-3">
                <i class="fas fa-lock"></i> <strong>งบประมาณถูกล็อก:</strong> โครงการอยู่ในสถานะดำเนินการ (600-700) ไม่อนุญาตให้แก้ไขข้อมูลในส่วนนี้ (สิทธิ์การแก้ไขเฉพาะผู้ดูแลระบบ)
            </div>
        @endif

        <form action="{{ route('contracts.projects.update', $project->id) }}" method="POST" id="form-tab3-budget" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" name="step" value="3">

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white d-flex align-items-center py-2">
                    <h5 class="card-title mb-0 mt-1"><i class="fas fa-money-bill-wave mr-2"></i> ส่วนที่ 3.1: แผนรายรับ</h5>
                    @if(!$isBudgetLocked)
                    <button type="button" class="btn btn-sm btn-success shadow-sm ml-auto" id="btn-add-income">
                        <i class="fas fa-plus-circle"></i> เพิ่มแถวรายรับ
                    </button>
                    @endif
                </div>
                <div class="card-body bg-light p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" id="table-budget-incomes" style="min-width: 900px;">
                            <thead class="bg-white text-center">
                                <tr>
                                    <th width="5%">ที่</th>
                                    <th width="20%">หมวดหมู่รายรับ <span class="text-danger">*</span></th>
                                    <th width="30%">รายละเอียดกิจกรรม</th>
                                    <th width="15%">อัตราจัดเก็บ (บาท)</th>
                                    <th width="10%">จำนวน</th>
                                    <th width="15%">จำนวนเงินรวม (บาท)</th>
                                    @if(!$isBudgetLocked) <th width="5%">จัดการ</th> @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="income-row template-row d-none">
                                    <td class="text-center align-middle font-weight-bold row-index">#</td>
                                    <td>
                                        <select name="incomes[category_id][]" class="form-control income-category-select select2-basic" style="width: 100%;">
                                            <option value="">-- เลือก --</option>
                                            @foreach($incomeCategoriesGrouped as $mainCat)
                                            <optgroup label="📌 {{ $mainCat->name_th }}">
                                                @foreach($mainCat->subCategories as $subCat)
                                                <option value="{{ $subCat->id }}">
                                                    {{ $subCat->name_th }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="incomes[description][]" class="form-control" placeholder="เช่น (คนละ ... บาท x ... คน)">
                                    </td>
                                    <td>
                                        <input type="text" name="incomes[unit_cost][]" class="form-control form-control-sm text-right income-unit-cost format-number-budget" placeholder="0.00" step="0.01" min="0">
                                    </td>
                                    <td>
                                        <input type="text" name="incomes[quantity][]" class="form-control form-control-sm text-center income-quantity format-number-budget" placeholder="0" min="0">
                                    </td>
                                    <td>
                                        <input type="text" name="incomes[total_amount][]" class="form-control form-control-sm text-danger text-right font-weight-bold income-total-amount format-number-budget" placeholder="0.00" readonly style="background-color: #f1f3f5;">
                                    </td>
                                    @if(!$isBudgetLocked)
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="fas fa-trash"></i></button>
                                    </td>
                                    @endif
                                </tr>
                                @if(isset($savedIncomes) && $savedIncomes->count() > 0)
                                @foreach($savedIncomes as $index => $income)
                                <tr class="income-row">
                                    <td class="text-center align-middle font-weight-bold row-index">{{ $index + 1 }}</td>
                                    <td>
                                        <select name="incomes[category_id][]" class="form-control income-category-select select2-basic" style="width: 100%; {{ $isBudgetLocked ? 'pointer-events: none; background-color: #e9ecef;' : '' }}" required tabindex="{{ $isBudgetLocked ? '-1' : '0' }}">
                                            <option value="">-- เลือก --</option>
                                            @foreach($incomeCategoriesGrouped as $mainCat)
                                            <optgroup label="📌 {{ $mainCat->name_th }}">
                                                @foreach($mainCat->subCategories as $subCat)
                                                <option value="{{ $subCat->id }}" {{ $income->category_id == $subCat->id ? 'selected' : '' }}>
                                                    {{ $subCat->name_th }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="incomes[description][]" class="form-control" value="{{ $income->description }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="incomes[unit_cost][]" class="form-control form-control-sm text-right income-unit-cost format-number-budget" value="{{ number_format($income->unit_cost, 2) }}" step="0.01" min="0" required {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="incomes[quantity][]" class="form-control form-control-sm text-center income-quantity format-number-budget" value="{{ $income->quantity }}" min="1" required {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="incomes[total_amount][]" class="form-control form-control-sm text-danger text-right font-weight-bold income-total-amount format-number-budget" value="{{ number_format($income->total_amount, 2) }}" readonly style="background-color: #f1f3f5;">
                                    </td>
                                    @if(!$isBudgetLocked)
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="fas fa-trash"></i></button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                            <tfoot class="bg-white">
                                <tr>
                                    <td colspan="5" class="text-right font-weight-bold p-3">รวมเป็นเงินทั้งสิ้น (บาท)</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm text-danger text-right font-weight-bold format-number-3-1 format-number-budget" id="income-grand-total" placeholder="0.00" value="{{ isset($savedIncomes) ? number_format($savedIncomes->sum('total_amount'), 2) : '0.00' }}" readonly style="background-color: #f1f3f5;">
                                    </td>
                                    @if(!$isBudgetLocked) <td></td> @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white d-flex align-items-center py-2">
                    <h5 class="card-title mb-0 mt-1"><i class="fas fa-file-invoice-dollar mr-2"></i> ส่วนที่ 3.2: แผนรายจ่าย (ค่าดำเนินการ)</h5>
                    @if(!$isBudgetLocked)
                    <button type="button" class="btn btn-sm btn-success shadow-sm ml-auto" id="btn-add-expense">
                        <i class="fas fa-plus-circle"></i> เพิ่มแถวรายจ่าย
                    </button>
                    @endif
                </div>
                <div class="card-body bg-light p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" id="table-budget-expenses" style="min-width: 1300px;">
                            <thead class="bg-white text-center">
                                <tr>
                                    <th width="4%">ที่</th>
                                    <th width="15%">หมวดหมู่รายจ่าย <span class="text-danger">*</span></th>
                                    <th width="20%">รายละเอียดกิจกรรม</th>
                                    <th width="10%">ราคาต่อหน่วย (บาท)</th>
                                    <th width="8%">ตัวคูณ 1</th>
                                    <th width="8%">ตัวคูณ 2</th>
                                    <th width="10%">หน่วยนับ (เช่น มื้อ, คน)</th>
                                    <th width="12%">จำนวนเงินรวม (บาท)</th>
                                    <th width="8%">ถัวเฉลี่ยได้? <span class="text-info" title="ระบบ Backend จะบังคับค่านี้อัตโนมัติตามหมวดหมู่"><i class="fas fa-info-circle"></i></span></th>
                                    @if(!$isBudgetLocked) <th width="5%">จัดการ</th> @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="expense-row template-row d-none">
                                    <td class="text-center align-middle font-weight-bold row-index">#</td>
                                    <td>
                                        <select name="expenses[category_id][]" class="form-control expense-category-select select2-basic" style="width: 100%;">
                                            <option value="">-- เลือก --</option>
                                            @foreach($expenseCategoriesGrouped as $mainCat)
                                            <optgroup label="📌 {{ $mainCat->name_th }}">
                                                @foreach($mainCat->subCategories as $subCat)
                                                <option value="{{ $subCat->id }}" data-is-service-fee="{{ $subCat->is_service_fee }}">
                                                    {{ $subCat->name_th }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[description][]" class="form-control" placeholder="เช่น ค่าอาหารว่าง (50 คน x 3 วัน)">
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[cost_per_unit][]" class="form-control form-control-sm text-right expense-cost format-number-budget" placeholder="0.00" step="0.01" min="0">
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[factor_1][]" class="form-control form-control-sm text-center expense-factor1 format-number-budget format-expense" placeholder="จำนวนคน" min="1">
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[factor_2][]" class="form-control form-control-sm text-center expense-factor2 format-number-budget format-expense" placeholder="จำนวนวัน/มื้อ" min="1">
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[uom][]" class="form-control form-control-sm text-center" placeholder="เช่น คน/มื้อ">
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[total_amount][]" class="form-control form-control-sm text-danger text-right font-weight-bold expense-total-amount format-number-budget" placeholder="0.00" readonly style="background-color: #f1f3f5;">
                                    </td>
                                    <td class="text-center align-middle p-0">
                                        <div class="custom-control custom-switch pt-2">
                                            <input type="checkbox" class="custom-control-input can-average-switch" id="can_average_template">
                                            <label class="custom-control-label" for="can_average_template"></label>
                                            <input type="hidden" name="expenses[can_average][]" class="can-average-hidden" value="1">
                                        </div>
                                    </td>
                                    @if(!$isBudgetLocked)
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="fas fa-trash"></i></button>
                                    </td>
                                    @endif
                                </tr>
                                @if(isset($savedExpenses) && $savedExpenses->count() > 0)
                                @foreach($savedExpenses as $index => $expense)
                                <tr class="expense-row">
                                    <td class="text-center align-middle font-weight-bold row-index">{{ $index + 1 }}</td>
                                    <td>
                                        <select name="expenses[category_id][]" class="form-control expense-category-select select2-basic" style="width: 100%; {{ $isBudgetLocked ? 'pointer-events: none; background-color: #e9ecef;' : '' }}" required tabindex="{{ $isBudgetLocked ? '-1' : '0' }}">
                                            <option value="">-- เลือก --</option>
                                            @foreach($expenseCategoriesGrouped as $mainCat)
                                            <optgroup label="📌 {{ $mainCat->name_th }}">
                                                @foreach($mainCat->subCategories as $subCat)
                                                <option value="{{ $subCat->id }}" data-is-service-fee="{{ $subCat->is_service_fee }}" {{ $expense->category_id == $subCat->id ? 'selected' : '' }}>
                                                    {{ $subCat->name_th }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[description][]" class="form-control" value="{{ $expense->description }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[cost_per_unit][]" class="form-control form-control-sm text-right expense-cost format-number-budget" value="{{ number_format($expense->cost_per_unit, 2) }}" required {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[factor_1][]" class="form-control form-control-sm text-center expense-factor1 format-number-budget format-expense" value="{{ $expense->factor_1 }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[factor_2][]" class="form-control form-control-sm text-center expense-factor2 format-number-budget format-expense" value="{{ $expense->factor_2 }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[uom][]" class="form-control form-control-sm text-center" value="{{ $expense->uom }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="expenses[total_amount][]" class="form-control form-control-sm text-danger text-right font-weight-bold expense-total-amount format-number-budget" value="{{ number_format($expense->total_amount, 2) }}" readonly style="background-color: #f1f3f5;">
                                    </td>
                                    <td class="text-center align-middle p-0">
                                        <div class="custom-control custom-switch pt-2">
                                            <input type="checkbox" class="custom-control-input can-average-switch" id="avg_{{ $expense->id }}" {{ $expense->can_average ? 'checked' : '' }} {{ $isBudgetLocked ? 'disabled' : '' }}>
                                            <label class="custom-control-label" for="avg_{{ $expense->id }}"></label>
                                            <input type="hidden" name="expenses[can_average][]" class="can-average-hidden" value="{{ $expense->can_average ? 1 : 0 }}">
                                        </div>
                                    </td>
                                    @if(!$isBudgetLocked)
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="fas fa-trash"></i></button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                            <tfoot class="bg-white">
                                <tr>
                                    <td colspan="7" class="text-right font-weight-bold p-3">รวมเป็นเงินทั้งสิ้น (บาท)</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm text-danger text-right font-weight-bold format-expense" id="expense-grand-total" placeholder="0.00" value="{{ isset($savedExpenses) ? number_format($savedExpenses->sum('total_amount'), 2) : '0.00' }}" readonly style="background-color: #f1f3f5;">
                                    </td>
                                    <td></td>
                                    @if(!$isBudgetLocked) <td></td> @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white d-flex align-items-center py-2">
                    <h5 class="card-title mb-0 mt-1"><i class="fas fa-file-invoice-dollar mr-2"></i> ส่วนที่ 3.3: แผนรายจ่าย (ค่าตอบแทน)</h5>
                    @if(!$isBudgetLocked)
                    <button type="button" class="btn btn-sm btn-success shadow-sm ml-auto" id="btn-add-remuneration">
                        <i class="fas fa-plus-circle"></i> เพิ่มแถวรายจ่าย
                    </button>
                    @endif
                </div>
                <div class="card-body bg-light p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" id="table-budget-remuneration" style="min-width: 1300px;">
                            <thead class="bg-white text-center">
                                <tr>
                                    <th width="4%">ที่</th>
                                    <th width="15%">หมวดหมู่รายจ่าย <span class="text-danger">*</span></th>
                                    <th width="20%">รายละเอียดกิจกรรม</th>
                                    <th width="10%">ราคาต่อหน่วย (บาท)</th>
                                    <th width="8%">ตัวคูณ 1</th>
                                    <th width="8%">ตัวคูณ 2</th>
                                    <th width="10%">หน่วยนับ (เช่น มื้อ, คน)</th>
                                    <th width="12%">จำนวนเงินรวม (บาท)</th>
                                    <th width="8%">ถัวเฉลี่ยได้? <span class="text-info" title="ระบบ Backend จะบังคับค่านี้อัตโนมัติตามหมวดหมู่"><i class="fas fa-info-circle"></i></span></th>
                                    @if(!$isBudgetLocked) <th width="5%">จัดการ</th> @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="remuneration-row template-row d-none">
                                    <td class="text-center align-middle font-weight-bold row-index"></td>
                                    <td>
                                        <select name="remunerations[category_id][]" class="form-control remuneration-category-select select2-basic" style="width: 100%;">
                                            <option value="">-- เลือก --</option>
                                            @foreach($expenseCategoriesGrouped as $mainCat)
                                            <optgroup label="📌 {{ $mainCat->name_th }}">
                                                @foreach($mainCat->subCategories as $subCat)
                                                <option value="{{ $subCat->id }}">{{ $subCat->name_th }}</option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[description][]" class="form-control" placeholder="ระบุรายละเอียด">
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[cost_per_unit][]" class="form-control form-control-sm text-right remuneration-cost format-number-budget" placeholder="0.00" required>
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[factor_1][]" class="form-control form-control-sm text-center remuneration-factor1 format-number-budget" value="1">
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[factor_2][]" class="form-control form-control-sm text-center remuneration-factor2 format-number-budget" value="1">
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[uom][]" class="form-control form-control-sm text-center" placeholder="หน่วย">
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[total_amount][]" class="form-control form-control-sm text-danger text-right font-weight-bold remuneration-total-amount format-number-budget" placeholder="0.00" readonly style="background-color: #f1f3f5;">
                                    </td>
                                    <td class="text-center align-middle p-0">
                                        <div class="custom-control custom-switch pt-2">
                                            <input type="checkbox" class="custom-control-input can-average-switch" id="avg_remun_template">
                                            <label class="custom-control-label" for="avg_remun_template"></label>
                                            <input type="hidden" name="remunerations[can_average][]" class="can-average-hidden" value="1">
                                        </div>
                                    </td>
                                    @if(!$isBudgetLocked)
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="fas fa-trash"></i></button>
                                    </td>
                                    @endif
                                </tr>

                                @if(isset($savedRemunerations) && $savedRemunerations->count() > 0)
                                @foreach($savedRemunerations as $index => $remun)
                                <tr class="remuneration-row">
                                    <td class="text-center align-middle font-weight-bold row-index">{{ $index + 1 }}</td>
                                    <td>
                                        <select name="remunerations[category_id][]" class="form-control remuneration-category-select select2-basic" style="width: 100%; {{ $isBudgetLocked ? 'pointer-events: none; background-color: #e9ecef;' : '' }}" required tabindex="{{ $isBudgetLocked ? '-1' : '0' }}">
                                            <option value="">-- เลือก --</option>
                                            @foreach($expenseCategoriesGrouped as $mainCat)
                                            <optgroup label="📌 {{ $mainCat->name_th }}">
                                                @foreach($mainCat->subCategories as $subCat)
                                                <option value="{{ $subCat->id }}" {{ $remun->category_id == $subCat->id ? 'selected' : '' }}>
                                                    {{ $subCat->name_th }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[description][]" class="form-control" value="{{ $remun->description }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[cost_per_unit][]" class="form-control form-control-sm text-right remuneration-cost format-number-budget" value="{{ number_format($remun->cost_per_unit, 2) }}" required {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[factor_1][]" class="form-control form-control-sm text-center remuneration-factor1 format-number-budget" value="{{ $remun->factor_1 }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[factor_2][]" class="form-control form-control-sm text-center remuneration-factor2 format-number-budget" value="{{ $remun->factor_2 }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[uom][]" class="form-control form-control-sm text-center" value="{{ $remun->uom }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="remunerations[total_amount][]" class="form-control form-control-sm text-danger text-right font-weight-bold remuneration-total-amount format-number-budget" value="{{ number_format($remun->total_amount, 2) }}" readonly style="background-color: #f1f3f5;">
                                    </td>
                                    <td class="text-center align-middle p-0">
                                        <div class="custom-control custom-switch pt-2">
                                            <input type="checkbox" class="custom-control-input can-average-switch" id="avg_remun_{{ $remun->id }}" {{ $remun->can_average ? 'checked' : '' }} {{ $isBudgetLocked ? 'disabled' : '' }}>
                                            <label class="custom-control-label" for="avg_remun_{{ $remun->id }}"></label>
                                            <input type="hidden" name="remunerations[can_average][]" class="can-average-hidden" value="{{ $remun->can_average ? 1 : 0 }}">
                                        </div>
                                    </td>
                                    @if(!$isBudgetLocked)
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="fas fa-trash"></i></button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                            <tfoot class="bg-white">
                                <tr>
                                    <td colspan="7" class="text-right font-weight-bold p-3">รวมเป็นเงินทั้งสิ้น (บาท)</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm text-danger text-right font-weight-bold format-remuneration" id="remuneration-grand-total" placeholder="0.00" value="{{ isset($savedRemunerations) ? number_format($savedRemunerations->sum('total_amount'), 2) : '0.00' }}" readonly style="background-color: #f1f3f5;">
                                    </td>
                                    <td></td>
                                    @if(!$isBudgetLocked) <td></td> @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white d-flex align-items-center py-2">
                    <h5 class="card-title mb-0 mt-1"><i class="fas fa-calculator mr-2"></i> ส่วนที่ 3.4: ข้อมูลลงงบประมาณโครงการ (สรุปค่าธรรมเนียม)</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-4 border-right pt-3">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-5 col-form-label text-right">งบประมาณทั้งโครงการ <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control text-right font-weight-bold text-dark format-summary" name="total_budget_summary" value="{{ isset($savedBudget->total_budget_summary) ? number_format($savedBudget->total_budget_summary, 2) : '0.00' }}" readonly style="background-color: #f8f9fa;">
                                </div>
                                <label class="col-sm-2 col-form-label px-0">บาท</label>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-5 col-form-label text-right">เงินค่าล่วงหน้าทั้งหมด</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control text-right format-summary" name="total_advance_amount" value="{{ isset($savedBudget->total_advance_amount) ? number_format($savedBudget->total_advance_amount, 2) : '0.00' }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                </div>
                                <label class="col-sm-2 col-form-label px-0">บาท</label>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-5 col-form-label text-right text-danger">ค่าปรับรวมทั้งหมด</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control text-right text-danger bg-light format-summary" name="total_fine_amount" value="{{ isset($savedBudget->total_fine_amount) ? number_format($savedBudget->total_fine_amount, 2) : '0.00' }}" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                </div>
                                <label class="col-sm-2 col-form-label px-0">บาท</label>
                            </div>
                        </div>

                        <div class="col-md-8 pt-2">
                            <div class="card mb-3" style="border: 1px solid #ffc107; border-radius: 8px;">
                                <div class="card-body" style="background-color: #fffdf5; border-radius: 8px;">
                                    <h6 class="text-danger font-weight-bold mb-3"><i class="fas fa-calculator"></i> คำนวณตามเกณฑ์ค่าธรรมเนียม 15% (แนะนำ)</h6>

                                    <div class="row text-center mb-4">
                                        <div class="col-md-6">
                                            ค่าใช้จ่ายที่สามารถใช้ได้สูงสุด : <span class="text-primary font-weight-bold" id="max_expense_label" style="font-size: 1.1em;">0.00 บาท</span>
                                        </div>
                                        <div class="col-md-6">
                                            ค่าธรรมเนียมบริการวิชาการ : <span class="text-primary font-weight-bold" id="service_fee_label" style="font-size: 1.1em;">0.00 บาท</span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row mb-2">
                                                <label class="col-sm-5 col-form-label text-right">ค่าตอบแทน</label>
                                                <div class="col-sm-5"><input type="text" class="form-control form-control-sm text-right format-summary" name="remuneration_fee" value="{{ isset($savedRemunerations) ? number_format($savedRemunerations->sum('total_amount'), 2) : '0.00' }}" readonly style="background-color: #f8f9fa;"></div>
                                                <label class="col-sm-2 col-form-label px-0">บาท</label>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <label class="col-sm-5 col-form-label text-right">ค่าธรรมเนียมบริการวิชาการ</label>
                                                <div class="col-sm-5"><input type="number" class="form-control form-control-sm text-right" name="service_fee_percent" value="{{ old('service_fee_percent', $project->service_fee_percent ?? '0.00') }}" step="0.01" {{ $isBudgetLocked ? 'readonly' : '' }}></div>
                                                <label class="col-sm-2 col-form-label px-0">%</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-2">
                                                <label class="col-sm-5 col-form-label text-right">ค่าดำเนินการ</label>
                                                <div class="col-sm-5"><input type="text" class="form-control form-control-sm text-right format-summary" name="operation_fee" value="{{ isset($savedExpenses) ? number_format($savedExpenses->sum('total_amount'), 2) : '0.00' }}" readonly style="background-color: #f8f9fa;"></div>
                                                <label class="col-sm-2 col-form-label px-0">บาท</label>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <label class="col-sm-5 col-form-label text-right">คิดเป็น</label>
                                                <div class="col-sm-5"><input type="text" class="form-control form-control-sm text-right bg-light format-summary" name="service_fee_amount" value="{{ old('service_fee_amount', number_format($project->service_fee_amount ?? 0, 2)) }}" readonly></div>
                                                <label class="col-sm-2 col-form-label px-0">บาท</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card" style="border: 1px solid #fd7e14; border-radius: 8px;">
                                <div class="card-body" style="background-color: #fff6ee; border-radius: 8px;">
                                    <h6 class="font-weight-bold mb-3" style="color: #d35400;"><i class="fas fa-chart-pie"></i> จัดสรรค่าธรรมเนียมบริการวิชาการ</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row mb-2">
                                                <label class="col-sm-5 col-form-label text-right">ค่าธรรมเนียมมหาวิทยาลัย</label>
                                                <div class="col-sm-5">
                                                    <input type="number" class="form-control form-control-sm text-right" name="alloc_uni_percent" value="{{ old('alloc_uni_percent', $project->alloc_uni_percent ?? '1.50') }}" step="0.01" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                                </div>
                                                <label class="col-sm-2 col-form-label px-0">%</label>
                                            </div>
                                            <div class="form-group row mb-2">
                                                <label class="col-sm-5 col-form-label text-right">ค่าธรรมเนียมวิทยาเขต</label>
                                                <div class="col-sm-5">
                                                    <input type="number" class="form-control form-control-sm text-right" name="alloc_campus_percent" value="{{ old('alloc_campus_percent', $project->alloc_campus_percent ?? '2.50') }}" step="0.01" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                                </div>
                                                <label class="col-sm-2 col-form-label px-0">%</label>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <label class="col-sm-5 col-form-label text-right font-weight-bold">ค่าธรรมเนียมคณะ/หน่วยงาน</label>
                                                <div class="col-sm-5">
                                                    <input type="number" class="form-control form-control-sm text-right" name="alloc_dept_percent" value="{{ old('alloc_dept_percent', $project->alloc_dept_percent ?? '11.00') }}" step="0.01" {{ $isBudgetLocked ? 'readonly' : '' }}>
                                                </div>
                                                <label class="col-sm-2 col-form-label px-0">%</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row mb-2">
                                                <label class="col-sm-5 col-form-label text-right">คิดเป็น</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control form-control-sm text-right bg-light format-summary" name="alloc_uni_amount" value="{{ old('alloc_uni_amount', number_format($project->alloc_uni_amount ?? 0, 2)) }}" readonly>
                                                </div>
                                                <label class="col-sm-2 col-form-label px-0">บาท</label>
                                            </div>
                                            <div class="form-group row mb-2">
                                                <label class="col-sm-5 col-form-label text-right">คิดเป็น</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control form-control-sm text-right bg-light format-summary" name="alloc_campus_amount" value="{{ old('alloc_campus_amount', number_format($project->alloc_campus_amount ?? 0, 2)) }}" readonly>
                                                </div>
                                                <label class="col-sm-2 col-form-label px-0">บาท</label>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <label class="col-sm-5 col-form-label text-right font-weight-bold">คิดเป็น</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control form-control-sm text-right bg-light format-summary" name="alloc_dept_amount" value="{{ old('alloc_dept_amount', number_format($project->alloc_dept_amount ?? 0, 2)) }}" readonly>
                                                </div>
                                                <label class="col-sm-2 col-form-label px-0">บาท</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="border border-info rounded p-3" style="background-color: #f8fbff;">
                                                <div class="d-flex align-items-center mb-3 pb-2 border-bottom border-info">
                                                    <span class="badge badge-info mr-2" style="font-size: 0.85rem;">จัดสรรย่อย</span>
                                                    <strong class="text-info" style="font-size: 0.95rem;">สัดส่วนย่อยภายในคณะ/หน่วยงาน (คำนวณอัตโนมัติ)</strong>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row mb-2">
                                                            <label class="col-sm-5 col-form-label text-right text-info font-weight-bold">กองทุนวิจัย</label>
                                                            <div class="col-sm-5">
                                                                <input type="number" class="form-control form-control-sm text-right bg-light sub-dept-percent" name="fund_research_percent" value="{{ old('fund_research_percent', $project->fund_research_percent ?? '0.550') }}" step="0.001" readonly>
                                                            </div>
                                                            <label class="col-sm-2 col-form-label px-0 text-info">%</label>
                                                        </div>
                                                        <div class="form-group row mb-2">
                                                            <label class="col-sm-5 col-form-label text-right text-info font-weight-bold">คณะ</label>
                                                            <div class="col-sm-5">
                                                                <input type="number" class="form-control form-control-sm text-right bg-light sub-dept-percent" name="faculty_percent" value="{{ old('faculty_percent', $project->faculty_percent ?? '5.225') }}" step="0.001" readonly>
                                                            </div>
                                                            <label class="col-sm-2 col-form-label px-0 text-info">%</label>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <label class="col-sm-5 col-form-label text-right text-info font-weight-bold">ศูนย์</label>
                                                            <div class="col-sm-5">
                                                                <input type="number" class="form-control form-control-sm text-right bg-light sub-dept-percent" name="center_percent" value="{{ old('center_percent', $project->center_percent ?? '5.225') }}" step="0.001" readonly>
                                                            </div>
                                                            <label class="col-sm-2 col-form-label px-0 text-info">%</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row mb-2">
                                                            <label class="col-sm-5 col-form-label text-right text-info">คิดเป็น</label>
                                                            <div class="col-sm-5">
                                                                <input type="text" class="form-control form-control-sm text-right bg-light format-summary sub-dept-amount" name="fund_research_amount" value="{{ old('fund_research_amount', number_format($project->fund_research_amount ?? 0, 2)) }}" readonly>
                                                            </div>
                                                            <label class="col-sm-2 col-form-label px-0 text-info">บาท</label>
                                                        </div>
                                                        <div class="form-group row mb-2">
                                                            <label class="col-sm-5 col-form-label text-right text-info">คิดเป็น</label>
                                                            <div class="col-sm-5">
                                                                <input type="text" class="form-control form-control-sm text-right bg-light format-summary sub-dept-amount" name="faculty_amount" value="{{ old('faculty_amount', number_format($project->faculty_amount ?? 0, 2)) }}" readonly>
                                                            </div>
                                                            <label class="col-sm-2 col-form-label px-0 text-info">บาท</label>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <label class="col-sm-5 col-form-label text-right text-info">คิดเป็น</label>
                                                            <div class="col-sm-5">
                                                                <input type="text" class="form-control form-control-sm text-right bg-light format-summary sub-dept-amount" name="center_amount" value="{{ old('center_amount', number_format($project->center_amount ?? 0, 2)) }}" readonly>
                                                            </div>
                                                            <label class="col-sm-2 col-form-label px-0 text-info">บาท</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-white text-right py-3 border-top">
                <button type="button" class="btn btn-secondary mr-2" onclick="$('.wizard-nav a[href=\'#tab2\']').tab('show')">
                    <i class="fas fa-arrow-left"></i> ย้อนกลับ
                </button>
                @if(!$isBudgetLocked)
                <button type="submit" class="btn btn-primary shadow-sm" id="btn-submit-tab3">
                    <i class="fas fa-save mr-1"></i> บันทึกข้อมูลงบประมาณ & ถัดไป <i class="fas fa-arrow-right ml-1"></i>
                </button>
                @else
                <button type="button" class="btn btn-secondary shadow-sm" disabled>
                    <i class="fas fa-lock mr-1"></i> งบประมาณถูกล็อก (ไม่สามารถบันทึกได้)
                </button>
                @endif
            </div>
        </form>
    </div>

    <!-- แท็บที่ 4: ผลลัพธ์ & ประเมิน -->
    <div class="tab-pane fade {{ ($activeTab ?? '') == 'tab4' ? 'show active' : '' }}" id="tab4" role="tabpanel">

        @if(isset($project->overall_status) && $project->overall_status >= 600)
        <form action="{{ route('contracts.projects.update', $project->id) }}" method="POST" id="form-tab4-evaluation">
            @csrf
            @method('PUT')
            <input type="hidden" name="step" value="4">

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white py-2">
                    <h5 class="card-title mb-0 mt-1"><i class="fas fa-smile mr-2"></i> ส่วนที่ 4.1: ประเมินความพึงพอใจ</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <h6 class="font-weight-bold text-success mb-3"><i class="fas fa-thumbs-up"></i> ด้านความพึงพอใจ</h6>
                            <div class="row">
                                <div class="col-sm-5 form-group">
                                    <label>คะแนน <small class="text-danger font-weight-bold">(เต็ม 5)</small></label>
                                    <div class="input-group">
                                        <input type="number" name="satisfaction_score" id="satisfaction_score" class="form-control text-center text-primary font-weight-bold" value="{{ old('satisfaction_score', $projectEvaluation->satisfaction_score ?? '') }}" step="0.01" min="0" max="5" placeholder="0.00">
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text bg-light"><i class="fas fa-arrow-right"></i></span>
                                        </div>
                                        <input type="number" name="satisfaction_percent" id="satisfaction_percent" class="form-control text-center text-success font-weight-bold" value="{{ old('satisfaction_percent', $projectEvaluation->satisfaction_percent ?? '') }}" readonly style="background-color: #e9ecef;" placeholder="0.00">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-white">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>พิสัย (Range)</label>
                                    <input type="text" name="satisfaction_range" class="form-control text-center" value="{{ old('satisfaction_range', $projectEvaluation->satisfaction_range ?? '') }}">
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label>ระดับ</label>
                                    <select name="satisfaction_level" class="form-control">
                                        <option value="">-- เลือก --</option>
                                        <option value="4" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '5') ? 'selected' : '' }}>มากที่สุด</option>
                                        <option value="3" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '4') ? 'selected' : '' }}>มาก</option>
                                        <option value="3" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '3') ? 'selected' : '' }}>ปานกลาง</option>
                                        <option value="2" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '2') ? 'selected' : '' }}>น้อย</option>
                                        <option value="1" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '1') ? 'selected' : '' }}>น้อยที่สุด</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-danger mb-3"><i class="fas fa-thumbs-down"></i> ด้านความไม่พึงพอใจ</h6>
                            <div class="row">
                                <div class="col-sm-5 form-group">
                                    <label>คะแนน <small class="text-danger font-weight-bold">(เต็ม 5)</small></label>
                                    <div class="input-group">
                                        <input type="number" name="dissatisfaction_score" id="dissatisfaction_score" class="form-control text-center text-danger font-weight-bold" value="{{ old('dissatisfaction_score', $projectEvaluation->dissatisfaction_score ?? '') }}" step="0.01" min="0" max="5" placeholder="0.00">
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text bg-light"><i class="fas fa-arrow-right"></i></span>
                                        </div>
                                        <input type="number" name="dissatisfaction_percent" id="dissatisfaction_percent" class="form-control text-center text-warning font-weight-bold" value="{{ old('dissatisfaction_percent', $projectEvaluation->dissatisfaction_percent ?? '') }}" readonly style="background-color: #e9ecef;" placeholder="0.00">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-white">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>พิสัย (Range)</label>
                                    <input type="text" name="dissatisfaction_range" class="form-control text-center" value="{{ old('dissatisfaction_range', $projectEvaluation->dissatisfaction_range ?? '') }}">
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label>ระดับ</label>
                                    <select name="dissatisfaction_level" class="form-control">
                                        <option value="">-- เลือก --</option>
                                        <option value="1" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '1') ? 'selected' : '' }}>น้อยที่สุด</option>
                                        <option value="2" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '2') ? 'selected' : '' }}>น้อย</option>
                                        <option value="3" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '3') ? 'selected' : '' }}>ปานกลาง</option>
                                        <option value="3" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '4') ? 'selected' : '' }}>มาก</option>
                                        <option value="4" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '5') ? 'selected' : '' }}>มากที่สุด</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white py-2">
                    <h5 class="card-title mb-0 mt-1"><i class="fas fa-clipboard-check mr-2"></i> ส่วนที่ 4.2: อื่นๆ (การบูรณาการ และ ผลกระทบ)</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <div class="form-group">
                                <label class="text-primary"><i class="fas fa-hand-point-right"></i> การนำผลประเมินไปปรับปรุง</label>
                                <textarea name="improvement_apply" class="form-control" rows="4">{{ old('improvement_apply', $projectEvaluation->improvement_apply ?? '') }}</textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label class="text-primary"><i class="fas fa-hand-point-right"></i> ผลกระทบของกิจกรรม</label>
                                <textarea name="impact" class="form-control" rows="4">{{ old('impact', $projectEvaluation->impact ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-info"><i class="fas fa-hand-point-right"></i> การบูรณาการ</label>
                                <textarea name="integration" class="form-control" rows="4">{{ old('integration', $projectEvaluation->integration ?? '') }}</textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label class="text-info"><i class="fas fa-hand-point-right"></i> การประเมินการบูรณาการ / การนำผลไปปรับปรุง</label>
                                <textarea name="integration_eval" class="form-control" rows="4">{{ old('integration_eval', $projectEvaluation->integration_eval ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 project-section">
                <div class="card-header bg-custom-dark text-white py-2">
                    <h5 class="card-title mb-0 mt-1"><i class="fas fa-chart-pie mr-2"></i> ส่วนที่ 4.3: ผลสัมฤทธิ์และมูลค่าโครงการ (ถ้ามี)</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>คะแนน SROI</label>
                            <input type="number" name="sroi_score" class="form-control text-center text-primary font-weight-bold" value="{{ old('sroi_score', $projectEvaluation->sroi_score ?? '') }}" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>จำนวนรางวัล <small class="text-muted">(รางวัล)</small></label>
                            <input type="number" name="award_count" class="form-control text-center text-success font-weight-bold" value="{{ old('award_count', $projectEvaluation->award_count ?? '') }}" min="0" placeholder="0">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>มูลค่าที่ส่งมอบให้ภาคอุตสาหกรรม</label>
                            <div class="input-group">
                                <input type="number" name="industrial_value" class="form-control text-right text-danger font-weight-bold" value="{{ old('industrial_value', $projectEvaluation->industrial_value ?? '') }}" step="0.01" min="0" placeholder="0.00">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white">บาท</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 form-group mb-0">
                            <label class="text-dark"><i class="fas fa-award text-warning"></i> ผลสัมฤทธิ์โครงการ (สิ่งที่ได้รับ)</label>
                            <textarea name="project_achievement" class="form-control" rows="3">{{ old('project_achievement', $projectEvaluation->project_achievement ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white text-right py-3 border-top">
                    <button type="button" class="btn btn-secondary mr-2" onclick="$('.wizard-nav a[href=\'#tab3\']').tab('show')">
                        <i class="fas fa-arrow-left"></i> ย้อนกลับ
                    </button>
                    <button type="submit" class="btn btn-primary shadow-sm" id="btn-submit-tab4">
                        <i class="fas fa-save mr-1"></i> บันทึกข้อมูลการประเมิน & ถัดไป <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </form>
        @else
        <div class="card shadow-sm border-0 mb-4 project-section">
            <div class="card-body text-center py-5">
                <i class="fas fa-file-signature fa-4x text-warning mb-3"></i>
                <h4 class="font-weight-bold text-dark">ยังไม่สามารถประเมินผลโครงการได้</h4>
                <p class="text-muted mb-4" style="font-size: 1.1em;">
                    เนื่องจากโครงการอยู่ในสถานะ
                    <strong class="text-primary border px-2 py-1 bg-light rounded">
                        @if($project->overall_status == 100) เตรียมการ / ฉบับร่าง
                        @elseif($project->overall_status == 110) ตีกลับ
                        @elseif($project->overall_status == 200) เสนอขออนุมัติ
                        @elseif($project->overall_status == 300) อนุมัติแล้ว / รอเปิดรับสมัคร
                        @elseif($project->overall_status == 400) เปิดรับสมัคร
                        @elseif($project->overall_status == 500) ปิดรับสมัคร / เตรียมจัดงาน
                        @elseif($project->overall_status == 600) อยู่ระหว่างดำเนินการ
                        @else {{ $project->overall_status }} @endif
                    </strong><br>
                    คุณจะสามารถกรอกข้อมูลส่วนนี้ได้ ก็ต่อเมื่อโครงการเข้าสู่สถานะ <strong>"อยู่ระหว่างดำเนินการ"</strong> ขึ้นไปแล้วเท่านั้น
                </p>

                <button type="button" class="btn btn-secondary mr-2" onclick="$('.wizard-nav a[href=\'#tab3\']').tab('show')">
                    <i class="fas fa-arrow-left"></i> ย้อนกลับไปแท็บก่อนหน้า
                </button>
                <button type="button" class="btn btn-primary shadow-sm" onclick="$('.wizard-nav a[href=\'#tab5\']').tab('show')">
                    ข้ามไปดูภาพรวมโครงการ <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
        @endif

    </div>

    <!-- แท็บที่ 5: ภาพรวม (อัปเดตใหม่ แสดงข้อมูลครบ 100%) -->
    <div class="tab-pane fade {{ $activeTab == 'tab5' ? 'show active' : '' }}" id="tab5" role="tabpanel">
        <form action="{{ route('contracts.projects.update', $project->id) }}" method="POST" id="form-tab5-overview">
            @csrf
            @method('PUT')
            <input type="hidden" name="step" value="5">
            <div class="alert alert-info shadow-sm border-0 mb-4">
                <i class="fas fa-search-plus mr-2"></i> <strong>ตรวจสอบข้อมูล:</strong> กรุณาตรวจสอบภาพรวมของโครงการทั้งหมด หากถูกต้องครบถ้วนแล้ว สามารถกดปุ่มเพื่อยื่นขออนุมัติโครงการได้ที่ด้านล่างสุด
            </div>

            <!-- 1. ข้อมูลพื้นฐานโครงการ -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-custom-dark text-white py-2">
                    <h6 class="mb-0 mt-1"><i class="fas fa-file-alt mr-2"></i> 1. ข้อมูลพื้นฐานโครงการ</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th width="20%" class="bg-light text-right align-middle">รหัสโครงการ :</th>
                                <td width="30%" class="align-middle font-weight-bold text-success">
                                    {{ $project->id }}
                                </td>
                                <th width="20%" class="bg-light text-right align-middle">ปีงบประมาณ :</th>
                                <td width="30%" class="align-middle font-weight-bold">
                                    {{ $fiscalYears->where('id', $project->fiscal_year_id)->first()->fiscal_year_be ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-middle">ชื่อโครงการ :</th>
                                <td colspan="3" class="align-middle font-weight-bold text-primary" style="font-size: 1.1em;">
                                    {{ $project->name_th ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-top pt-3">หน่วยงานผู้รับผิดชอบ :</th>
                                <td colspan="3" class="align-middle">
                                    @if(!empty($selectedDepartments))
                                    @foreach($departments->whereIn('DEPARTMENT_ID', $selectedDepartments) as $dept)
                                    <span class="badge badge-info px-2 py-1 mr-1 mb-1" style="font-size: 0.9em; font-weight: normal;">{{ $dept->DEPARTMENT_NAME_TH }}</span>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-middle">ศูนย์ (Center) :</th>
                                <td class="align-middle">
                                    {{ $centers->where('id', $project->center_id)->first()->name_th ?? '-' }}
                                </td>
                                <th class="bg-light text-right align-middle">ระดับโครงการ :</th>
                                <td class="align-middle">
                                    @if(($project->region_type ?? '') == '1') ระดับชาติ
                                    @elseif(($project->region_type ?? '') == '2') ระดับนานาชาติ
                                    @else - @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-middle">ระยะเวลาโครงการ :</th>
                                <td colspan="3" class="align-middle">
                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->addYears(543)->format('d/m/Y') : '-' }}
                                    <strong class="mx-2">ถึง</strong>
                                    {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->addYears(543)->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                            <!-- เพิ่มส่วนที่ขาดหาย: วัตถุประสงค์, รายละเอียดโดยย่อ, หลักการและเหตุผล -->
                            <tr>
                                <th class="bg-light text-right align-top pt-3">วัตถุประสงค์โครงการ :</th>
                                <td colspan="3" class="align-middle">
                                    @if(isset($savedObjectives) && count($savedObjectives) > 0)
                                    <ul class="pl-3 mb-0" style="line-height: 1.6;">
                                        @foreach($savedObjectives as $obj)
                                        @php
                                        $groupName = $customerGroups->where('id', $obj->target_group_id)->first()->name_th ?? '-';
                                        @endphp
                                        <li><strong>{{ $groupName }}</strong> : {{ $obj->detail }}</li>
                                        @endforeach
                                    </ul>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-top pt-3">รายละเอียดโครงการโดยย่อ :</th>
                                <td colspan="3" class="align-middle" style="line-height: 1.6;">
                                    {!! !empty($project->brief_description) ? nl2br(e($project->brief_description)) : '<span class="text-muted">-</span>' !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-top pt-3">หลักการและเหตุผล :</th>
                                <td colspan="3" class="align-middle" style="line-height: 1.6;">
                                    {!! !empty($project->rationale) ? nl2br(e($project->rationale)) : '<span class="text-muted">-</span>' !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. ข้อมูลเฉพาะ & คณะทำงาน -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-custom-dark text-white py-2">
                    <h6 class="mb-0 mt-1"><i class="fas fa-info-circle mr-2"></i> 2. ข้อมูลเฉพาะ & คณะทำงาน</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <!-- เพิ่มส่วนที่ขาดหาย: เอกสารสัญญา -->
                            <tr>
                                <th width="20%" class="bg-light text-right align-middle">เลขที่สัญญา :</th>
                                <td width="30%" class="align-middle text-dark font-weight-bold">
                                    {{ $projectContract->contract_number ?? '-' }}
                                </td>
                                <th width="20%" class="bg-light text-right align-middle">เอกสารสัญญา/ลิ้งค์ :</th>
                                <td width="30%" class="align-middle">
                                    @if(!empty($projectContract->contract_file_path))
                                    <a href="{{ asset('storage/'.$projectContract->contract_file_path) }}" target="_blank" class="badge badge-info p-2 mb-1"><i class="fas fa-file-pdf"></i> ดูไฟล์แนบเดิม</a>
                                    @endif
                                    @if(!empty($projectContract->contract_file_link))
                                    <a href="{{ $projectContract->contract_file_link }}" target="_blank" class="badge badge-primary p-2 mb-1"><i class="fas fa-link"></i> ลิ้งค์เอกสาร</a>
                                    @endif
                                    @if(empty($projectContract->contract_file_path) && empty($projectContract->contract_file_link))
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-top pt-3">เกี่ยวข้องกับ SDGs :</th>
                                <td colspan="3" class="align-middle">
                                    @if(!empty($selectedSdgs))
                                    @foreach($sdgs->whereIn('id', $selectedSdgs) as $sdg)
                                    <span class="badge badge-success px-2 py-1 mr-1 mb-1" style="font-size: 0.9em; font-weight: normal;">
                                        <i class="fas fa-leaf mr-1"></i> SDGs {{ $sdg->id }}: {{ $sdg->name_th }}
                                    </span>
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-top pt-3">กลุ่มผู้ว่าจ้าง/แหล่งทุน :</th>
                                <td colspan="3" class="align-middle">
                                    @if(isset($savedTargetGroups) && $savedTargetGroups->count() > 0)
                                    <ul class="pl-3 mb-0" style="line-height: 1.6;">
                                        @foreach($savedTargetGroups as $stg)
                                        @php
                                        $tg = $filteredTargetGroups->where('id', $stg->target_group_id)->first();
                                        $nat = $nationalities->where('id', $stg->nationality_id)->first();
                                        @endphp
                                        <li>
                                            {{ $tg ? $tg->full_path : '-' }}
                                            <span class="text-muted">(เชื้อชาติ: {{ $nat ? $nat->name_th : '-' }})</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-top pt-3">คณะทำงาน :<br><small class="text-info font-weight-normal">(รวม {{ isset($savedCommittees) ? $savedCommittees->count() : '0' }} คน)</small></th>
                                <td colspan="3" class="align-middle">
                                    @if(isset($savedCommittees) && $savedCommittees->count() > 0)
                                    <ul class="pl-3 mb-0" style="line-height: 1.6;">
                                        @foreach($savedCommittees as $comm)
                                        @php
                                        $posName = $projectPositions->where('id', $comm->project_position_id)->first()->name_th ?? '-';

                                        if($comm->member_type == '1') {
                                        $staff = $staffs->where('STAFF_ID', $comm->personnel_id)->first();
                                        $name = $staff ? $staff->TITLE_TH . $staff->NAME_TH . ' ' . $staff->SURNAME_TH : '-';
                                        $typeBadge = '<span class="badge badge-primary px-2" style="font-size:0.8em; font-weight:normal;">บุคลากรในคณะ</span>';
                                        } else {
                                        $ext = $externals->where('id', $comm->external_id)->first();
                                        $name = $ext ? $ext->prefix->name_th . $ext->firstname . ' ' . $ext->lastname : '-';
                                        $typeBadge = '<span class="badge badge-secondary px-2" style="font-size:0.8em; font-weight:normal;">บุคคลภายนอก</span>';
                                        }
                                        @endphp
                                        <li class="mb-1">
                                            <strong class="text-dark">{{ $posName }} :</strong>
                                            <span class="text-info">{{ $name }}</span> {!! $typeBadge !!}
                                            @if($comm->remuneration_total > 0)
                                            <small class="text-danger ml-2"><i class="fas fa-coins"></i> ค่าตอบแทน: {{ number_format($comm->remuneration_total, 2) }} บาท</small>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @php
            $totalIncome = isset($savedIncomes) ? $savedIncomes->sum('total_amount') : 0;
            $totalExpense = isset($savedExpenses) ? $savedExpenses->sum('total_amount') : 0;
            $totalRemuneration = isset($savedRemunerations) ? $savedRemunerations->sum('total_amount') : 0;
            $serviceFee = $savedBudget->service_fee_amount ?? 0;
            $balance = $totalIncome - ($totalExpense + $totalRemuneration + $serviceFee);
            @endphp

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-custom-dark text-white py-2">
                    <h6 class="mb-0 mt-1"><i class="fas fa-file-invoice-dollar mr-2"></i> 3. รายละเอียดแผนงบประมาณ</h6>
                </div>
                <div class="card-body p-0">

                    <!-- 3.1 แผนรายรับ (โค้ดเดิม) -->
                    <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom">
                        <i class="fas fa-arrow-down mr-1 text-muted"></i> 3.1 แผนรายรับ
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="bg-white text-center text-muted" style="font-size: 0.9em;">
                                <tr>
                                    <th width="5%" class="align-middle">ที่</th>
                                    <th width="25%" class="align-middle">หมวดหมู่รายรับ</th>
                                    <th width="35%" class="align-middle">รายละเอียดกิจกรรม</th>
                                    <th width="12%" class="align-middle">อัตราจัดเก็บ (บาท)</th>
                                    <th width="8%" class="align-middle">จำนวน</th>
                                    <th width="15%" class="align-middle">จำนวนเงินรวม (บาท)</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.95em;">
                                @forelse($savedIncomes ?? [] as $index => $inc)
                                @php
                                $catName = '-';
                                if(isset($incomeCategoriesGrouped)) {
                                foreach($incomeCategoriesGrouped as $main) {
                                $found = $main->subCategories->where('id', $inc->category_id)->first();
                                if($found) { $catName = $found->name_th; break; }
                                }
                                }
                                @endphp
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle">{{ $catName }}</td>
                                    <td class="align-middle">{{ $inc->description }}</td>
                                    <td class="text-right align-middle">{{ number_format($inc->unit_cost, 2) }}</td>
                                    <td class="text-center align-middle">{{ number_format($inc->quantity) }}</td>
                                    <td class="text-right align-middle font-weight-bold text-dark">{{ number_format($inc->total_amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-2">ไม่มีข้อมูลแผนรายรับ</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light" style="font-size: 0.95em;">
                                <tr>
                                    <td colspan="5" class="text-right font-weight-bold align-middle">รวมรายรับทั้งสิ้น</td>
                                    <td class="text-right font-weight-bold text-dark align-middle" style="text-decoration: underline double;">
                                        {{ number_format($totalIncome, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- 3.2 แผนรายจ่าย (ค่าดำเนินการ) (โค้ดเดิม) -->
                    <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0">
                        <i class="fas fa-arrow-up mr-1 text-muted"></i> 3.2 แผนรายจ่าย (ค่าดำเนินการ)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="bg-white text-center text-muted" style="font-size: 0.9em;">
                                <tr>
                                    <th width="4%" class="align-middle">ที่</th>
                                    <th width="20%" class="align-middle">หมวดหมู่รายจ่าย</th>
                                    <th width="25%" class="align-middle">รายละเอียดกิจกรรม</th>
                                    <th width="12%" class="align-middle">ราคาต่อหน่วย (บาท)</th>
                                    <th width="8%" class="align-middle">ตัวคูณ 1</th>
                                    <th width="8%" class="align-middle">ตัวคูณ 2</th>
                                    <th width="8%" class="align-middle">หน่วยนับ</th>
                                    <th width="15%" class="align-middle">จำนวนเงินรวม (บาท)</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.95em;">
                                @forelse($savedExpenses ?? [] as $index => $exp)
                                @php
                                $catName = '-';
                                if(isset($expenseCategoriesGrouped)) {
                                foreach($expenseCategoriesGrouped as $main) {
                                $found = $main->subCategories->where('id', $exp->category_id)->first();
                                if($found) { $catName = $found->name_th; break; }
                                }
                                }
                                @endphp
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle">{{ $catName }}</td>
                                    <td class="align-middle">{{ $exp->description }}</td>
                                    <td class="text-right align-middle">{{ number_format($exp->cost_per_unit, 2) }}</td>
                                    <td class="text-center align-middle">{{ $exp->factor_1 ?? '-' }}</td>
                                    <td class="text-center align-middle">{{ $exp->factor_2 ?? '-' }}</td>
                                    <td class="text-center align-middle">{{ $exp->uom ?? '-' }}</td>
                                    <td class="text-right align-middle font-weight-bold text-dark">{{ number_format($exp->total_amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-2">ไม่มีข้อมูลค่าดำเนินการ</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light" style="font-size: 0.95em;">
                                <tr>
                                    <td colspan="7" class="text-right font-weight-bold align-middle">รวมค่าดำเนินการทั้งสิ้น</td>
                                    <td class="text-right font-weight-bold text-dark align-middle">
                                        {{ number_format($totalExpense, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- 3.3 แผนรายจ่าย (ค่าตอบแทน)-->
                    <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0">
                        <i class="fas fa-hand-holding-usd mr-1 text-muted"></i> 3.3 แผนรายจ่าย (ค่าตอบแทน)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="bg-white text-center text-muted" style="font-size: 0.9em;">
                                <tr>
                                    <th width="4%" class="align-middle">ที่</th>
                                    <th width="20%" class="align-middle">หมวดหมู่รายจ่าย</th>
                                    <th width="25%" class="align-middle">รายละเอียดกิจกรรม</th>
                                    <th width="12%" class="align-middle">ราคาต่อหน่วย (บาท)</th>
                                    <th width="8%" class="align-middle">ตัวคูณ 1</th>
                                    <th width="8%" class="align-middle">ตัวคูณ 2</th>
                                    <th width="8%" class="align-middle">หน่วยนับ</th>
                                    <th width="15%" class="align-middle">จำนวนเงินรวม (บาท)</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.95em;">
                                @forelse($savedRemunerations ?? [] as $index => $remun)
                                @php
                                $catName = '-';
                                if(isset($expenseCategoriesGrouped)) {
                                foreach($expenseCategoriesGrouped as $main) {
                                $found = $main->subCategories->where('id', $remun->category_id)->first();
                                if($found) { $catName = $found->name_th; break; }
                                }
                                }
                                @endphp
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle">{{ $catName }}</td>
                                    <td class="align-middle">{{ $remun->description }}</td>
                                    <td class="text-right align-middle">{{ number_format($remun->cost_per_unit, 2) }}</td>
                                    <td class="text-center align-middle">{{ $remun->factor_1 ?? '-' }}</td>
                                    <td class="text-center align-middle">{{ $remun->factor_2 ?? '-' }}</td>
                                    <td class="text-center align-middle">{{ $remun->uom ?? '-' }}</td>
                                    <td class="text-right align-middle font-weight-bold text-dark">{{ number_format($remun->total_amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-2">ไม่มีข้อมูลค่าตอบแทน</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light" style="font-size: 0.95em;">
                                <tr>
                                    <td colspan="7" class="text-right font-weight-bold align-middle">รวมค่าตอบแทนทั้งสิ้น</td>
                                    <td class="text-right font-weight-bold text-dark align-middle">
                                        {{ number_format($totalRemuneration, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- 🛑 3.4 สรุปค่าธรรมเนียม (แก้ไขเป็น $savedBudget ทั้งหมด) -->
                    <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0" style="background-color: #fffdf5 !important;">
                        <i class="fas fa-calculator mr-1 text-muted"></i> 3.4 สรุปจัดสรรค่าธรรมเนียม
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <tbody style="font-size: 0.95em;">
                                <tr>
                                    <th width="30%" class="bg-white text-right">งบประมาณทั้งโครงการ :</th>
                                    <td width="20%" class="text-right font-weight-bold text-success">{{ number_format($savedBudget->total_budget_summary ?? 0, 2) }}</td>
                                    <th width="30%" class="bg-white text-right">ค่าธรรมเนียมบริการวิชาการ ({{ $savedBudget->service_fee_percent ?? '0' }}%) :</th>
                                    <td width="20%" class="text-right font-weight-bold text-danger">{{ number_format($savedBudget->service_fee_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-right">ค่าธรรมเนียมมหาวิทยาลัย ({{ $savedBudget->alloc_uni_percent ?? '0' }}%) :</th>
                                    <td class="text-right">{{ number_format($savedBudget->alloc_uni_amount ?? 0, 2) }}</td>
                                    <th class="bg-light text-right text-info">กองทุนวิจัย ({{ $savedBudget->fund_research_percent ?? '0' }}%) :</th>
                                    <td class="text-right text-info">{{ number_format($savedBudget->fund_research_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-right">ค่าธรรมเนียมวิทยาเขต ({{ $savedBudget->alloc_campus_percent ?? '0' }}%) :</th>
                                    <td class="text-right">{{ number_format($savedBudget->alloc_campus_amount ?? 0, 2) }}</td>
                                    <th class="bg-light text-right text-info">ส่วนของคณะ ({{ $savedBudget->faculty_percent ?? '0' }}%) :</th>
                                    <td class="text-right text-info">{{ number_format($savedBudget->faculty_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-right">ค่าธรรมเนียมคณะ/หน่วยงาน ({{ $savedBudget->alloc_dept_percent ?? '0' }}%) :</th>
                                    <td class="text-right">{{ number_format($savedBudget->alloc_dept_amount ?? 0, 2) }}</td>
                                    <th class="bg-light text-right text-info">ส่วนของศูนย์ ({{ $savedBudget->center_percent ?? '0' }}%) :</th>
                                    <td class="text-right text-info">{{ number_format($savedBudget->center_amount ?? 0, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- เพิ่มส่วนที่ขาดหาย: 3.5 งวดงาน -->
                    <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0">
                        <i class="fas fa-list-ol mr-1 text-muted"></i> 3.5 ข้อมูลลงงวดงาน
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="bg-white text-center text-muted" style="font-size: 0.9em;">
                                <tr>
                                    <th width="10%">งวดที่</th>
                                    <th width="15%">ระยะเวลา (วัน)</th>
                                    <th width="20%">วันที่เริ่มต้น</th>
                                    <th width="20%">วันที่สิ้นสุด</th>
                                    <th width="20%">หักประกันผลงาน</th>
                                    <th width="15%">ยอดสุทธิ (บาท)</th>
                                </tr>
                            </thead>
                            <tbody class="text-center" style="font-size: 0.95em;">
                                @forelse($savedInstallments ?? [] as $inst)
                                <tr>
                                    <td class="align-middle font-weight-bold">{{ $inst->installment_no }}</td>
                                    <td class="align-middle">{{ $inst->duration_days ?? '-' }}</td>
                                    <td class="align-middle">{{ $inst->start_date_show ? \Carbon\Carbon::parse($inst->start_date_show)->addYears(543)->format('d/m/Y') : '-' }}</td>
                                    <td class="align-middle">{{ $inst->end_date_show ? \Carbon\Carbon::parse($inst->end_date_show)->addYears(543)->format('d/m/Y') : '-' }}</td>
                                    <td class="align-middle">{{ $inst->guarantee_pct ?? '0' }}% <br><small class="text-muted">({{ number_format($inst->guarantee_amt ?? 0, 2) }} บ.)</small></td>
                                    <td class="text-right align-middle font-weight-bold text-success">{{ number_format($inst->net_amount ?? 0, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-muted py-2">ไม่มีข้อมูลงวดงาน</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-white p-3 border-top text-right" style="font-size: 1em;">
                        <span class="text-dark font-weight-bold mr-2">
                            ยอดคงเหลือสุทธิ (รายรับ - (รายจ่าย + ค่าธรรมเนียม)) :
                        </span>
                        <strong class="{{ $balance < 0 ? 'text-danger' : 'text-primary' }}" style="text-decoration: underline double;">
                            {{ number_format($balance, 2) }} บาท
                        </strong>
                    </div>

                </div>
            </div>

            <!-- 4. สรุปผลลัพธ์โครงการ (แสดงเฉพาะสถานะที่กำหนด) -->
            @if(isset($project->overall_status) && $project->overall_status >= 700)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-custom-dark text-white py-2">
                    <h6 class="mb-0 mt-1"><i class="fas fa-chart-pie mr-2"></i> 4. สรุปผลลัพธ์โครงการ</h6>
                </div>
                <div class="card-body p-0">
                    <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom">
                        4.1 ประเมินความพึงพอใจ
                    </div>
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="bg-white text-center text-muted" style="font-size: 0.95em;">
                            <tr>
                                <th width="25%">ด้าน</th>
                                <th width="25%">คะแนน (เต็ม 5)</th>
                                <th width="25%">พิสัย (Range)</th>
                                <th width="25%">ระดับ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $evalLevels = [
                            '5' => 'มากที่สุด',
                            '4' => 'มาก',
                            '3' => 'ปานกลาง',
                            '2' => 'น้อย',
                            '1' => 'น้อยที่สุด'
                            ];
                            @endphp
                            <tr>
                                <td class="font-weight-bold text-success align-middle pl-3"><i class="fas fa-thumbs-up mr-1"></i> ความพึงพอใจ</td>
                                <td class="text-center align-middle font-weight-bold">
                                    {{ $projectEvaluation->satisfaction_score ?? '-' }}
                                    @if(isset($projectEvaluation->satisfaction_percent))
                                    <small class="text-muted font-weight-normal">({{ $projectEvaluation->satisfaction_percent }}%)</small>
                                    @endif
                                </td>
                                <td class="text-center align-middle">{{ $projectEvaluation->satisfaction_range ?? '-' }}</td>
                                <td class="text-center align-middle">
                                    {{ isset($projectEvaluation->satisfaction_level) && array_key_exists($projectEvaluation->satisfaction_level, $evalLevels) ? $evalLevels[$projectEvaluation->satisfaction_level] : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-danger align-middle pl-3"><i class="fas fa-thumbs-down mr-1"></i> ความไม่พึงพอใจ</td>
                                <td class="text-center align-middle font-weight-bold">
                                    {{ $projectEvaluation->dissatisfaction_score ?? '-' }}
                                    @if(isset($projectEvaluation->dissatisfaction_percent))
                                    <small class="text-muted font-weight-normal">({{ $projectEvaluation->dissatisfaction_percent }}%)</small>
                                    @endif
                                </td>
                                <td class="text-center align-middle">{{ $projectEvaluation->dissatisfaction_range ?? '-' }}</td>
                                <td class="text-center align-middle">
                                    {{ isset($projectEvaluation->dissatisfaction_level) && array_key_exists($projectEvaluation->dissatisfaction_level, $evalLevels) ? $evalLevels[$projectEvaluation->dissatisfaction_level] : '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0">
                        4.2 อื่นๆ (การบูรณาการ และ ผลกระทบ)
                    </div>
                    <table class="table table-bordered table-sm mb-0">
                        <tbody>
                            <tr>
                                <th width="35%" class="bg-white text-right align-top pt-2">การนำผลประเมินไปปรับปรุง :</th>
                                <td width="65%" class="align-middle" style="line-height: 1.6;">
                                    {!! !empty($projectEvaluation->improvement_apply) ? nl2br(e($projectEvaluation->improvement_apply)) : '<span class="text-muted">-</span>' !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-white text-right align-top pt-2">ผลกระทบของกิจกรรม :</th>
                                <td class="align-middle" style="line-height: 1.6;">
                                    {!! !empty($projectEvaluation->impact) ? nl2br(e($projectEvaluation->impact)) : '<span class="text-muted">-</span>' !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-white text-right align-top pt-2">การบูรณาการ :</th>
                                <td class="align-middle" style="line-height: 1.6;">
                                    {!! !empty($projectEvaluation->integration) ? nl2br(e($projectEvaluation->integration)) : '<span class="text-muted">-</span>' !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-white text-right align-top pt-2">การประเมินการบูรณาการ / การนำผลไปปรับปรุง :</th>
                                <td class="align-middle" style="line-height: 1.6;">
                                    {!! !empty($projectEvaluation->integration_eval) ? nl2br(e($projectEvaluation->integration_eval)) : '<span class="text-muted">-</span>' !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0">
                        4.3 ผลสัมฤทธิ์และมูลค่าโครงการ (ถ้ามี)
                    </div>
                    <table class="table table-bordered table-sm mb-0">
                        <tbody>
                            <tr class="text-center bg-white text-muted" style="font-size: 0.95em;">
                                <th width="33%" class="align-middle py-2">คะแนน SROI</th>
                                <th width="33%" class="align-middle py-2">จำนวนรางวัล (รางวัล)</th>
                                <th width="34%" class="align-middle py-2">มูลค่าที่ส่งมอบให้ภาคอุตสาหกรรม (บาท)</th>
                            </tr>
                            <tr class="text-center font-weight-bold" style="font-size: 1.1em;">
                                <td class="align-middle text-primary py-3">{{ $projectEvaluation->sroi_score ?? '-' }}</td>
                                <td class="align-middle text-success py-3">{{ $projectEvaluation->award_count ?? '0' }}</td>
                                <td class="align-middle text-dark py-3">{{ number_format($projectEvaluation->industrial_value ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-right align-top pt-3" style="width: 20%;">ผลสัมฤทธิ์โครงการ<br><small class="text-muted">(สิ่งที่ได้รับ)</small> :</th>
                                <td colspan="2" class="align-middle bg-white" style="line-height: 1.6;">
                                    {!! !empty($projectEvaluation->project_achievement) ? nl2br(e($projectEvaluation->project_achievement)) : '<span class="text-muted">-</span>' !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- 5. ผู้ลงนาม (เหมือนเดิม) -->
            <div class="card shadow-sm border-0 mb-4 d-print-none">
                <div class="card-header bg-custom-dark text-white py-2">
                    <h6 class="mb-0 mt-1"><i class="fas fa-signature mr-2"></i> 5. รายชื่อผู้ลงนามโครงการ (แสดงผลในหน้าปริ้นเอกสาร)</h6>
                </div>
                <div class="card-body bg-light">
                    @php
                    $savedSignatures = \App\Models\MasterData\AcademicProjectSignature::where('academic_project_id', $project->id)->orderBy('sign_order')->get();
                    @endphp
                    <div id="signature-container">
                        @if($savedSignatures->isNotEmpty())
                        @foreach($savedSignatures as $index => $savedSig)
                        <div class="row signature-row align-items-center mb-3 p-3 border rounded bg-light">
                            <div class="col-md-4">
                                <label>ชื่อ-นามสกุลผู้ลงนาม <span class="text-danger">*</span></label>
                                <select name="signatures[{{ $index }}][staff_id]" class="form-control select2-staff" required>
                                    <option value="">-- ค้นหาชื่อบุคลากร --</option>
                                    @foreach($staffs as $staff)
                                    <option value="{{ $staff->STAFF_ID }}"
                                        data-department="{{ $staff->DEPARTMENT_NAME_TH }}"
                                        data-position="{{ $staff->FINAL_POSITION }}"
                                        {{ $savedSig->staff_id == $staff->STAFF_ID ? 'selected' : '' }}>
                                        {{ $staff->ACADEMIC_ABBR ?: $staff->TITLE_TH }}{{ $staff->NAME_TH }} {{ $staff->SURNAME_TH }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>ตำแหน่ง/สายงาน (ถ้ามี)</label>
                                <input type="text" name="signatures[{{ $index }}][executive_position]" class="form-control executive-position" value="{{ $savedSig->executive_position }}" placeholder="เช่น คณบดีคณะวิทยาศาสตร์">
                            </div>

                            <div class="col-md-4">
                                <label>บทบาทการลงนาม <span class="text-danger">*</span></label>
                                <select name="signatures[{{ $index }}][signature_role_id]" class="form-control select2-role" required>
                                    <option value="">-- เลือกบทบาท --</option>
                                    @foreach($signatureRoles as $role) <option value="{{ $role->id }}" {{ $savedSig->signature_role_id == $role->id ? 'selected' : '' }}>
                                        {{ $role->name_th }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1 text-center mt-4">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-signature" {{ $index == 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="row signature-row align-items-center mb-3 p-3 border rounded bg-light">
                            <div class="col-md-4">
                                <label>1. ชื่อ-นามสกุลผู้ลงนาม <span class="text-danger">*</span></label>
                                <select name="signatures[0][staff_id]" class="form-control select2-staff" required>
                                    <option value="">-- ค้นหาชื่อบุคลากร --</option>
                                    @foreach($staffs as $staff)
                                    <option value="{{ $staff->STAFF_ID }}"
                                        data-department="{{ $staff->DEPARTMENT_NAME_TH }}"
                                        data-position="{{ $staff->FINAL_POSITION }}">
                                        {{ $staff->ACADEMIC_ABBR ?: $staff->TITLE_TH }}{{ $staff->NAME_TH }} {{ $staff->SURNAME_TH }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>ตำแหน่ง/สายงาน (ถ้ามี)</label>
                                <input type="text" name="signatures[0][executive_position]" class="form-control executive-position" value="" placeholder="เช่น คณบดีคณะวิทยาศาสตร์">
                            </div>

                            <div class="col-md-4">
                                <label>บทบาทการลงนาม <span class="text-danger">*</span></label>
                                <select name="signatures[0][signature_role_id]" class="form-control select2-role" required>
                                    <option value="">-- เลือกบทบาท --</option>
                                    @foreach($signatureRoles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1 text-center mt-4">
                                <button type="button" class="btn btn-secondary btn-sm btn-remove-signature" disabled title="ต้องมีอย่างน้อย 1 คน">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr class="border-secondary" style="opacity: 0.2;">
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-primary bg-white shadow-sm" id="btn-add-signature">
                            <i class="fas fa-plus-circle"></i> เพิ่มผู้ลงนาม (สูงสุด 10 คน)
                        </button>
                    </div>
                </div>
            </div>

            <!-- ปุ่มกดยืนยัน -->
            <div class="text-center py-4 border-top mt-3 d-print-none">
                <button type="button" class="btn btn-secondary btn-lg mr-2 shadow-sm" onclick="$('.wizard-nav a[href=\'#tab4\']').tab('show')">
                    <i class="fas fa-arrow-left"></i> ย้อนกลับไปแก้ไข
                </button>

                <button type="submit" class="btn btn-success btn-lg shadow-sm" id="btn-submit-tab5">
                    @if($project->overall_status >= 300 || auth()->user()->hasAnyRole(['admin', 'staff']))
                    <i class="fas fa-save mr-1"></i> ยืนยันและบันทึกภาพรวมโครงการ
                    @else
                    <i class="fas fa-paper-plane mr-1"></i> ยืนยันและยื่นขออนุมัติโครงการ
                    @endif
                </button>
            </div>
        </form>
    </div>

    <!-- ส่วนแท็บอื่นๆ (Locked State) -->
    @foreach([] as $key => $title)
    <div class="tab-pane fade {{ $activeTab == $key ? 'show active' : '' }}" id="{{ $key }}" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-cog fa-spin fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">กำลังเตรียมส่วน {{ $title }}...</h5>
            </div>
        </div>
    </div>
    @endforeach

</div>

<!-- Modals (เหมือนใน Training) -->
<div class="modal fade" id="modalNewTargetGroup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i> สร้างกลุ่มเป้าหมายใหม่</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="formNewTargetGroup">
                    <div class="form-group">
                        <label for="new_target_group_parent_id">ตำแหน่งของกลุ่มนี้ (อยู่ภายใต้กลุ่มใด)</label>
                        <select class="form-control" id="new_target_group_parent_id" name="parent_id" style="width: 100%;">
                            <option value="">-- สร้างเป็นกลุ่มหลัก (Level 1) --</option>
                            @foreach($filteredTargetGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->full_path }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label>ชื่อ (ไทย) <span class="text-danger">*</span></label><input type="text" class="form-control" id="new_target_group_name_th" name="name_th" required></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label>ชื่อ (อังกฤษ) <span class="text-danger">*</span></label><input type="text" class="form-control" id="new_target_group_name_en" name="name_en" required></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button><button type="button" class="btn btn-success" id="btn-save-new-target-group">บันทึก</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNewExternal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus mr-2"></i> สร้างบุคคลภายนอกใหม่</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="formNewExternal">
                    <div class="row">
                        <div class="col-md-3 form-group"><label>คำนำหน้า <span class="text-danger">*</span></label><select id="new_ext_prefix_id" class="form-control">
                                <option value="">- เลือก -</option>@foreach(DB::table('prefixes')->get() as $p)<option value="{{ $p->id }}">{{ $p->name_th }}</option>@endforeach
                            </select></div>
                        <div class="col-md-4 form-group"><label>ชื่อ <span class="text-danger">*</span></label><input type="text" id="new_ext_firstname" class="form-control" required></div>
                        <div class="col-md-5 form-group"><label>นามสกุล <span class="text-danger">*</span></label><input type="text" id="new_ext_lastname" class="form-control" required></div>
                        <div class="col-md-12 form-group"><label>หน่วยงาน/บริษัท <span class="text-danger">*</span></label><input type="text" id="new_ext_department" class="form-control" placeholder="เช่น บริษัท..." required></div>
                        <div class="col-md-6 mb-3">
                            <label>เบอร์โทรศัพท์</label>
                            <input type="text" id="new_ext_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>อีเมล</label>
                            <input type="email" id="new_ext_email" class="form-control">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>รายละเอียดเพิ่มเติม</label>
                            <textarea id="new_ext_description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button><button type="button" class="btn btn-info" id="btn-save-new-external">บันทึกข้อมูล</button></div>
        </div>
    </div>
</div>

@endsection

@section('script')
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/flatpickr/flatpickr.min.css') }}">
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('plugins/flatpickr/th.js') }}"></script>

<script>
    // ส่งผ่านข้อมูลจาก Blade ไปยังไฟล์ JS แยก
    window.OBJECTIVE_START_INDEX = {{ count($savedObjectives ?? []) }};
    
    window.START_SIG_INDEX = {{ \App\Models\MasterData\AcademicProjectSignature::where('academic_project_id', $project->id)->count() }};
    
    window.CUSTOMER_GROUPS = @json($customerGroups);
    
    window.ROUTES = {
        csrfToken: "{{ csrf_token() }}",
        storeTargetGroup: "{{ route('contracts.projects.store-target-group-ajax') }}",
        storeExternal: "{{ route('contracts.projects.store-external-ajax') }}",
        storageUrl: "{{ asset('storage') }}"
    };
</script>

<script src="{{ asset('js/contracts/projects/edit.js?v=' . time()) }}"></script>
@endsection