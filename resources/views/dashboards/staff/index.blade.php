@extends('layouts.main_all')

@section('title', 'Staff Workspace')

@section('content')

<style>
    .staff-dashboard {
        background: #f8fafc;
        min-height: calc(100vh - 100px);
    }

    /* Hero Section สำหรับ Staff ใช้โทนสีฟ้า/น้ำเงิน ดูเป็นมิตรและกระฉับกระเฉง */
    .hero-staff {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 55%, #1e40af 100%);
        color: #fff;
        border-radius: 18px;
        padding: 28px 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.15);
    }

    .hero-staff:after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -80px;
        top: -100px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .hero-staff .hero-icon {
        width: 62px;
        height: 62px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.15);
        font-size: 28px;
        margin-right: 15px;
    }

    .hero-title {
        font-size: 1.65rem;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .hero-subtitle {
        color: rgba(255,255,255,.80);
        margin: 0;
    }

    .hero-badge {
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.20);
        color: #fff;
        padding: 9px 15px;
        border-radius: 50px;
        font-size: .85rem;
    }

    /* Card Styles แบบเรียบหรู */
    .dashboard-card, .kpi-card {
        background: #fff;
        border: 0;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,.04);
        height: 100%;
        transition: all .25s ease;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,.08);
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
        border-bottom: 1px dashed #edf0f3;
    }
    
    .todo-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .todo-icon {
        width: 40px;
        height: 40px;
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
        margin-bottom: 4px;
    }

    .todo-content p {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 8px;
    }

    /* Project Tracking Pipeline */
    .tracking-pipeline {
        position: relative;
        padding-left: 30px;
    }

    .tracking-pipeline::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: #e5e7eb;
    }

    .tracking-item {
        position: relative;
        margin-bottom: 20px;
    }

    .tracking-item:last-child {
        margin-bottom: 0;
    }

    .tracking-dot {
        position: absolute;
        left: -30px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid;
        z-index: 1;
    }

    .tracking-info h6 {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .tracking-info span {
        font-size: 0.75rem;
        color: #9ca3af;
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
        .hero-staff { padding: 22px; }
        .hero-title { font-size: 1.3rem; }
        .hero-badge { display: inline-block; margin-top: 15px; }
    }
</style>

<div class="staff-dashboard py-4">
    <div class="container-fluid">

        {{-- =====================================================
             HERO SECTION (STAFF)
        ====================================================== --}}
        <div class="hero-staff mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="hero-icon">
                        <i class="fas fa-laptop-house"></i>
                    </div>
                    <div>
                        <div class="hero-title">
                            My Workspace
                        </div>
                        <p class="hero-subtitle">
                            ยินดีต้อนรับกลับมา! นี่คือภาพรวมงานบริการวิชาการของคุณ
                        </p>
                    </div>
                </div>
                <div class="hero-badge mt-3 mt-md-0">
                    <i class="fas fa-user-circle mr-1"></i>
                    สิทธิ์การใช้งาน: Staff
                </div>
            </div>
        </div>

        {{-- =====================================================
             KPI CARDS (เน้นงานของตัวเอง)
        ====================================================== --}}
        <div class="row mb-4">
            {{-- My Total Projects --}}
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-primary text-white mr-3">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div>
                            <div class="kpi-number text-primary">15</div>
                            <div class="kpi-label">โครงการที่รับผิดชอบ</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- In Progress --}}
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-info text-white mr-3">
                            <i class="fas fa-spinner"></i>
                        </div>
                        <div>
                            <div class="kpi-number text-info">8</div>
                            <div class="kpi-label">กำลังดำเนินการ</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Required (สำคัญสำหรับ Staff) --}}
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-danger text-white mr-3">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div>
                            <div class="kpi-number text-danger">3</div>
                            <div class="kpi-label">ต้องแก้ไข / รอส่งเอกสาร</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Waiting Approval --}}
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-warning text-white mr-3">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div>
                            <div class="kpi-number text-warning">4</div>
                            <div class="kpi-label">รอผู้บริหารพิจารณา</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =====================================================
             MIDDLE SECTION: TO-DO LIST & TRACKING
        ====================================================== --}}
        <div class="row mb-4">
            {{-- Action Required / To-Do List --}}
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="fas fa-clipboard-list text-danger"></i>
                            รายการที่ต้องดำเนินการ (To-Do List)
                        </h5>
                        <span class="badge badge-danger">3 รายการด่วน</span>
                    </div>
                    <div class="card-body">
                        
                        <div class="todo-item">
                            <div class="todo-icon bg-danger text-white">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <div class="todo-content flex-grow-1">
                                <h6 class="text-danger">แก้ไขข้อเสนอโครงการ (งบประมาณไม่ถูกต้อง)</h6>
                                <p>โครงการอบรมเชิงปฏิบัติการ Data Analytics (TR-2569-008)</p>
                                <button class="btn btn-sm btn-outline-danger px-3">ดำเนินการแก้ไข</button>
                            </div>
                        </div>

                        <div class="todo-item">
                            <div class="todo-icon bg-warning text-white">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <div class="todo-content flex-grow-1">
                                <h6 class="text-warning text-dark">ถึงกำหนดส่งรายงานผล (งวดที่ 1)</h6>
                                <p>โครงการที่ปรึกษาระบบห้องปฏิบัติการ (AC-2569-006)</p>
                                <button class="btn btn-sm btn-outline-warning px-3">แนบไฟล์รายงาน</button>
                            </div>
                        </div>

                        <div class="todo-item">
                            <div class="todo-icon bg-secondary text-white">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="todo-content flex-grow-1">
                                <h6 class="text-secondary text-dark">ร่างโครงการยังไม่เสร็จสมบูรณ์</h6>
                                <p>โครงการบริการตรวจวิเคราะห์คุณภาพน้ำ (Draft)</p>
                                <button class="btn btn-sm btn-outline-secondary px-3">ทำต่อให้เสร็จ</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- My Projects Tracking Pipeline --}}
            <div class="col-lg-5">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            ติดตามสถานะโครงการ (Tracking)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="tracking-pipeline mt-2">
                            
                            <div class="tracking-item">
                                <div class="tracking-dot border-success"></div>
                                <div class="tracking-info">
                                    <h6 class="text-success">อนุมัติเรียบร้อยแล้ว (เบิกจ่ายได้)</h6>
                                    <span>TR-2569-002: อบรมเทคนิคการใช้ AI</span>
                                </div>
                            </div>

                            <div class="tracking-item">
                                <div class="tracking-dot border-warning"></div>
                                <div class="tracking-info">
                                    <h6 class="text-warning text-dark">อยู่ระหว่างผู้บริหารพิจารณา</h6>
                                    <span>AC-2569-011: ตรวจวิเคราะห์สารเคมี</span>
                                </div>
                            </div>

                            <div class="tracking-item">
                                <div class="tracking-dot border-info"></div>
                                <div class="tracking-info">
                                    <h6 class="text-info">หัวหน้าหน่วยงานตรวจสอบแล้ว</h6>
                                    <span>TR-2569-015: โครงการค่ายวิทยาศาสตร์</span>
                                </div>
                            </div>

                            <div class="tracking-item">
                                <div class="tracking-dot border-danger"></div>
                                <div class="tracking-info">
                                    <h6 class="text-danger">ตีกลับให้แก้ไข</h6>
                                    <span>TR-2569-008: อบรม Data Analytics</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =====================================================
             RECENT PROJECTS TABLE
        ====================================================== --}}
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title">
                    <i class="fas fa-folder text-info"></i>
                    โครงการล่าสุดของฉัน
                </h5>
                <a href="#" class="btn btn-sm btn-primary">สร้างโครงการใหม่</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-dashboard mb-0">
                    <thead>
                        <tr>
                            <th class="pl-4">รหัสโครงการ</th>
                            <th>ชื่อโครงการ</th>
                            <th>ประเภท</th>
                            <th>อัปเดตล่าสุด</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pl-4 font-weight-bold">TR-2569-008</td>
                            <td>อบรมเชิงปฏิบัติการ Data Analytics</td>
                            <td><span class="badge badge-light text-muted border">ฝึกอบรม</span></td>
                            <td>วันนี้ 10:30 น.</td>
                            <td class="text-center"><span class="badge badge-soft bg-danger text-white">รอแก้ไข</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light"><i class="fas fa-edit text-primary"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="pl-4 font-weight-bold">AC-2569-011</td>
                            <td>ตรวจวิเคราะห์สารเคมี บริษัท ABC</td>
                            <td><span class="badge badge-light text-muted border">บริการวิชาการ</span></td>
                            <td>เมื่อวาน 15:45 น.</td>
                            <td class="text-center"><span class="badge badge-soft bg-warning text-dark">รอพิจารณา</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light"><i class="fas fa-search text-info"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="pl-4 font-weight-bold">TR-2569-002</td>
                            <td>อบรมเทคนิคการใช้ AI สำหรับองค์กร</td>
                            <td><span class="badge badge-light text-muted border">ฝึกอบรม</span></td>
                            <td>3 วันที่แล้ว</td>
                            <td class="text-center"><span class="badge badge-soft bg-success text-white">อนุมัติแล้ว</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light"><i class="fas fa-search text-info"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection