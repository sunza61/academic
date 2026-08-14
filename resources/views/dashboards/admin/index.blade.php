@extends('layouts.main_all')

@section('content')

<style>
    .admin-dashboard {
        background: #f4f6f9;
        min-height: calc(100vh - 100px);
    }

    .hero-admin {
        background: linear-gradient(135deg, #1f2937 0%, #111827 55%, #0f172a 100%);
        color: #fff;
        border-radius: 18px;
        padding: 28px 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
    }

    .hero-admin:after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -80px;
        top: -100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .05);
    }

    .hero-admin .hero-icon {
        width: 62px;
        height: 62px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .10);
        font-size: 28px;
        margin-right: 15px;
    }

    .hero-title {
        font-size: 1.65rem;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .hero-subtitle {
        color: rgba(255, 255, 255, .70);
        margin: 0;
    }

    .hero-badge {
        background: rgba(255, 255, 255, .10);
        border: 1px solid rgba(255, 255, 255, .12);
        color: #fff;
        padding: 9px 15px;
        border-radius: 50px;
        font-size: .85rem;
    }

    .kpi-card {
        border: 0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(0, 0, 0, .06);
        height: 100%;
        transition: all .25s ease;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, .10);
    }

    .kpi-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .kpi-number {
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .kpi-label {
        color: #6b7280;
        font-size: .85rem;
        margin-top: 6px;
    }

    .dashboard-card {
        background: #fff;
        border: 0;
        border-radius: 16px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, .06);
        overflow: hidden;
        height: 100%;
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

    .dashboard-card-title i {
        margin-right: 8px;
    }

    .workflow-item {
        margin-bottom: 17px;
    }

    .workflow-item:last-child {
        margin-bottom: 0;
    }

    .workflow-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: .86rem;
    }

    .workflow-label strong {
        color: #374151;
    }

    .workflow-bar {
        height: 9px;
        border-radius: 20px;
        background: #edf0f3;
        overflow: hidden;
    }

    .workflow-progress {
        height: 100%;
        border-radius: 20px;
    }

    .progress-approved {
        width: 78%;
        background: #22c55e;
    }

    .progress-review {
        width: 43%;
        background: #f59e0b;
    }

    .progress-running {
        width: 61%;
        background: #3b82f6;
    }

    .progress-delivery {
        width: 29%;
        background: #8b5cf6;
    }

    .progress-draft {
        width: 21%;
        background: #9ca3af;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .activity-item:last-child {
        border-bottom: 0;
    }

    .activity-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .activity-title {
        font-weight: 600;
        font-size: .88rem;
        color: #374151;
        margin-bottom: 2px;
    }

    .activity-meta {
        color: #9ca3af;
        font-size: .76rem;
    }

    .queue-card {
        border-radius: 14px;
        padding: 17px;
        border: 1px solid #edf0f3;
        margin-bottom: 12px;
        transition: .2s;
    }

    .queue-card:hover {
        border-color: #d5dbe3;
        transform: translateX(2px);
    }

    .queue-number {
        font-size: 1.55rem;
        font-weight: 800;
        line-height: 1;
    }

    .queue-title {
        font-size: .82rem;
        font-weight: 600;
        color: #4b5563;
    }

    .health-ring {
        width: 135px;
        height: 135px;
        border-radius: 50%;
        background:
            conic-gradient(#22c55e 0deg 295deg,
                #f59e0b 295deg 342deg,
                #ef4444 342deg 360deg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 5px auto 20px;
    }

    .health-ring-inner {
        width: 103px;
        height: 103px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .health-number {
        font-size: 1.6rem;
        font-weight: 800;
        color: #1f2937;
    }

    .health-text {
        font-size: .72rem;
        color: #9ca3af;
    }

    .health-legend {
        display: flex;
        justify-content: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .legend-item {
        font-size: .78rem;
        color: #6b7280;
    }

    .legend-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }

    .table-dashboard thead th {
        border-top: 0;
        background: #f8fafc;
        color: #6b7280;
        font-size: .76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .table-dashboard tbody td {
        vertical-align: middle;
        font-size: .84rem;
        color: #374151;
    }

    .project-name {
        font-weight: 700;
        color: #1f2937;
    }

    .user-avatar {
        width: 31px;
        height: 31px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e5e7eb;
        color: #4b5563;
        font-weight: 700;
        font-size: .72rem;
        margin-right: 7px;
    }

    .system-footer {
        color: #9ca3af;
        font-size: .75rem;
    }



    @media (max-width: 767px) {
        .hero-admin {
            padding: 22px;
        }

        .hero-title {
            font-size: 1.3rem;
        }

        .hero-badge {
            display: inline-block;
            margin-top: 15px;
        }

        .kpi-number {
            font-size: 1.45rem;
        }
    }
</style>


<div class="admin-dashboard py-4">

    <div class="container-fluid">

        {{-- =====================================================
             HERO
        ====================================================== --}}
        <div class="hero-admin mb-4">

            <div class="d-flex flex-wrap align-items-center justify-content-between">

                <div class="d-flex align-items-center">

                    <div class="hero-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>

                    <div>
                        <div class="hero-title">
                            ยินดีต้อนรับ, {{ auth()->user()->name ?? 'ผู้ดูแลระบบ' }} (ผู้ดูแลระบบ)
                        </div>

                        <p class="hero-subtitle">
                            ระบบสารสนเทศเพื่อการบริหารจัดการบริการวิชาการแบบบูรณาการ คณะวิทยาศาสตร์ มหาวิทยาลัยสงขลานครินทร์
                        </p>
                    </div>

                </div>

                <div class="hero-badge" style="position: relative; z-index: 100;">
                    <i class="far fa-calendar-alt mr-1"></i>
                    <select name="fiscal_year" id="fiscal_year" class="text-white font-weight-bold" style="background-color: transparent; border: none; cursor: pointer;">
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
             KPI
        ====================================================== --}}
        <div class="row mb-4">

            {{-- 1. โครงการทั้งหมด --}}
            <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                <div class="kpi-card p-3 h-100 shadow-sm border-0 rounded">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-info text-white mr-3 d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px;">
                            <i class="fas fa-project-diagram fa-lg"></i>
                        </div>
                        <div>
                            <div class="kpi-number font-weight-bold" style="font-size: 1.4rem;">
                                {{ $countTotal }}
                            </div>
                            <div class="kpi-label text-muted" style="font-size: 0.8rem; line-height: 1.2;">
                                โครงการทั้งหมด
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. อบรม ประชุม สัมมนาฯ --}}
            <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                <div class="kpi-card p-3 h-100 shadow-sm border-0 rounded">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-primary text-white mr-3 d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px;">
                            <i class="fas fa-chalkboard-teacher fa-lg"></i>
                        </div>
                        <div>
                            <div class="kpi-number font-weight-bold" style="font-size: 1.4rem;">
                                {{ $countTraining }}
                            </div>
                            <div class="kpi-label text-muted" style="font-size: 0.8rem; line-height: 1.2;">
                                อบรม ประชุม สัมมนาฯ
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. บริการวิชาการ --}}
            <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                <div class="kpi-card p-3 h-100 shadow-sm border-0 rounded">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-success text-white mr-3 d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px;">
                            <i class="fas fa-hands-helping fa-lg"></i>
                        </div>
                        <div>
                            <div class="kpi-number font-weight-bold" style="font-size: 1.4rem;">
                                {{ $countAcademic }}
                            </div>
                            <div class="kpi-label text-muted" style="font-size: 0.8rem; line-height: 1.2;">
                                บริการวิชาการ
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. วิทยากร --}}
            <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                <div class="kpi-card p-3 h-100 shadow-sm border-0 rounded" style="opacity: 0.6; filter: grayscale(100%); cursor: not-allowed;">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-warning text-white mr-3 d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px;">
                            <i class="fas fa-user-tie fa-lg"></i>
                        </div>
                        <div>
                            <div class="kpi-number font-weight-bold" style="font-size: 1.4rem;">
                                0
                            </div>
                            <div class="kpi-label text-muted" style="font-size: 0.8rem; line-height: 1.2;">
                                วิทยากร
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. วิเคราะห์ทดสอบ --}}
            <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                <div class="kpi-card p-3 h-100 shadow-sm border-0 rounded" style="opacity: 0.6; filter: grayscale(100%); cursor: not-allowed;">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-danger text-white mr-3 d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px;">
                            <i class="fas fa-flask fa-lg"></i>
                        </div>
                        <div>
                            <div class="kpi-number font-weight-bold" style="font-size: 1.4rem;">
                                0
                            </div>
                            <div class="kpi-label text-muted" style="font-size: 0.8rem; line-height: 1.2;">
                                วิเคราะห์ทดสอบ
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. นำส่งเงิน --}}
            <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                <div class="kpi-card p-3 h-100 shadow-sm border-0 rounded" style="opacity: 0.6; filter: grayscale(100%); cursor: not-allowed;">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-secondary text-white mr-3 d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px;">
                            <i class="fas fa-file-invoice-dollar fa-lg"></i>
                        </div>
                        <div>
                            <div class="kpi-number font-weight-bold" style="font-size: 1.4rem;">
                                0
                            </div>
                            <div class="kpi-label text-muted" style="font-size: 0.8rem; line-height: 1.2;">
                                นำส่งเงิน
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- =====================================================
             WORKFLOW + ACTIVITY
        ====================================================== --}}
        <div class="row mb-4">

            {{-- Workflow --}}
            <div class="col-lg-7 mb-4">
                <div class="dashboard-card">

                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="fas fa-stream text-primary"></i>
                            Project Workflow
                        </h5>
                        <span class="badge badge-light shadow-sm">
                            รวมทั้งหมด {{ $countTotal }} โครงการ
                        </span>
                    </div>

                    <div class="card-body">

                        {{-- 🟢 Legend (ป้ายกำกับสีประเภทโครงการ) --}}
                        <div class="mb-4 pb-3 border-bottom d-flex flex-wrap align-items-center justify-content-between" style="font-size: 0.85rem;">
                            <span class="mr-2 mb-1"><i class="fas fa-circle text-primary mr-1"></i> อบรม ประชุม สัมมนาฯ</span>
                            <span class="mr-2 mb-1"><i class="fas fa-circle text-success mr-1"></i> บริการวิชาการ</span>
                            <span class="mr-2 mb-1"><i class="fas fa-circle text-warning mr-1"></i> วิทยากร</span>
                            <span class="mb-1"><i class="fas fa-circle text-danger mr-1"></i> วิเคราะห์ทดสอบ</span>
                        </div>

                        {{-- 🔵 ข้อมูลจริงจากฐานข้อมูล --}}
                        
                        {{-- 🟣 วนลูปแสดงผลกราฟ Stacked Progress Bar --}}
                        @foreach($workflowStatuses as $status)
                        @php
                        // คำนวณ % ความกว้างของแต่ละประเภท
                        $total = $status['total'] > 0 ? $status['total'] : 1;
                        $pct1 = ($status['type1'] / $total) * 100;
                        $pct2 = ($status['type2'] / $total) * 100;
                        $pct3 = ($status['type3'] / $total) * 100;
                        $pct4 = ($status['type4'] / $total) * 100;
                        @endphp

                        <div class="workflow-item mb-4">

                            {{-- ชื่อสถานะ และ จำนวนรวม --}}
                            <div class="workflow-label d-flex justify-content-between mb-2">
                                <div>
                                    <span class="badge badge-light border text-muted mr-1" style="font-size: 0.75em;">{{ $status['id'] }}</span>
                                    <strong class="text-dark">{{ $status['name'] }}</strong>
                                </div>
                                <span class="font-weight-bold">{{ $status['total'] }} โครงการ</span>
                            </div>

                            {{-- หลอดสีแบบซ้อนกัน (Stacked) --}}
                            <div class="progress shadow-sm" style="height: 12px; border-radius: 6px; background-color: #f1f3f5;">

                                @if($status['type1'] > 0)
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pct1 }}%"
                                    data-toggle="tooltip" data-placement="top" title="อบรมฯ: {{ $status['type1'] }} โครงการ"></div>
                                @endif

                                @if($status['type2'] > 0)
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pct2 }}%"
                                    data-toggle="tooltip" data-placement="top" title="บริการวิชาการ: {{ $status['type2'] }} โครงการ"></div>
                                @endif

                                {{-- ส่วนโมดูลอนาคต: ใส่ style จางๆ --}}
                                @if($status['type3'] > 0)
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pct3 }}%; opacity: 0.5;"
                                    data-toggle="tooltip" data-placement="top" title="วิทยากร: {{ $status['type3'] }} โครงการ"></div>
                                @endif

                                @if($status['type4'] > 0)
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pct4 }}%; opacity: 0.5;"
                                    data-toggle="tooltip" data-placement="top" title="วิเคราะห์ทดสอบ: {{ $status['type4'] }} โครงการ"></div>
                                @endif

                            </div>

                        </div>
                        @endforeach

                    </div>
                </div>
            </div>


            {{-- Activity --}}
            <div class="col-lg-5 mb-4">

                <div class="dashboard-card h-100">

                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="fas fa-history text-info"></i>
                            System Activity
                        </h5>
                        <span class="badge badge-info shadow-sm">
                            Live Mock
                        </span>
                    </div>

                    {{-- กระจายพื้นที่ให้พอดีโดยไม่มี Scrollbar --}}
                    <div class="card-body d-flex flex-column justify-content-between pb-3">

                        {{-- 🔵 ข้อมูลจริงจากฐานข้อมูล --}}
                        
                        {{-- 🟣 วนลูปแสดงผลรายการกิจกรรมล่าสุด --}}
                        @foreach($latestActivities as $activity)
                        {{-- ใช้ mb-2 เพื่อลดช่องว่างให้ยัดได้พอดี --}}
                        <div class="activity-item d-flex {{ $loop->last ? '' : 'mb-2' }}">

                            {{-- ไอคอน --}}
                            <div class="activity-icon {{ $activity['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm mr-2" style="width: 32px; height: 32px; flex-shrink: 0; font-size: 0.8rem;">
                                <i class="{{ $activity['icon'] }}"></i>
                            </div>

                            {{-- รายละเอียด --}}
                            <div style="min-width: 0; width: 100%;">
                                {{-- ชื่อสถานะ --}}
                                <div class="activity-title font-weight-bold text-dark mb-0 text-truncate" style="font-size: 0.85rem; line-height: 1.2;">
                                    {{ $activity['title'] }}
                                    <span class="badge badge-light border text-muted ml-1" style="font-size: 0.65em;">{{ $activity['status'] }}</span>
                                </div>

                                {{-- Meta Text --}}
                                <div class="activity-meta text-muted text-truncate" style="font-size: 0.75rem; line-height: 1.2;">
                                    {{ $activity['meta'] }}
                                </div>
                            </div>

                        </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             APPROVAL QUEUE + HEALTH
        ====================================================== --}}
        <div class="row mb-4">

            {{-- Approval Queue --}}
            <div class="col-lg-7 mb-4">

                <div class="dashboard-card">

                    <div class="dashboard-card-header">

                        <h5 class="dashboard-card-title">
                            <i class="fas fa-inbox text-warning"></i>
                            Approval & Attention Queue
                        </h5>

                        <span class="badge badge-warning">
                            18 รายการ
                        </span>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4">

                                <div class="queue-card">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>
                                            <div class="queue-title">
                                                รออนุมัติ
                                            </div>

                                            <div class="queue-number text-warning">
                                                7
                                            </div>
                                        </div>

                                        <i class="fas fa-file-signature fa-2x text-warning opacity-50"></i>

                                    </div>

                                    <div class="small text-muted mt-2">
                                        โครงการรอการพิจารณา
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="queue-card">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>
                                            <div class="queue-title">
                                                รอแก้ไข
                                            </div>

                                            <div class="queue-number text-danger">
                                                6
                                            </div>
                                        </div>

                                        <i class="fas fa-edit fa-2x text-danger opacity-50"></i>

                                    </div>

                                    <div class="small text-muted mt-2">
                                        เอกสารหรือข้อมูลไม่ครบ
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="queue-card">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>
                                            <div class="queue-title">
                                                ต้องติดตาม
                                            </div>

                                            <div class="queue-number text-primary">
                                                5
                                            </div>
                                        </div>

                                        <i class="fas fa-bell fa-2x text-primary opacity-50"></i>

                                    </div>

                                    <div class="small text-muted mt-2">
                                        รายการใกล้ครบกำหนด
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Health --}}
            <div class="col-lg-5 mb-4">

                <div class="dashboard-card">

                    <div class="dashboard-card-header">

                        <h5 class="dashboard-card-title">
                            <i class="fas fa-heartbeat text-success"></i>
                            Project Health
                        </h5>

                    </div>

                    <div class="card-body text-center">

                        <div class="health-ring">

                            <div class="health-ring-inner">

                                <div class="health-number">
                                    82%
                                </div>

                                <div class="health-text">
                                    On Track
                                </div>

                            </div>

                        </div>


                        <div class="health-legend">

                            <div class="legend-item">
                                <span class="legend-dot bg-success"></span>
                                On Track 82%
                            </div>

                            <div class="legend-item">
                                <span class="legend-dot bg-warning"></span>
                                Attention 13%
                            </div>

                            <div class="legend-item">
                                <span class="legend-dot bg-danger"></span>
                                Critical 5%
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RECENT PROJECT ACTIVITY
        ====================================================== --}}
        <div class="dashboard-card mb-3">

            <div class="dashboard-card-header">

                <h5 class="dashboard-card-title">
                    <i class="fas fa-project-diagram text-primary"></i>
                    Recent Project Activity
                </h5>

                <button class="btn btn-sm btn-outline-secondary">
                    ดูทั้งหมด
                    <i class="fas fa-arrow-right ml-1"></i>
                </button>

            </div>


            <div class="table-responsive">

                <table class="table table-hover table-dashboard mb-0">

                    <thead>

                        <tr>
                            <th class="pl-4">โครงการ</th>
                            <th>ผู้ดำเนินการ</th>
                            <th>กิจกรรม</th>
                            <th>เวลา</th>
                            <th class="text-center">สถานะ</th>
                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td class="pl-4">
                                <div class="project-name">
                                    บริการตรวจวิเคราะห์คุณภาพน้ำ
                                </div>

                                <small class="text-muted">
                                    AC-2569-014
                                </small>
                            </td>

                            <td>
                                <span class="user-avatar">WK</span>
                                เจ้าหน้าที่
                            </td>

                            <td>
                                เปลี่ยนสถานะโครงการ
                            </td>

                            <td>
                                10 นาทีที่แล้ว
                            </td>

                            <td class="text-center">
                                <span class="badge badge-success">
                                    อนุมัติ
                                </span>
                            </td>

                        </tr>


                        <tr>

                            <td class="pl-4">

                                <div class="project-name">
                                    อบรม Data Science
                                </div>

                                <small class="text-muted">
                                    TR-2569-008
                                </small>

                            </td>

                            <td>
                                <span class="user-avatar">PS</span>
                                ผู้ประสานงาน
                            </td>

                            <td>
                                ส่งเอกสารโครงการ
                            </td>

                            <td>
                                32 นาทีที่แล้ว
                            </td>

                            <td class="text-center">
                                <span class="badge badge-warning">
                                    รอตรวจ
                                </span>
                            </td>

                        </tr>


                        <tr>

                            <td class="pl-4">

                                <div class="project-name">
                                    ที่ปรึกษาระบบห้องปฏิบัติการ
                                </div>

                                <small class="text-muted">
                                    AC-2569-006
                                </small>

                            </td>

                            <td>
                                <span class="user-avatar">NT</span>
                                นักวิจัย
                            </td>

                            <td>
                                แก้ไขรายละเอียดโครงการ
                            </td>

                            <td>
                                1 ชั่วโมงที่แล้ว
                            </td>

                            <td class="text-center">
                                <span class="badge badge-info">
                                    ดำเนินการ
                                </span>
                            </td>

                        </tr>


                        <tr>

                            <td class="pl-4">

                                <div class="project-name">
                                    โครงการอบรมเชิงปฏิบัติการ
                                </div>

                                <small class="text-muted">
                                    TR-2569-003
                                </small>

                            </td>

                            <td>
                                <span class="user-avatar">AK</span>
                                ผู้ประสานงาน
                            </td>

                            <td>
                                บันทึกผลการดำเนินงาน
                            </td>

                            <td>
                                2 ชั่วโมงที่แล้ว
                            </td>

                            <td class="text-center">
                                <span class="badge badge-primary">
                                    ส่งมอบ
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script src="{{ asset('js/dashboards/admin/index.js?v=' . time()) }}"></script>
@endsection