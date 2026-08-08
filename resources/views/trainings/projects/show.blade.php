@extends('layouts.main_all')

@section('content')
<div class="row mb-3 mt-2 d-print-none align-items-center">
    <div class="col-md-7">
        <h3 class="m-0 font-weight-bold">
            <i class="fas fa-search text-info mr-2"></i> รายละเอียดโครงการ: <span class="text-primary">{{ $project->name_th ?? '-' }}</span>
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

    <div class="col-md-5 text-right">
        @if(request('from') == 'approvals')
            <a href="{{ route('admin.approvals.index') }}" class="btn btn-secondary shadow-sm mt-2">
                <i class="fas fa-arrow-left"></i> กลับไปหน้าพิจารณาอนุมัติ
            </a>
        @else
            <a href="{{ route('trainings.projects.index', ['type_id' => $project->project_type_id]) }}" class="btn btn-secondary shadow-sm mt-2">
                <i class="fas fa-arrow-left"></i> กลับไปหน้าตาราง
            </a>
        @endif

        @if($project->overall_status >= 300)
        <button type="button" class="btn btn-info shadow-sm mt-2 ml-1" onclick="printNormal()">
            <i class="fas fa-pen"></i> พิมพ์เพื่อเซ็นสด
        </button>
        <button type="button" class="btn btn-primary shadow-sm mt-2 ml-1" onclick="printDigital()">
            <i class="fas fa-fingerprint"></i> พิมพ์แบบ Digital
        </button>
        @endif
    </div>
</div>

<div id="print-area">

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-custom-dark text-white py-2">
            <h6 class="mb-0 mt-1"><i class="fas fa-file-alt mr-2"></i> 1. ข้อมูลพื้นฐานโครงการ</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <th width="20%" class="bg-light text-right align-middle">รหัสโครงการ :</th>
                        <td width="30%" class="align-middle font-weight-bold text-success">{{ $project->id }}</td>
                        <th width="20%" class="bg-light text-right align-middle">ปีงบประมาณ :</th>
                        <td width="30%" class="align-middle font-weight-bold">{{ $fiscalYears->where('id', $project->fiscal_year_id)->first()->fiscal_year_be ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-middle">ชื่อโครงการ :</th>
                        <td colspan="3" class="align-middle font-weight-bold text-primary" style="font-size: 1.1em;">{{ $project->name_th ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-middle">ระยะเวลาโครงการ :</th>
                        <td colspan="3" class="align-middle">
                            {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->addYears(543)->format('d/m/Y') : '-' }}
                            <strong class="mx-2">ถึง</strong>
                            {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->addYears(543)->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-top pt-3">หน่วยงานผู้รับผิดชอบ :</th>
                        <td colspan="3" class="align-middle">
                            @if(!empty($selectedDepartments))
                            @foreach($departments->whereIn('DEPARTMENT_ID', $selectedDepartments) as $dept)
                            <span class="badge badge-info px-2 py-1 mr-1 mb-1" style="font-size: 0.9em; font-weight: normal;">{{ $dept->DEPARTMENT_NAME_TH }}</span>
                            @endforeach
                            @else - @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-top pt-3">หลักสูตร :</th>
                        <td colspan="3" class="align-middle">
                            @if(!empty($selectedCourses))
                            @foreach($divisions->whereIn('DIVISION_ID', $selectedCourses) as $div)
                            <span class="badge badge-secondary px-2 py-1 mr-1 mb-1" style="font-size: 0.9em; font-weight: normal;">{{ $div->DIVISION_NAME }}</span>
                            @endforeach
                            @else - @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-middle">ศูนย์ (Center) :</th>
                        <td class="align-middle">{{ $centers->where('id', $project->center_id)->first()->name_th ?? '-' }}</td>
                        <th class="bg-light text-right align-middle">ระดับโครงการ :</th>
                        <td class="align-middle">
                            @if(($project->region_type ?? '') == '1') ระดับชาติ
                            @elseif(($project->region_type ?? '') == '2') ระดับนานาชาติ
                            @else - @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-top pt-3">วัตถุประสงค์ :</th>
                        <td colspan="3" class="align-middle">
                            @if(!empty($selectedObjectives))
                            <ul class="pl-3 mb-0" style="line-height: 1.6;">
                                @foreach($targetGroups->whereIn('id', $selectedObjectives) as $tg)
                                <li>{{ $tg->name_th }}</li>
                                @endforeach
                            </ul>
                            @else - @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-top pt-3">รายละเอียดโดยย่อ :</th>
                        <td colspan="3" class="align-middle" style="line-height: 1.6;">{!! $project->brief_description ? nl2br(e($project->brief_description)) : '-' !!}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-top pt-3">หลักการและเหตุผล :</th>
                        <td colspan="3" class="align-middle" style="line-height: 1.6;">{!! $project->rationale ? nl2br(e($project->rationale)) : '-' !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-custom-dark text-white py-2">
            <h6 class="mb-0 mt-1"><i class="fas fa-chalkboard-teacher mr-2"></i> 2. ข้อมูลการจัดกิจกรรม & คณะทำงาน</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <th width="20%" class="bg-light text-right align-middle">เลขที่หนังสืออนุมัติ :</th>
                        <td width="30%" class="align-middle text-dark font-weight-bold">{{ $trainingProject->document_number ?? '-' }}</td>
                        <th width="20%" class="bg-light text-right align-middle">สถานประกอบการ :</th>
                        <td width="30%" class="align-middle">{{ $trainingProject->has_collaboration ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-middle">ประเภทโครงการ :</th>
                        <td class="align-middle">
                            @if(($trainingProject->project_types ?? '') == '0') มีรายได้
                            @elseif(($trainingProject->project_types ?? '') == '1') ให้เปล่า
                            @else - @endif
                        </td>
                        <th class="bg-light text-right align-middle">ลักษณะหลักสูตร :</th>
                        <td class="align-middle">
                            @if(($trainingProject->course_type ?? '') == '1') Upskill
                            @elseif(($trainingProject->course_type ?? '') == '2') Reskill
                            @elseif(($trainingProject->course_type ?? '') == '3') New skill
                            @else - @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-top pt-3">ชื่อหลักสูตรในโครงการ :</th>
                        <td colspan="3" class="align-middle">
                            @if(isset($savedTrainingCourses) && $savedTrainingCourses->count() > 0)
                            <ul class="pl-3 mb-0" style="line-height: 1.6;">
                                @foreach($savedTrainingCourses as $course)
                                <li class="font-weight-bold text-primary">{{ $course->course_name }}</li>
                                @endforeach
                            </ul>
                            @else - @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-middle">วันรับสมัคร :</th>
                        <td colspan="3" class="align-middle">
                            {{ ($trainingProject->start_regis_date ?? false) ? \Carbon\Carbon::parse($trainingProject->start_regis_date)->addYears(543)->format('d/m/Y') : '-' }}
                            <strong class="mx-2">ถึง</strong>
                            {{ ($trainingProject->end_regis_date ?? false) ? \Carbon\Carbon::parse($trainingProject->end_regis_date)->addYears(543)->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-top pt-3">เอกสารอ้างอิง :</th>
                        <td colspan="3" class="align-middle" style="line-height: 1.8;">
                            @if(!empty($trainingProject->approval_file))
                            <div><i class="fas fa-file-pdf text-danger mr-1"></i> ไฟล์เอกสารอนุมัติ: <a href="{{ asset('storage/'.$trainingProject->approval_file) }}" target="_blank" class="text-primary"><u>เปิดดูไฟล์</u></a></div>
                            @endif
                            @if(!empty($trainingProject->approval_link))
                            <div><i class="fas fa-link text-info mr-1"></i> ลิงก์เอกสารอ้างอิง: <a href="{{ $trainingProject->approval_link }}" target="_blank" class="text-primary"><u>{{ $trainingProject->approval_link }}</u></a></div>
                            @endif
                            @if(empty($trainingProject->approval_file) && empty($trainingProject->approval_link))
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-top pt-3">เกี่ยวข้องกับ SDGs :</th>
                        <td colspan="3" class="align-middle">
                            @if(!empty($selectedSdgs))
                            @foreach($sdgs->whereIn('id', $selectedSdgs) as $sdg)
                            <span class="badge badge-success px-2 py-1 mr-1 mb-1" style="font-size: 0.9em; font-weight: normal;"><i class="fas fa-leaf mr-1"></i> SDGs {{ $sdg->id }}: {{ $sdg->name_th }}</span>
                            @endforeach
                            @else - @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light text-right align-top pt-3">กลุ่มเป้าหมาย :<br><small class="text-success font-weight-normal">(รวม {{ isset($savedTargetGroups) ? number_format($savedTargetGroups->sum('total')) : '0' }} คน)</small></th>
                        <td colspan="3" class="align-middle">
                            @if(isset($savedTargetGroups) && $savedTargetGroups->count() > 0)
                            <ul class="pl-3 mb-0" style="line-height: 1.6;">
                                @foreach($savedTargetGroups as $stg)
                                @php
                                $tg = $filteredTargetGroups->where('id', $stg->target_group_id)->first();
                                $nat = $nationalities->where('id', $stg->nationality_id)->first();
                                @endphp
                                <li>{{ $tg ? $tg->full_path : '-' }} <span class="text-muted">(เชื้อชาติ: {{ $nat ? $nat->name_th : '-' }})</span> <i class="fas fa-arrow-right mx-1 text-secondary" style="font-size: 0.8em;"></i> <strong class="text-success">{{ number_format($stg->total) }}</strong> คน</li>
                                @endforeach
                            </ul>
                            @else - @endif
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
                                $prefixName = isset($ext->prefix) ? $ext->prefix->name_th : '';
                                $name = $prefixName . $ext->firstname . ' ' . $ext->lastname;
                                $typeBadge = '<span class="badge badge-secondary px-2" style="font-size:0.8em; font-weight:normal;">บุคคลภายนอก</span>';
                                }
                                @endphp
                                <li class="mb-1"><strong class="text-dark">{{ $posName }} :</strong> <span class="text-info">{{ $name }}</span> {!! $typeBadge !!}
                                    @if($comm->remuneration_total > 0) <small class="text-danger ml-2"><i class="fas fa-coins"></i> ค่าตอบแทน: {{ number_format($comm->remuneration_total, 2) }} บาท</small> @endif
                                </li>
                                @endforeach
                            </ul>
                            @else - @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-custom-dark text-white py-2">
            <h6 class="mb-0 mt-1"><i class="fas fa-calendar-alt mr-2"></i> 3. กำหนดการจัดกิจกรรม ({{ isset($savedSchedules) ? $savedSchedules->count() : '0' }} กิจกรรม)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="5%" class="align-middle">ที่</th>
                            <th width="15%" class="align-middle">วัน-เวลา</th>
                            <th width="30%" class="align-middle">รายละเอียดกิจกรรม & เอกสาร</th>
                            <th width="25%" class="align-middle">วิทยากร / ผู้เกี่ยวข้อง</th>
                            <th width="25%" class="align-middle">สถานที่จัดกิจกรรม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($savedSchedules ?? [] as $index => $sch)
                        @php
                        $schMembers = \App\Models\Training\TrainingMember::where('training_schedule_id', $sch->id)->get();
                        $schLocations = \App\Models\Training\TrainingSchedulesLocation::where('training_schedule_id', $sch->id)->get();
                        $schDocs = \App\Models\Training\TrainingScheduleDocument::where('training_schedule_id', $sch->id)->get();
                        @endphp
                        <tr>
                            <td class="text-center align-top pt-3 font-weight-bold text-muted">{{ $index + 1 }}</td>
                            <td class="text-center align-top pt-3">
                                <div class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($sch->schedule_date)->addYears(543)->format('d/m/Y') }}</div>
                                <div class="text-muted small mt-1"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }} น.</div>
                            </td>
                            <td class="align-top pt-3">
                                <div style="line-height: 1.6;" class="text-dark font-weight-bold">{!! nl2br(e($sch->topic)) !!}</div>
                                @if($schDocs->count() > 0)
                                <hr class="my-2 border-secondary" style="opacity: 0.1;">
                                <div class="small"><strong class="text-muted"><i class="fas fa-paperclip"></i> เอกสารแนบ:</strong>
                                    @foreach($schDocs as $doc)
                                    <div class="mt-1 ml-3"><i class="fas fa-file-alt text-primary mr-1"></i> <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="text-primary"><u>{{ $doc->document_name }}</u></a></div>
                                    @endforeach
                                </div>
                                @endif
                            </td>
                            <td class="align-top pt-3">
                                @if($schMembers->count() > 0)
                                <ul class="pl-3 mb-0 small" style="line-height: 1.6;">
                                    @foreach($schMembers as $mem)
                                    @php
                                    $posName = $trainingPositions->where('id', $mem->training_position_id)->first()->name_th ?? '-';
                                    if($mem->member_type == '1') {
                                    $staff = $staffs->where('STAFF_ID', $mem->personnel_id)->first();
                                    $name = $staff ? $staff->TITLE_TH . $staff->NAME_TH . ' ' . $staff->SURNAME_TH : '-';
                                    $badge = '<span class="badge badge-primary px-1 ml-1" style="font-size:0.7em; font-weight:normal;">คนใน</span>';
                                    } else {
                                    $ext = $externals->where('id', $mem->external_id)->first();
                                    $name = $ext ? $ext->prefix->name_th . $ext->firstname . ' ' . $ext->lastname : '-';
                                    $badge = '<span class="badge badge-secondary px-1 ml-1" style="font-size:0.7em; font-weight:normal;">คนนอก</span>';
                                    }
                                    @endphp
                                    <li class="mb-1"><strong class="text-dark">{{ $posName }}:</strong> <span class="text-info">{{ $name }}</span> {!! $badge !!}</li>
                                    @endforeach
                                </ul>
                                @else <span class="text-muted">-</span> @endif
                            </td>
                            <td class="align-top pt-3">
                                @if($schLocations->count() > 0)
                                <ul class="pl-4 mb-0 small" style="line-height: 1.6;">
                                    @foreach($schLocations as $loc)
                                    @php
                                    $prov = $provinces->where('ProvinceNo', $loc->province_id)->first();
                                    $provName = $prov ? ' <span class="text-muted">(จ.'.$prov->ProvinceNameThai.')</span>' : '';
                                    @endphp
                                    <li class="mb-1"><i class="fas fa-map-marker-alt text-danger mr-1" style="margin-left: -15px;"></i> <span class="font-weight-bold">{{ $loc->location_name }}</span> {!! $provName !!}</li>
                                    @endforeach
                                </ul>
                                @else <span class="text-muted">-</span> @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4"><i class="fas fa-calendar-times fa-2x mb-2 text-light"></i><br>ยังไม่มีข้อมูลกำหนดการจัดกิจกรรม</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                    <h6 class="mb-0 mt-1"><i class="fas fa-file-invoice-dollar mr-2"></i> 4. รายละเอียดแผนงบประมาณ</h6>
                </div>
                <div class="card-body p-0">

                    <!-- 3.1 แผนรายรับ (โค้ดเดิม) -->
                    <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom">
                        <i class="fas fa-arrow-down mr-1 text-muted"></i> 4.1 แผนรายรับ
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
                        <i class="fas fa-arrow-up mr-1 text-muted"></i> 4.2 แผนรายจ่าย (ค่าดำเนินการ)
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
                        <i class="fas fa-hand-holding-usd mr-1 text-muted"></i> 4.3 แผนรายจ่าย (ค่าตอบแทน)
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
                        <i class="fas fa-calculator mr-1 text-muted"></i> 4.4 สรุปจัดสรรค่าธรรมเนียม
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

    @if(isset($project->overall_status) && $project->overall_status >= 700)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-custom-dark text-white py-2">
            <h6 class="mb-0 mt-1"><i class="fas fa-chart-pie mr-2"></i> 5. สรุปผลลัพธ์โครงการ</h6>
        </div>
        <div class="card-body p-0">
            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom">5.1 ประเมินความพึงพอใจ</div>
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
                    @php $evalLevels = ['5'=>'มากที่สุด','4'=>'มาก','3'=>'ปานกลาง','2'=>'น้อย','1'=>'น้อยที่สุด']; @endphp
                    <tr>
                        <td class="font-weight-bold text-success align-middle pl-3"><i class="fas fa-thumbs-up mr-1"></i> ความพึงพอใจ</td>
                        <td class="text-center align-middle font-weight-bold">{{ $projectEvaluation->satisfaction_score ?? '-' }} @if(isset($projectEvaluation->satisfaction_percent))<small class="text-muted font-weight-normal">({{ $projectEvaluation->satisfaction_percent }}%)</small>@endif</td>
                        <td class="text-center align-middle">{{ $projectEvaluation->satisfaction_range ?? '-' }}</td>
                        <td class="text-center align-middle">{{ isset($projectEvaluation->satisfaction_level) && array_key_exists($projectEvaluation->satisfaction_level, $evalLevels) ? $evalLevels[$projectEvaluation->satisfaction_level] : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-danger align-middle pl-3"><i class="fas fa-thumbs-down mr-1"></i> ความไม่พึงพอใจ</td>
                        <td class="text-center align-middle font-weight-bold">{{ $projectEvaluation->dissatisfaction_score ?? '-' }} @if(isset($projectEvaluation->dissatisfaction_percent))<small class="text-muted font-weight-normal">({{ $projectEvaluation->dissatisfaction_percent }}%)</small>@endif</td>
                        <td class="text-center align-middle">{{ $projectEvaluation->dissatisfaction_range ?? '-' }}</td>
                        <td class="text-center align-middle">{{ isset($projectEvaluation->dissatisfaction_level) && array_key_exists($projectEvaluation->dissatisfaction_level, $evalLevels) ? $evalLevels[$projectEvaluation->dissatisfaction_level] : '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0">5.2 อื่นๆ (การบูรณาการ และ ผลกระทบ)</div>
            <table class="table table-bordered table-sm mb-0">
                <tbody>
                    <tr>
                        <th width="35%" class="bg-white text-right align-top pt-2">การนำผลประเมินไปปรับปรุง :</th>
                        <td width="65%" class="align-middle" style="line-height: 1.6;">{!! !empty($projectEvaluation->improvement_apply) ? nl2br(e($projectEvaluation->improvement_apply)) : '<span class="text-muted">-</span>' !!}</td>
                    </tr>
                    <tr>
                        <th class="bg-white text-right align-top pt-2">ผลกระทบของกิจกรรม :</th>
                        <td class="align-middle" style="line-height: 1.6;">{!! !empty($projectEvaluation->impact) ? nl2br(e($projectEvaluation->impact)) : '<span class="text-muted">-</span>' !!}</td>
                    </tr>
                    <tr>
                        <th class="bg-white text-right align-top pt-2">การบูรณาการ :</th>
                        <td class="align-middle" style="line-height: 1.6;">{!! !empty($projectEvaluation->integration) ? nl2br(e($projectEvaluation->integration)) : '<span class="text-muted">-</span>' !!}</td>
                    </tr>
                    <tr>
                        <th class="bg-white text-right align-top pt-2">การประเมินการบูรณาการ / การนำผลไปปรับปรุง :</th>
                        <td class="align-middle" style="line-height: 1.6;">{!! !empty($projectEvaluation->integration_eval) ? nl2br(e($projectEvaluation->integration_eval)) : '<span class="text-muted">-</span>' !!}</td>
                    </tr>
                </tbody>
            </table>

            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0">5.3 ผลสัมฤทธิ์และมูลค่าโครงการ (ถ้ามี)</div>
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
                        <td colspan="2" class="align-middle bg-white" style="line-height: 1.6;">{!! !empty($projectEvaluation->project_achievement) ? nl2br(e($projectEvaluation->project_achievement)) : '<span class="text-muted">-</span>' !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
    @if(isset($project->signatures) && $project->signatures->count() > 0)
    <div class="card shadow-sm border-0 mb-4" style="page-break-inside: avoid;">
        <div class="card-header bg-custom-dark text-white py-2 d-print-none">
            <h6 class="mb-0 mt-1"><i class="fas fa-file-signature mr-2"></i> 6. รายชื่อผู้ลงนามโครงการ</h6>
        </div>
        <div class="card-body p-4 bg-white pt-print-0">
            <div class="row mt-3">
                @foreach($project->signatures as $sig)
                @php
                // 1. ดึงชื่อพนักงานจาก V_STAFF_NEWWEB
                $staffInfo = $staffs->where('STAFF_ID', $sig->staff_id)->first();
                $fullName = $staffInfo ? ($staffInfo->ACADEMIC_ABBR ?: $staffInfo->TITLE_TH) . $staffInfo->NAME_TH . ' ' . $staffInfo->SURNAME_TH : 'ไม่พบข้อมูลบุคลากร';

                // 2. ดึงชื่อบทบาท
                $roleInfo = $signatureRoles->where('id', $sig->signature_role_id)->first();
                $roleName = $roleInfo ? $roleInfo->name_th : 'ผู้ลงนาม';

                // 3. 🌟 โลจิกคำนวณตำแหน่ง (ซ้าย-ขวา-กลาง) 🌟
                // แชทใช้ col-6 (แทน col-md-6) เพื่อบังคับให้ตอนปริ้นกระดาษ A4 มันแบ่งครึ่งซ้ายขวาเสมอ
                $colClass = 'col-6';
                if ($loop->count == 1) {
                // กรณีมีแค่ 1 คน -> ผลักไปชิดขวา
                $colClass .= ' offset-6';
                } elseif ($loop->count % 2 != 0 && $loop->last) {
                // กรณีมีจำนวนคนเป็นคี่ (3, 5, 7) และ "เป็นคนสุดท้าย" -> ผลักไปอยู่ตรงกลาง
                $colClass .= ' offset-3';
                }
                @endphp
                <div class="{{ $colClass }} mb-5 text-center" style="page-break-inside: avoid;">
                    
                    <div class="position-relative mb-2">
                        <div class="sig-digital text-danger" style="position: absolute; bottom: 12px; width: 100%; padding-left: 60px; font-weight: bold;">
                            #sg{{ sprintf('%02d', $loop->iteration) }}#
                        </div>
                        
                        <p class="m-0 text-dark" style="font-size: 1.1em;">
                            (ลงชื่อ).......................................................
                        </p>
                    </div>
                    
                    <p class="mb-1 font-weight-bold text-dark" style="font-size: 1.1em;">
                        ( {{ $fullName }} )
                    </p>

                    @if(!$loop->first)
                    <p class="mb-0 text-dark">
                        {{ $sig->executive_position ?? 'ตำแหน่ง.......................................................' }}
                    </p>
                    @endif

                    <p class="mt-2 text-muted small" style="font-weight: 500;">
                        {{ $roleName }}
                    </p>
                    
                </div> @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

@if(auth()->user()->hasRole('admin') && request('from') != 'approvals')
<div class="card border-danger mb-4 shadow-sm d-print-none">
    <div class="card-header bg-danger text-white py-2">
        <h6 class="mb-0 mt-1"><i class="fas fa-tools mr-2"></i> ส่วนจัดการเฉพาะผู้ดูแลระบบ (Admin Override)</h6>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('trainings.projects.change-status', $project->id) }}" method="POST" id="form-admin-change-status">
            @csrf
            @method('PATCH')
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="mb-0 font-weight-bold">ปรับเปลี่ยนสถานะโครงการ:</label>
                </div>
                <div class="col-md-6">
                    <select name="new_status" id="admin_new_status" class="form-control border-danger" required>
                        <option value="100" {{ $project->overall_status == 100 ? 'selected' : '' }}>100 - เตรียมการ / ฉบับร่าง</option>
                        <option value="110" {{ $project->overall_status == 110 ? 'selected' : '' }}>110 - ตีกลับ (ส่งกลับไปแก้ไข)</option>
                        <option value="200" {{ $project->overall_status == 200 ? 'selected' : '' }}>200 - เสนอขออนุมัติ</option>
                        <option value="300" {{ $project->overall_status == 300 ? 'selected' : '' }}>300 - อนุมัติแล้ว / รอเปิดรับสมัคร</option>
                        <option value="400" {{ $project->overall_status == 400 ? 'selected' : '' }}>400 - เปิดรับสมัคร</option>
                        <option value="500" {{ $project->overall_status == 500 ? 'selected' : '' }}>500 - ปิดรับสมัคร / เตรียมจัดงาน</option>
                        <option value="600" {{ $project->overall_status == 600 ? 'selected' : '' }}>600 - อยู่ระหว่างดำเนินการ</option>
                        <option value="700" {{ $project->overall_status == 700 ? 'selected' : '' }}>700 - รอประเมินผลและรายงาน</option>
                        <option value="800" {{ $project->overall_status == 800 ? 'selected' : '' }}>800 - เสร็จสิ้นโครงการ</option>
                        <option value="900" {{ $project->overall_status == 900 ? 'selected' : '' }}>900 - ยกเลิกโครงการ</option>
                    </select>
                </div>
                <div class="col-md-3 text-right">
                    <button type="button" class="btn btn-danger font-weight-bold w-100 btn-force-change-status">
                        <i class="fas fa-sync-alt mr-1"></i> บังคับเปลี่ยนสถานะ
                    </button>
                </div>
            </div>
            <small class="text-danger mt-2 d-block">
                * หมายเหตุ: การเปลี่ยนสถานะด้วยวิธีนี้ จะข้ามขั้นตอนการตรวจสอบปกติของระบบ และจะถูกบันทึกประวัติการทำรายการ กรุณาใช้งานด้วยความระมัดระวัง
            </small>
        </form>
    </div>
</div>
@endif

<style>
    .sig-digital { display: none !important; }

    @media print {
        body.print-digital .sig-digital { display: block !important; }
    }

    
</style>
<style>
    .text-success {
        color: #dc3545 !important;
    }

    .badge-success {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    @media print {
        html,
        body {
            font-size: 14pt !important;
        }

        body * {
            visibility: hidden;
        }

        #print-area,
        #print-area * {
            visibility: visible;
        }

        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .d-print-none,
        .main-header,
        .main-sidebar,
        footer {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
            margin-bottom: 20px !important;
        }

        .card-header {
            background-color: #f8f9fa !important;
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
            color: #000 !important;
        }

        a {
            text-decoration: none !important;
            color: #000 !important;
        }

        .d-print-block {
            display: block !important;
            page-break-inside: avoid;
        }
    }
</style>


@endsection
@section('script')
<script src="{{ asset('js/trainings/projects/show.js?v=' . time()) }}"></script>
@endsection