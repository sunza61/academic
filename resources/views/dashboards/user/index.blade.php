@extends('layouts.main_all')

@section('title', 'My Dashboard')

@section('content')

<style>
    .user-dashboard {
        background: #f8fafc;
        min-height: calc(100vh - 100px);
    }

    /* Hero Section สำหรับ User ใช้โทนสีมรกต/เขียวอมฟ้า ดูสบายตาและเป็นมิตร */
    .hero-user {
        background: linear-gradient(135deg, #059669 0%, #10b981 55%, #34d399 100%);
        color: #fff;
        border-radius: 18px;
        padding: 28px 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.15);
    }

    .hero-user:after {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        right: -50px;
        top: -120px;
        border-radius: 50%;
        background: rgba(255,255,255,.1);
    }

    .hero-user .hero-icon {
        width: 62px;
        height: 62px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.2);
        font-size: 28px;
        margin-right: 15px;
    }

    .hero-title {
        font-size: 1.65rem;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .hero-subtitle {
        color: rgba(255,255,255,.9);
        margin: 0;
        font-size: 0.95rem;
    }

    .hero-badge {
        background: rgba(255,255,255,.2);
        border: 1px solid rgba(255,255,255,.3);
        color: #fff;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: .85rem;
        backdrop-filter: blur(4px);
    }

    /* Card Styles */
    .dashboard-card, .kpi-card {
        background: #fff;
        border: 0;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,.03);
        height: 100%;
        transition: all .25s ease;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,.06);
    }

    .kpi-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .kpi-number {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .kpi-label {
        color: #6b7280;
        font-size: .85rem;
        margin-top: 5px;
    }

    .dashboard-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f3;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dashboard-card-title {
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    /* To-Do List Styles */
    .todo-item {
        display: flex;
        align-items: flex-start;
        padding: 15px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .todo-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .todo-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .todo-content h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .todo-content p {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 8px;
    }

    /* Quick Actions */
    .quick-action-btn {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 12px;
        background: #f8fafc;
        color: #374151;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.2s;
        border: 1px solid #e5e7eb;
        margin-bottom: 12px;
    }

    .quick-action-btn:hover {
        background: #10b981;
        color: #fff;
        border-color: #10b981;
        transform: translateX(4px);
    }

    .quick-action-btn i {
        font-size: 1.2rem;
        margin-right: 12px;
        width: 24px;
        text-align: center;
    }

    .quick-action-btn:hover i {
        color: #fff !important;
    }

    /* Table Styles */
    .table-dashboard thead th {
        border-top: 0;
        background: #f8fafc;
        color: #6b7280;
        font-size: .76rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .table-dashboard tbody td {
        vertical-align: middle;
        font-size: .88rem;
        color: #374151;
    }

    .badge-soft {
        padding: 6px 10px;
        font-weight: 600;
        border-radius: 6px;
    }

    @media (max-width: 767px) {
        .hero-user { padding: 22px; }
        .hero-title { font-size: 1.3rem; }
        .hero-badge { display: inline-block; margin-top: 15px; }
    }
</style>

<div class="user-dashboard py-4">
    <div class="container-fluid">

        {{-- =====================================================
             HERO SECTION (USER)
        ====================================================== --}}
        <div class="hero-user mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="hero-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <div>
                        <div class="hero-title">
                            ยินดีต้อนรับ, {{ auth()->user()->name ?? 'อาจารย์ / นักวิจัย' }}
                        </div>
                        <p class="hero-subtitle">
                            เริ่มต้นสร้างและติดตามโครงการบริการวิชาการของคุณได้ที่นี่
                        </p>
                    </div>
                </div>
                <div class="hero-badge mt-3 mt-md-0 d-flex align-items-center" style="position: relative; z-index: 1000;">
                    <i class="far fa-calendar-alt mr-2"></i>
                    <select name="fiscal_year" id="fiscal_year_user" class="text-white font-weight-bold" style="background-color: transparent; border: none; cursor: pointer; outline: none; appearance: auto;">
                        @foreach($fiscalYears as $year)
                            <option value="{{ $year->id }}" {{ $selectedFiscalYearId == $year->id ? 'selected' : '' }} class="text-dark">
                                ปีงบประมาณ {{ $year->fiscal_year_be }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- =====================================================
             KPI CARDS (สถานะโครงการของฉัน)
        ====================================================== --}}
        <div class="row mb-4">
            {{-- Total My Projects --}}
            <div class="col-xl-3 col-md-6 mb-3">
                <a href="{{ route('all-projects.index', ['fiscal_year_id' => $selectedFiscalYearId]) }}" class="text-decoration-none" style="display: block; height: 100%;">
                    <div class="kpi-card p-3 h-100" style="cursor: pointer;">
                        <div class="d-flex align-items-center">
                            <div class="kpi-icon bg-primary text-white mr-3">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div>
                                <div class="kpi-number text-primary">{{ $totalMyProjects }}</div>
                                <div class="kpi-label">โครงการทั้งหมดของฉัน</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Drafts --}}
            <div class="col-xl-3 col-md-6 mb-3">
                <a href="{{ route('all-projects.index', ['fiscal_year_id' => $selectedFiscalYearId, 'status' => 100]) }}" class="text-decoration-none" style="display: block; height: 100%;">
                    <div class="kpi-card p-3 h-100" style="cursor: pointer;">
                        <div class="d-flex align-items-center">
                            <div class="kpi-icon bg-secondary text-white mr-3">
                                <i class="fas fa-pencil-alt"></i>
                            </div>
                            <div>
                                <div class="kpi-number text-secondary">{{ $countDraft }}</div>
                                <div class="kpi-label">ฉบับร่าง (ยังไม่ส่ง)</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Waiting Approval --}}
            <div class="col-xl-3 col-md-6 mb-3">
                <a href="{{ route('all-projects.index', ['fiscal_year_id' => $selectedFiscalYearId, 'status' => 200]) }}" class="text-decoration-none" style="display: block; height: 100%;">
                    <div class="kpi-card p-3 h-100" style="cursor: pointer;">
                        <div class="d-flex align-items-center">
                            <div class="kpi-icon bg-warning text-white mr-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <div class="kpi-number text-warning">{{ $countWaitingApproval }}</div>
                                <div class="kpi-label">รอคณะพิจารณาอนุมัติ</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Needs Revision --}}
            <div class="col-xl-3 col-md-6 mb-3">
                <a href="{{ route('all-projects.index', ['fiscal_year_id' => $selectedFiscalYearId, 'status' => 110]) }}" class="text-decoration-none" style="display: block; height: 100%;">
                    <div class="kpi-card p-3 h-100 border-left border-danger" style="border-left-width: 4px !important; cursor: pointer;">
                        <div class="d-flex align-items-center">
                            <div class="kpi-icon bg-danger text-white mr-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <div class="kpi-number text-danger">{{ $countNeedsRevision }}</div>
                                <div class="kpi-label text-danger font-weight-bold">ส่งกลับให้แก้ไข !</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- =====================================================
             MIDDLE SECTION: MY TASKS & QUICK ACTIONS
        ====================================================== --}}
        <div class="row mb-4">
            {{-- My Tasks (สิ่งที่ฉันต้องทำ) --}}
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h5 class="dashboard-card-title">
                                <i class="fas fa-tasks text-success mr-2"></i>
                                งานที่ต้องดำเนินการ (My Tasks)
                            </h5>
                            <small class="text-muted">รายการโครงการที่รอการแก้ไข หรือฉบับร่างที่ค้างอยู่</small>
                        </div>
                        @if($totalTasks > 0)
                            <span class="badge badge-danger px-2 py-1">{{ $totalTasks }} งาน</span>
                        @endif
                    </div>
                    <div class="card-body">
                        {{-- โครงการที่ต้องแก้ไข --}}
                        @foreach($tasksNeedsRevision as $task)
                        <div class="todo-item">
                            <div class="todo-icon bg-danger text-white">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="todo-content flex-grow-1">
                                <h6 class="text-danger">โปรดแก้ไขข้อเสนอโครงการ</h6>
                                <p><strong>โครงการ:</strong> {{ $task->name_th }}<br>
                                   <span class="text-muted"><i class="fas fa-comment-dots mr-1"></i> หมายเหตุ: {{ $task->latestLog->comment ?? 'ไม่พบหมายเหตุ' }}</span>
                                </p>
                                @php
                                    $routeEdit = ($task->project_type_id == 2) ? 'trainings.projects.edit' : 'contracts.projects.edit';
                                @endphp
                                <a href="{{ route($routeEdit, $task->id) }}" class="btn btn-sm btn-danger px-3">
                                    <i class="fas fa-edit mr-1"></i> เข้าไปแก้ไข
                                </a>
                            </div>
                        </div>
                        @endforeach

                        {{-- โครงการฉบับร่าง --}}
                        @foreach($recentDrafts as $draft)
                        <div class="todo-item">
                            <div class="todo-icon bg-secondary text-white">
                                <i class="fas fa-save"></i>
                            </div>
                            <div class="todo-content flex-grow-1">
                                <h6>มีแบบร่างโครงการที่ยังสร้างไม่เสร็จ</h6>
                                <p><strong>โครงการ:</strong> {{ $draft->name_th }}<br>
                                   <span class="text-muted">บันทึกล่าสุด: {{ $draft->updated_at->diffForHumans() }}</span>
                                </p>
                                @php
                                    $routeEdit = ($draft->project_type_id == 2) ? 'trainings.projects.edit' : 'contracts.projects.edit';
                                @endphp
                                <a href="{{ route($routeEdit, $draft->id) }}" class="btn btn-sm btn-outline-secondary px-3">
                                    <i class="fas fa-arrow-right mr-1"></i> ทำต่อให้เสร็จ
                                </a>
                            </div>
                        </div>
                        @endforeach

                        @if($totalTasks == 0)
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-check-circle fa-2x mb-2 text-success"></i><br>
                                ไม่มีงานที่ต้องดำเนินการในขณะนี้
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="col-lg-4">
                <div class="dashboard-card border-0 bg-transparent shadow-none">
                    <h5 class="font-weight-bold text-dark mb-3 px-1">
                        <i class="fas fa-bolt text-warning mr-2"></i> เมนูลัด (Quick Actions)
                    </h5>
                    
                    {{-- สร้างโครงการใหม่ โดดเด่นสุด --}}
                    <a href="{{ route('projects.select-type') }}" class="quick-action-btn" style="background: #10b981; color: white; border-color: #059669; box-shadow: 0 4px 10px rgba(16,185,129,0.2);">
                        <i class="fas fa-plus-circle text-white"></i>
                        <span class="flex-grow-1">สร้างโครงการใหม่</span>
                        <i class="fas fa-chevron-right small"></i>
                    </a>

                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-file-download text-primary"></i>
                        <span class="flex-grow-1">ดาวน์โหลดแบบฟอร์ม (Templates)</span>
                    </a>

                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-book-reader text-info"></i>
                        <span class="flex-grow-1">คู่มือการใช้งานระบบ</span>
                    </a>

                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-headset text-secondary"></i>
                        <span class="flex-grow-1">ติดต่อเจ้าหน้าที่ / สอบถาม</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- =====================================================
             RECENT PROJECTS TABLE
        ====================================================== --}}
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title">
                    <i class="fas fa-list text-primary mr-2"></i>
                    ประวัติโครงการของฉัน
                </h5>
                <a href="{{ route('all-projects.index', ['fiscal_year_id' => $selectedFiscalYearId]) }}" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-dashboard mb-0">
                    <thead>
                        <tr>
                            <th class="pl-4">รหัสอ้างอิง</th>
                            <th>ชื่อโครงการ</th>
                            <th>ประเภท</th>
                            <th>วันที่ยื่นเสนอ</th>
                            <th class="text-center">สถานะปัจจุบัน</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentProjects as $project)
                        <tr>
                            <td class="pl-4 font-weight-bold text-primary">{{ $project->project_code ?? '-' }}</td>
                            <td>
                                <a href="#" class="text-dark font-weight-bold">{{ $project->name_th }}</a>
                            </td>
                            <td><span class="badge badge-light text-muted border">{{ $project->projectType->name_th ?? 'ไม่ระบุ' }}</span></td>
                            <td>{{ $project->created_at ? $project->created_at->addYears(543)->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">
                                @if($project->overall_status == 100)
                                    <span class="badge badge-secondary px-2 py-1"><i class="fas fa-edit"></i> เตรียมการ / ฉบับร่าง</span>
                                @elseif($project->overall_status == 110)
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-hourglass-half"></i> ส่งกลับให้แก้ไข</span>
                                @elseif($project->overall_status == 200)
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-hourglass-half"></i> รอพิจารณาอนุมัติ</span>
                                @elseif($project->overall_status == 300)
                                    <span class="badge badge-info px-2 py-1"><i class="fas fa-check"></i> อนุมัติแล้ว</span>
                                @elseif($project->overall_status == 400)
                                    <span class="badge badge-primary px-2 py-1"><i class="fas fa-bullhorn"></i> เปิดรับสมัคร</span>
                                @elseif($project->overall_status == 500)
                                    <span class="badge badge-dark px-2 py-1"><i class="fas fa-door-closed"></i> ปิดรับสมัคร</span>
                                @elseif($project->overall_status == 600)
                                    <span class="badge" style="background-color: #fd7e14; color: white;"><i class="fas fa-spinner fa-spin"></i> อยู่ระหว่างดำเนินการ</span>
                                @elseif($project->overall_status == 700)
                                    <span class="badge badge-info px-2 py-1"><i class="fas fa-clipboard-list"></i> รอประเมินผล</span>
                                @elseif($project->overall_status == 800)
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-flag-checkered"></i> เสร็จสิ้นโครงการ</span>
                                @elseif($project->overall_status == 900)
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> ยกเลิกโครงการ</span>
                                @else
                                    <span class="badge badge-light px-2 py-1">ไม่ทราบสถานะ</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $routeShow = ($project->project_type_id == 2) ? 'trainings.projects.show' : 'contracts.projects.show';
                                    $routeEdit = ($project->project_type_id == 2) ? 'trainings.projects.edit' : 'contracts.projects.edit';
                                @endphp
                                @if($project->overall_status == 100 || $project->overall_status == 110)
                                    <a href="{{ route($routeEdit, $project->id) }}" class="btn btn-sm btn-light text-primary" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @else
                                    <a href="{{ route($routeShow, $project->id) }}" class="btn btn-sm btn-light text-info" title="ดูรายละเอียด">
                                        <i class="fas fa-search"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/dashboards/user/index.js?v=' . time()) }}"></script>
@endsection