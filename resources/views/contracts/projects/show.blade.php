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
        <a href="{{ route('contracts.projects.index', ['type_id' => $project->project_type_id]) }}" class="btn btn-secondary shadow-sm mt-2">
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
                        <td width="30%" class="align-middle font-weight-bold text-success">{{ $project->id }}</td>
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
                    <tr>
                        <th class="bg-light text-right align-top pt-3">วัตถุประสงค์โครงการ :</th>
                        <td colspan="3" class="align-middle">
                            @if(isset($savedObjectives) && count($savedObjectives) > 0)
                            <ul class="pl-3 mb-0" style="line-height: 1.6;">
                                @foreach($savedObjectives as $obj)
                                @php
                                // สำหรับสัญญาจ้างใช้ targetGroups -> filteredTargetGroups / customerGroups
                                $groupName = isset($customerGroups) ? ($customerGroups->where('id', $obj->target_group_id)->first()->name_th ?? '-') : '-';
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
                                $name = $ext ? $ext->firstname . ' ' . $ext->lastname : '-';
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

    <!-- 3. รายละเอียดแผนงบประมาณและงวดงาน -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-custom-dark text-white py-2">
            <h6 class="mb-0 mt-1"><i class="fas fa-file-invoice-dollar mr-2"></i> 3. รายละเอียดแผนงบประมาณและงวดงาน</h6>
        </div>
        <div class="card-body p-0">

            <!-- 3.1 แผนรายรับ -->
            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom"><i class="fas fa-arrow-down mr-1 text-muted"></i> 3.1 แผนรายรับ</div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="bg-white text-center text-muted" style="font-size: 0.9em;">
                        <tr>
                            <th width="5%">ที่</th>
                            <th width="25%">หมวดหมู่รายรับ</th>
                            <th width="35%">รายละเอียดกิจกรรม</th>
                            <th width="12%">อัตราจัดเก็บ (บาท)</th>
                            <th width="8%">จำนวน</th>
                            <th width="15%">จำนวนเงินรวม (บาท)</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95em;">
                        @forelse($savedIncomes ?? [] as $index => $inc)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $inc->category_name ?? '-' }}</td>
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
                            <td class="text-right font-weight-bold text-dark align-middle" style="text-decoration: underline double;">{{ number_format($totalIncome ?? 0, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 3.2 แผนรายจ่าย (ค่าดำเนินการ) -->
            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom mt-2 border-top"><i class="fas fa-arrow-up mr-1 text-muted"></i> 3.2 แผนรายจ่าย (ค่าดำเนินการ)</div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="bg-white text-center text-muted" style="font-size: 0.9em;">
                        <tr>
                            <th width="4%">ที่</th>
                            <th width="20%">หมวดหมู่รายจ่าย</th>
                            <th width="25%">รายละเอียดกิจกรรม</th>
                            <th width="12%">ราคาต่อหน่วย (บาท)</th>
                            <th width="8%">ตัวคูณ 1</th>
                            <th width="8%">ตัวคูณ 2</th>
                            <th width="8%">หน่วยนับ</th>
                            <th width="15%">จำนวนเงินรวม (บาท)</th>
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
                            <td class="text-right font-weight-bold text-dark align-middle">{{ number_format($totalExpense ?? 0, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 3.3 แผนรายจ่าย (ค่าตอบแทน) -->
            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom mt-2 border-top"><i class="fas fa-hand-holding-usd mr-1 text-muted"></i> 3.3 แผนรายจ่าย (ค่าตอบแทน)</div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="bg-white text-center text-muted" style="font-size: 0.9em;">
                        <tr>
                            <th width="4%">ที่</th>
                            <th width="20%">หมวดหมู่รายจ่าย</th>
                            <th width="25%">รายละเอียดกิจกรรม</th>
                            <th width="12%">ราคาต่อหน่วย (บาท)</th>
                            <th width="8%">ตัวคูณ 1</th>
                            <th width="8%">ตัวคูณ 2</th>
                            <th width="8%">หน่วยนับ</th>
                            <th width="15%">จำนวนเงินรวม (บาท)</th>
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
                            <td class="text-right font-weight-bold text-dark align-middle">{{ number_format($totalRemuneration ?? 0, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 3.4 สรุปจัดสรรค่าธรรมเนียม -->
            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom mt-2 border-top" style="background-color: #fffdf5 !important;"><i class="fas fa-calculator mr-1 text-muted"></i> 3.4 สรุปจัดสรรค่าธรรมเนียม</div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <tbody style="font-size: 0.95em;">
                        <tr>
                            <th width="30%" class="bg-white text-right align-middle">งบประมาณทั้งโครงการ :</th>
                            <td width="20%" class="text-right align-middle text-success font-weight-bold">{{ number_format($savedBudget->total_budget_summary ?? 0, 2) }}</td>
                            <th width="30%" class="bg-white text-right align-middle">ค่าธรรมเนียมบริการวิชาการ ({{ number_format($savedBudget->service_fee_percent ?? 0, 2) }}%) :</th>
                            <td width="20%" class="text-right align-middle text-danger font-weight-bold">{{ number_format($savedBudget->service_fee_amount ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light text-right align-middle">ค่าธรรมเนียมมหาวิทยาลัย ({{ number_format($savedBudget->alloc_uni_percent ?? 0, 2) }}%) :</th>
                            <td class="text-right align-middle">{{ number_format($savedBudget->alloc_uni_amount ?? 0, 2) }}</td>
                            <th class="bg-light text-right text-info align-middle">กองทุนวิจัย ({{ number_format($savedBudget->fund_research_percent ?? 0, 3) }}%) :</th>
                            <td class="text-right text-info align-middle">{{ number_format($savedBudget->fund_research_amount ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light text-right align-middle">ค่าธรรมเนียมวิทยาเขต ({{ number_format($savedBudget->alloc_campus_percent ?? 0, 2) }}%) :</th>
                            <td class="text-right align-middle">{{ number_format($savedBudget->alloc_campus_amount ?? 0, 2) }}</td>
                            <th class="bg-light text-right text-info align-middle">ส่วนของคณะ ({{ number_format($savedBudget->faculty_percent ?? 0, 3) }}%) :</th>
                            <td class="text-right text-info align-middle">{{ number_format($savedBudget->faculty_amount ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light text-right align-middle">ค่าธรรมเนียมคณะ/หน่วยงาน ({{ number_format($savedBudget->alloc_dept_percent ?? 0, 2) }}%) :</th>
                            <td class="text-right align-middle">{{ number_format($savedBudget->alloc_dept_amount ?? 0, 2) }}</td>
                            <th class="bg-light text-right text-info align-middle">ส่วนของศูนย์ ({{ number_format($savedBudget->center_percent ?? 0, 3) }}%) :</th>
                            <td class="text-right text-info align-middle">{{ number_format($savedBudget->center_amount ?? 0, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 3.5 ข้อมูลงวดงาน -->
            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom mt-2 border-top"><i class="fas fa-list-ol mr-1 text-muted"></i> 3.5 ข้อมูลงวดงาน</div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="bg-white text-center text-muted" style="font-size: 0.9em;">
                        <tr>
                            <th width="10%" class="align-middle">งวดที่</th>
                            <th width="15%" class="align-middle">ระยะเวลา (วัน)</th>
                            <th width="20%" class="align-middle">วันที่เริ่มต้น</th>
                            <th width="20%" class="align-middle">วันที่สิ้นสุด</th>
                            <th width="15%" class="align-middle">หักประกันผลงาน</th>
                            <th width="20%" class="align-middle">ยอดสุทธิ (บาท)</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95em;">
                        @forelse($savedInstallments ?? [] as $inst)
                        <tr>
                            <td class="text-center align-middle font-weight-bold">{{ $inst->installment_no }}</td>
                            <td class="text-center align-middle">{{ $inst->duration_days ?? '-' }}</td>
                            <td class="text-center align-middle">{{ $inst->start_date_show ? \Carbon\Carbon::parse($inst->start_date_show)->addYears(543)->format('d/m/Y') : '-' }}</td>
                            <td class="text-center align-middle">{{ $inst->end_date_show ? \Carbon\Carbon::parse($inst->end_date_show)->addYears(543)->format('d/m/Y') : '-' }}</td>
                            <td class="text-center align-middle">
                                {{ $inst->guarantee_pct ?? '0' }}%
                                <br>
                                <small class="text-muted">({{ number_format($inst->guarantee_amt ?? 0, 2) }} บ.)</small>
                            </td>
                            <td class="text-right align-middle font-weight-bold text-success">{{ number_format($inst->net_amount ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">ยังไม่มีข้อมูลงวดงาน</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- สรุปยอดสุทธิ -->
            <div class="bg-white p-3 border-top text-right" style="font-size: 1em;">
                <span class="text-dark font-weight-bold mr-2">ยอดคงเหลือสุทธิ (รายรับ - (รายจ่าย + ค่าธรรมเนียม)) : </span>
                <strong class="{{ ($balance ?? 0) < 0 ? 'text-danger' : 'text-primary' }}" style="text-decoration: underline double;">
                    {{ number_format($balance ?? 0, 2) }} บาท
                </strong>
            </div>

        </div>
    </div>

    <!-- 4. ประเมินผลและสรุป (ซ่อนไว้ถ้ายังไม่มีข้อมูลประเมิน) -->
    @if(isset($project->overall_status) && $project->overall_status >= 700)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-custom-dark text-white py-2">
            <h6 class="mb-0 mt-1"><i class="fas fa-chart-pie mr-2"></i> 4. สรุปผลลัพธ์โครงการ</h6>
        </div>
        <div class="card-body p-0">
            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-bottom">4.1 ประเมินความพึงพอใจ</div>
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
                            @if(isset($projectEvaluation->satisfaction_percent))<small class="text-muted font-weight-normal">({{ $projectEvaluation->satisfaction_percent }}%)</small>@endif
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
                            @if(isset($projectEvaluation->dissatisfaction_percent))<small class="text-muted font-weight-normal">({{ $projectEvaluation->dissatisfaction_percent }}%)</small>@endif
                        </td>
                        <td class="text-center align-middle">{{ $projectEvaluation->dissatisfaction_range ?? '-' }}</td>
                        <td class="text-center align-middle">
                            {{ isset($projectEvaluation->dissatisfaction_level) && array_key_exists($projectEvaluation->dissatisfaction_level, $evalLevels) ? $evalLevels[$projectEvaluation->dissatisfaction_level] : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0">4.2 อื่นๆ (การบูรณาการ และ ผลกระทบ)</div>
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

            <div class="bg-light text-dark px-3 py-2 font-weight-bold border-top border-bottom mt-0">4.3 ผลสัมฤทธิ์และมูลค่าโครงการ (ถ้ามี)</div>
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

    <!-- 5. รายชื่อผู้ลงนามโครงการ -->
    @if(isset($project->signatures) && $project->signatures->count() > 0)
    <div class="card shadow-sm border-0 mb-4" style="page-break-inside: avoid;">
        <div class="card-header bg-custom-dark text-white py-2 d-print-none">
            <h6 class="mb-0 mt-1"><i class="fas fa-file-signature mr-2"></i> 5. รายชื่อผู้ลงนามโครงการ</h6>
        </div>
        <div class="card-body p-4 bg-white pt-print-0">
            <div class="row mt-3">
                @foreach($project->signatures as $sig)
                @php
                $staffInfo = $staffs->where('STAFF_ID', $sig->staff_id)->first();
                $fullName = $staffInfo ? ($staffInfo->ACADEMIC_ABBR ?: $staffInfo->TITLE_TH) . $staffInfo->NAME_TH . ' ' . $staffInfo->SURNAME_TH : 'ไม่พบข้อมูลบุคลากร';

                $roleInfo = $signatureRoles->where('id', $sig->signature_role_id)->first();
                $roleName = $roleInfo ? $roleInfo->name_th : 'ผู้ลงนาม';

                $colClass = 'col-6';
                if ($loop->count == 1) {
                $colClass .= ' offset-6';
                } elseif ($loop->count % 2 != 0 && $loop->last) {
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

                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Admin Override Status -->
@if(auth()->user()->hasRole('admin') && request('from') != 'approvals')
<div class="card border-danger mb-4 shadow-sm d-print-none">
    <div class="card-header bg-danger text-white py-2">
        <h6 class="mb-0 mt-1"><i class="fas fa-tools mr-2"></i> ส่วนจัดการเฉพาะผู้ดูแลระบบ (Admin Override)</h6>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('contracts.projects.change-status', $project->id) }}" method="POST" id="form-admin-change-status">
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
    .sig-digital {
        display: none !important;
    }

    @media print {
        body.print-digital .sig-digital {
            display: block !important;
        }
    }

    .text-success {
        color: #28a745 !important;
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
    }
</style>
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('js/contracts/projects/show.js?v=' . time()) }}"></script>
@endsection