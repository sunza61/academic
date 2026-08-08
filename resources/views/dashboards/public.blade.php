@extends('layouts.main_all')
@section('content')

<head>
    <link href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css?')}}{{sha1(time())}}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css?')}}{{sha1(time())}}" rel="stylesheet">
    <style>
        .service-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .coming-soon-card {
            opacity: 0.75;
            filter: grayscale(80%);
        }
    </style>
</head>

<div class="container-fluid pt-4 pb-5">
    {{-- 🛑 เช็คก่อนว่า "ล็อกอินหรือยัง?" --}}
    @auth

    {{-- 👑 โซนของ Admin และ Manager (ผู้บริหาร / ผู้ดูแลระบบ) --}}
    @hasanyrole('admin|manager')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <h5><i class="icon fas fa-chart-pie"></i> ภาพรวมหน่วยงาน (Admin / Manager)</h5>
                <p class="mb-0">จำนวนโครงการทั้งหมดในระบบ: <strong>{{ number_format($totalProjects ?? 0) }} โครงการ</strong></p>
            </div>
        </div>
    </div>
    @endhasanyrole

    {{-- 🧑‍🏫 โซนของ Staff และ User ทั่วไป (บุคลากร / อาจารย์) --}}
    @hasanyrole('staff|user')
    <div class="row mt-2">
        <div class="col-12">
            <div class="alert alert-success border-0 shadow-sm bg-white text-dark border-left border-success" style="border-width: 5px !important;">
                <h5><i class="icon fas fa-user-tie text-success"></i> ภาระงานของฉัน (My Projects)</h5>
                <p class="mb-0 text-muted">กำลังรอดึงข้อมูลโครงการที่กำลังดำเนินการ และกำหนดการส่งงาน...</p>
            </div>
        </div>
    </div>
    @endhasanyrole

    {{-- 🛑 กรณีที่ล็อกอินแล้ว แต่ "ไม่มี" สิทธิ์อะไรเลย --}}
    @if(!auth()->user()->hasAnyRole(['admin', 'manager', 'staff', 'user', 'finance', 'plan']))
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-user-clock text-warning" style="font-size: 5rem;"></i>
                    </div>
                    <h3 class="font-weight-bold text-dark">ยินดีต้อนรับเข้าสู่ระบบ</h3>
                    <p class="text-muted" style="font-size: 1.1rem;">
                        บัญชีของคุณเข้าสู่ระบบสำเร็จแล้ว แต่ยัง <strong>ไม่ได้รับการกำหนดสิทธิ์</strong> ในการเข้าถึง
                    </p>
                    <hr class="w-50 mx-auto my-4">
                    <p class="text-muted">กรุณาติดต่อผู้ดูแลระบบ (Admin) เพื่อขอรับสิทธิ์เข้าใช้งาน</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    @else
    {{-- 1. ส่วนต้อนรับและสถิติภาพรวม --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="font-weight-bold text-dark mb-1">
                <i class="fas fa-globe-asia text-primary mr-2"></i> ระบบบริการวิชาการ
            </h2>
            <p class="text-muted" style="font-size: 1.1rem;">คณะวิทยาศาสตร์ มหาวิทยาลัยสงขลานครินทร์</p>
        </div>
        <div class="col-md-4 text-md-right text-left mt-3 mt-md-0">
            <a href="{{ route('login') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="fas fa-sign-in-alt mr-1"></i> เข้าสู่ระบบสำหรับบุคลากร
            </a>
        </div>
    </div>

    {{-- สถิติแบบ Quick Info --}}
    <div class="row mb-5">
        <div class="col-md-3 col-6">
            <div class="info-box shadow-sm border-0">
                <span class="info-box-icon bg-info text-white elevation-1"><i class="fas fa-project-diagram"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted">โครงการทั้งหมด</span>
                    <span class="info-box-number" style="font-size: 1.5rem;">{{ number_format($totalProjects ?? 0) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box shadow-sm border-0">
                <span class="info-box-icon bg-success text-white elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted">ผู้เข้าร่วมโครงการ</span>
                    <span class="info-box-number" style="font-size: 1.5rem;">{{ number_format($totalParticipants ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. หมวดหมู่บริการ (Our Services) --}}
    <h4 class="font-weight-bold mb-4 text-dark"><i class="fas fa-th-large text-secondary mr-2"></i> บริการของเรา (Our Services)</h4>
    <div class="row mb-5">

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm border-0 border-top border-info service-card" style="border-width: 4px !important;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-info text-white rounded-circle shadow-sm" style="width: 70px; height: 70px;">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="font-weight-bold text-dark">อบรมและสัมมนา</h5>
                    <p class="text-muted small mb-4">บริการจัดอบรม พัฒนาทักษะ และสัมมนาวิชาการสำหรับบุคคลทั่วไป</p>
                    <a href="#" class="btn btn-outline-info btn-sm rounded-pill px-3 mt-auto">ดูหลักสูตร <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm border-0 border-top border-success service-card" style="border-width: 4px !important;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm" style="width: 70px; height: 70px;">
                            <i class="fas fa-microscope fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="font-weight-bold text-dark">บริการวิชาการ</h5>
                    <p class="text-muted small mb-4">โครงการที่ปรึกษา วิจัย และบริการวิชาการแก่สังคมและหน่วยงานภายนอก</p>
                    <a href="#" class="btn btn-outline-success btn-sm rounded-pill px-3 mt-auto">ดูรายละเอียด <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm border-0 border-top border-secondary bg-light coming-soon-card" style="border-width: 4px !important;">
                <div class="card-body text-center p-4 position-relative">
                    <span class="badge badge-warning position-absolute shadow-sm" style="top: 15px; right: 15px; font-size: 0.8rem;">Coming Soon</span>
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded-circle" style="width: 70px; height: 70px;">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="font-weight-bold text-secondary">วิทยากรภายนอก</h5>
                    <p class="text-muted small mb-4">ฐานข้อมูลและการจัดการวิทยากร (กรณีไม่เบิกค่าใช้จ่าย)</p>
                    <button class="btn btn-secondary btn-sm rounded-pill px-3 mt-auto" disabled>เร็วๆ นี้</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm border-0 border-top border-secondary bg-light coming-soon-card" style="border-width: 4px !important;">
                <div class="card-body text-center p-4 position-relative">
                    <span class="badge badge-warning position-absolute shadow-sm" style="top: 15px; right: 15px; font-size: 0.8rem;">Coming Soon</span>
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded-circle" style="width: 70px; height: 70px;">
                            <i class="fas fa-award fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="font-weight-bold text-secondary">ศูนย์มาตรฐานรับรอง</h5>
                    <p class="text-muted small mb-4">บริการทดสอบ ตรวจสอบ และออกใบรับรองมาตรฐานวิชาชีพ</p>
                    <button class="btn btn-secondary btn-sm rounded-pill px-3 mt-auto" disabled>เร็วๆ นี้</button>
                </div>
            </div>
        </div>

    </div>

    {{-- 3. ส่วนรายการไฮไลต์ (โครงการที่เปิดรับสมัคร) --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-custom-dark text-white py-3">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-bullhorn mr-2 text-warning"></i> โครงการอบรมที่กำลังเปิดรับสมัคร</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="border-top-0 pl-4">ชื่อโครงการ</th>
                            <th class="border-top-0">วันที่จัดอบรม</th>
                            <th class="border-top-0 text-center">สถานะ</th>
                            <th class="border-top-0 text-center">เพิ่มเติม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pl-4 font-weight-bold text-dark">โครงการอบรมเชิงปฏิบัติการ Data Science เบื้องต้น</td>
                            <td>15 - 16 ส.ค. 2569</td>
                            <td class="text-center"><span class="badge badge-success px-2 py-1">เปิดรับสมัคร</span></td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i> รายละเอียด</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="pl-4 font-weight-bold text-dark">บริการตรวจวัดคุณภาพน้ำสำหรับชุมชน</td>
                            <td>ตลอดปีงบประมาณ</td>
                            <td class="text-center"><span class="badge badge-info px-2 py-1">ให้บริการ</span></td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i> รายละเอียด</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endauth
</div>

@endsection