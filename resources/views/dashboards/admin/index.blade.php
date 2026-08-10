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
        box-shadow: 0 10px 30px rgba(0,0,0,.12);
    }

    .hero-admin:after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -80px;
        top: -100px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }

    .hero-admin .hero-icon {
        width: 62px;
        height: 62px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.10);
        font-size: 28px;
        margin-right: 15px;
    }

    .hero-title {
        font-size: 1.65rem;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .hero-subtitle {
        color: rgba(255,255,255,.70);
        margin: 0;
    }

    .hero-badge {
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.12);
        color: #fff;
        padding: 9px 15px;
        border-radius: 50px;
        font-size: .85rem;
    }

    .kpi-card {
        border: 0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(0,0,0,.06);
        height: 100%;
        transition: all .25s ease;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,.10);
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
        box-shadow: 0 5px 18px rgba(0,0,0,.06);
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

    .progress-approved { width: 78%; background: #22c55e; }
    .progress-review { width: 43%; background: #f59e0b; }
    .progress-running { width: 61%; background: #3b82f6; }
    .progress-delivery { width: 29%; background: #8b5cf6; }
    .progress-draft { width: 21%; background: #9ca3af; }

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
            conic-gradient(
                #22c55e 0deg 295deg,
                #f59e0b 295deg 342deg,
                #ef4444 342deg 360deg
            );
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
                            System Control Center
                        </div>

                        <p class="hero-subtitle">
                            ระบบบริหารจัดการบริการวิชาการ
                            คณะวิทยาศาสตร์ มหาวิทยาลัยสงขลานครินทร์
                        </p>
                    </div>

                </div>

                <div class="hero-badge">
                    <i class="far fa-calendar-alt mr-1"></i>
                    ปีงบประมาณ 2569
                </div>

            </div>

        </div>


        {{-- =====================================================
             KPI
        ====================================================== --}}
        <div class="row mb-4">

            {{-- Projects --}}
            <div class="col-xl-3 col-md-6 mb-3">

                <div class="kpi-card p-3">

                    <div class="d-flex align-items-center">

                        <div class="kpi-icon bg-primary text-white mr-3">
                            <i class="fas fa-project-diagram"></i>
                        </div>

                        <div>

                            <div class="kpi-number">
                                128
                            </div>

                            <div class="kpi-label">
                                โครงการทั้งหมด
                            </div>

                        </div>

                    </div>

                    <div class="mt-3 small text-success">
                        <i class="fas fa-arrow-up mr-1"></i>
                        12% จากปีที่ผ่านมา
                    </div>

                </div>

            </div>


            {{-- Approval --}}
            <div class="col-xl-3 col-md-6 mb-3">

                <div class="kpi-card p-3">

                    <div class="d-flex align-items-center">

                        <div class="kpi-icon bg-warning text-white mr-3">
                            <i class="fas fa-file-signature"></i>
                        </div>

                        <div>

                            <div class="kpi-number">
                                18
                            </div>

                            <div class="kpi-label">
                                โครงการรออนุมัติ
                            </div>

                        </div>

                    </div>

                    <div class="mt-3 small text-warning">
                        <i class="fas fa-clock mr-1"></i>
                        ต้องดำเนินการ
                    </div>

                </div>

            </div>


            {{-- Pending --}}
            <div class="col-xl-3 col-md-6 mb-3">

                <div class="kpi-card p-3">

                    <div class="d-flex align-items-center">

                        <div class="kpi-icon bg-danger text-white mr-3">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>

                        <div>

                            <div class="kpi-number">
                                7
                            </div>

                            <div class="kpi-label">
                                รายการที่ต้องติดตาม
                            </div>

                        </div>

                    </div>

                    <div class="mt-3 small text-danger">
                        <i class="fas fa-bell mr-1"></i>
                        มีรายการค้าง
                    </div>

                </div>

            </div>


            {{-- Users --}}
            <div class="col-xl-3 col-md-6 mb-3">

                <div class="kpi-card p-3">

                    <div class="d-flex align-items-center">

                        <div class="kpi-icon bg-success text-white mr-3">
                            <i class="fas fa-users-cog"></i>
                        </div>

                        <div>

                            <div class="kpi-number">
                                64
                            </div>

                            <div class="kpi-label">
                                ผู้ใช้งานระบบ
                            </div>

                        </div>

                    </div>

                    <div class="mt-3 small text-success">
                        <i class="fas fa-check-circle mr-1"></i>
                        ระบบพร้อมใช้งาน
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

                        <span class="badge badge-light">
                            128 โครงการ
                        </span>

                    </div>

                    <div class="card-body">

                        <div class="workflow-item">

                            <div class="workflow-label">
                                <strong>อนุมัติแล้ว</strong>
                                <span>46 โครงการ</span>
                            </div>

                            <div class="workflow-bar">
                                <div class="workflow-progress progress-approved"></div>
                            </div>

                        </div>


                        <div class="workflow-item">

                            <div class="workflow-label">
                                <strong>รอตรวจสอบ / อนุมัติ</strong>
                                <span>18 โครงการ</span>
                            </div>

                            <div class="workflow-bar">
                                <div class="workflow-progress progress-review"></div>
                            </div>

                        </div>


                        <div class="workflow-item">

                            <div class="workflow-label">
                                <strong>กำลังดำเนินการ</strong>
                                <span>35 โครงการ</span>
                            </div>

                            <div class="workflow-bar">
                                <div class="workflow-progress progress-running"></div>
                            </div>

                        </div>


                        <div class="workflow-item">

                            <div class="workflow-label">
                                <strong>รอส่งมอบ / ปิดโครงการ</strong>
                                <span>17 โครงการ</span>
                            </div>

                            <div class="workflow-bar">
                                <div class="workflow-progress progress-delivery"></div>
                            </div>

                        </div>


                        <div class="workflow-item">

                            <div class="workflow-label">
                                <strong>ร่าง / ยังไม่ส่ง</strong>
                                <span>12 โครงการ</span>
                            </div>

                            <div class="workflow-bar">
                                <div class="workflow-progress progress-draft"></div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Activity --}}
            <div class="col-lg-5 mb-4">

                <div class="dashboard-card">

                    <div class="dashboard-card-header">

                        <h5 class="dashboard-card-title">
                            <i class="fas fa-history text-info"></i>
                            System Activity
                        </h5>

                        <span class="badge badge-info">
                            Live Mock
                        </span>

                    </div>

                    <div class="card-body">

                        <div class="activity-item">

                            <div class="activity-icon bg-success text-white">
                                <i class="fas fa-plus"></i>
                            </div>

                            <div>
                                <div class="activity-title">
                                    สร้างโครงการใหม่
                                </div>

                                <div class="activity-meta">
                                    โครงการบริการตรวจวิเคราะห์ • 5 นาทีที่แล้ว
                                </div>
                            </div>

                        </div>


                        <div class="activity-item">

                            <div class="activity-icon bg-warning text-white">
                                <i class="fas fa-exchange-alt"></i>
                            </div>

                            <div>
                                <div class="activity-title">
                                    เปลี่ยนสถานะโครงการ
                                </div>

                                <div class="activity-meta">
                                    โครงการอบรม Data Science • 18 นาทีที่แล้ว
                                </div>
                            </div>

                        </div>


                        <div class="activity-item">

                            <div class="activity-icon bg-primary text-white">
                                <i class="fas fa-edit"></i>
                            </div>

                            <div>
                                <div class="activity-title">
                                    แก้ไขข้อมูลโครงการ
                                </div>

                                <div class="activity-meta">
                                    โครงการบริการวิชาการ • 42 นาทีที่แล้ว
                                </div>
                            </div>

                        </div>


                        <div class="activity-item">

                            <div class="activity-icon bg-danger text-white">
                                <i class="fas fa-user-check"></i>
                            </div>

                            <div>
                                <div class="activity-title">
                                    อนุมัติโครงการ
                                </div>

                                <div class="activity-meta">
                                    ผู้ดูแลระบบ • 1 ชั่วโมงที่แล้ว
                                </div>
                            </div>

                        </div>


                        <div class="activity-item">

                            <div class="activity-icon bg-secondary text-white">
                                <i class="fas fa-file-upload"></i>
                            </div>

                            <div>
                                <div class="activity-title">
                                    ส่งรายงานโครงการ
                                </div>

                                <div class="activity-meta">
                                    Project #AC-2569-014 • 2 ชั่วโมงที่แล้ว
                                </div>
                            </div>

                        </div>

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


        <div class="text-right system-footer">
            <i class="fas fa-shield-alt mr-1"></i>
            Admin Dashboard • Mock Data • Academic Service
        </div>

    </div>

</div>

@endsection

