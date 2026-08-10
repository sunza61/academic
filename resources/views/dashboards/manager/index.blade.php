@extends('layouts.main_all')

@section('title', 'Manager Dashboard')

@section('content')

<div class="container-fluid mt-3">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 font-weight-bold">
                <i class="fas fa-chart-line text-primary mr-2"></i>
                Manager Dashboard
            </h2>
            <div class="text-muted">
                ภาพรวมการดำเนินงานบริการวิชาการ
            </div>
        </div>

        <div class="text-right">
            <span class="badge badge-primary px-3 py-2">
                <i class="fas fa-user-tie mr-1"></i>
                Manager
            </span>
            <div class="small text-muted mt-1">
                ข้อมูลจำลองสำหรับการออกแบบ Dashboard
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- KPI CARDS --}}
    {{-- ========================================================= --}}
    <div class="row">
        {{-- Total Projects --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-primary shadow-sm h-100 mb-0">
                <div class="inner">
                    <h3>{{ $kpiTotal ?? 128 }}</h3>
                    <p>โครงการทั้งหมด</p>
                    <div class="small">
                        <i class="fas fa-arrow-up mr-1"></i>
                        12.5% จากปีที่ผ่านมา
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <a href="#" class="small-box-footer mt-auto">
                    ดูรายละเอียด <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>

        {{-- Waiting Approval --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-warning shadow-sm h-100 mb-0">
                <div class="inner">
                    <h3>{{ $kpiWaiting ?? 12 }}</h3>
                    <p>รอพิจารณา</p>
                    <div class="small">
                        <i class="fas fa-clock mr-1"></i>
                        ต้องดำเนินการ
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <a href="#" class="small-box-footer mt-auto">
                    ดูรายการ <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>

        {{-- In Progress --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-info shadow-sm h-100 mb-0">
                <div class="inner">
                    <h3>{{ $kpiInProgress ?? 47 }}</h3>
                    <p>กำลังดำเนินการ</p>
                    <div class="small">
                        36.7% ของโครงการทั้งหมด
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <a href="#" class="small-box-footer mt-auto">
                    ดูรายละเอียด <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>

        {{-- Completed --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="small-box bg-success shadow-sm h-100 mb-0">
                <div class="inner">
                    <h3>{{ $kpiCompleted ?? 69 }}</h3>
                    <p>ดำเนินการเสร็จสิ้น</p>
                    <div class="small">
                        53.9% ของโครงการทั้งหมด
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="#" class="small-box-footer mt-auto">
                    ดูรายละเอียด <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- SECOND ROW (CHARTS) --}}
    {{-- ========================================================= --}}
    <div class="row mb-4">
        {{-- PROJECT TREND --}}
        <div class="col-lg-8 mb-3 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-header border-0 bg-white pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title font-weight-bold mb-0">
                                <i class="fas fa-chart-area text-primary mr-2"></i>
                                แนวโน้มโครงการ
                            </h3>
                            <div class="text-muted small mt-1">จำนวนโครงการรายเดือน</div>
                        </div>
                        <div>
                            <select class="form-control form-control-sm">
                                <option>ปี 2569</option>
                                <option>ปี 2568</option>
                                <option>ปี 2567</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="projectTrendChart" style="height:300px;"></canvas>
                </div>
            </div>
        </div>

        {{-- PROJECT STATUS --}}
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header border-0 bg-white pt-3">
                    <h3 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-chart-pie text-primary mr-2"></i>
                        สถานะโครงการ
                    </h3>
                </div>
                <div class="card-body pb-0">
                    <canvas id="projectStatusChart" style="height:220px;"></canvas>
                </div>
                <div class="card-footer bg-white border-0 pt-0 pb-3">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-warning font-weight-bold h5 mb-0">12</div>
                            <div class="small text-muted">รอพิจารณา</div>
                        </div>
                        <div class="col-4">
                            <div class="text-info font-weight-bold h5 mb-0">47</div>
                            <div class="small text-muted">ดำเนินการ</div>
                        </div>
                        <div class="col-4">
                            <div class="text-success font-weight-bold h5 mb-0">69</div>
                            <div class="small text-muted">เสร็จสิ้น</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- WORKFLOW --}}
    {{-- ========================================================= --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-0 bg-white pt-3">
                    <h3 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-project-diagram text-primary mr-2"></i>
                        Project Workflow
                    </h3>
                    <div class="text-muted small mt-1">ภาพรวมจำนวนโครงการในแต่ละขั้นตอน</div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        {{-- Draft --}}
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="mb-2">
                                <span class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width:65px;height:65px;">
                                    <i class="fas fa-file-alt fa-lg"></i>
                                </span>
                            </div>
                            <h4 class="font-weight-bold mb-0">18</h4>
                            <div class="small text-muted">Draft</div>
                        </div>

                        {{-- Submitted --}}
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="mb-2">
                                <span class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center" style="width:65px;height:65px;">
                                    <i class="fas fa-paper-plane fa-lg"></i>
                                </span>
                            </div>
                            <h4 class="font-weight-bold mb-0">12</h4>
                            <div class="small text-muted">Submitted</div>
                        </div>

                        {{-- Review --}}
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="mb-2">
                                <span class="rounded-circle bg-info text-white d-inline-flex align-items-center justify-content-center" style="width:65px;height:65px;">
                                    <i class="fas fa-search fa-lg"></i>
                                </span>
                            </div>
                            <h4 class="font-weight-bold mb-0">7</h4>
                            <div class="small text-muted">Review</div>
                        </div>

                        {{-- Approved --}}
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="mb-2">
                                <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:65px;height:65px;">
                                    <i class="fas fa-thumbs-up fa-lg"></i>
                                </span>
                            </div>
                            <h4 class="font-weight-bold mb-0">23</h4>
                            <div class="small text-muted">Approved</div>
                        </div>

                        {{-- In Progress --}}
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="mb-2">
                                <span class="rounded-circle bg-info text-white d-inline-flex align-items-center justify-content-center" style="width:65px;height:65px;">
                                    <i class="fas fa-cogs fa-lg"></i>
                                </span>
                            </div>
                            <h4 class="font-weight-bold mb-0">47</h4>
                            <div class="small text-muted">In Progress</div>
                        </div>

                        {{-- Completed --}}
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="mb-2">
                                <span class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width:65px;height:65px;">
                                    <i class="fas fa-check fa-lg"></i>
                                </span>
                            </div>
                            <h4 class="font-weight-bold mb-0">69</h4>
                            <div class="small text-muted">Completed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- BOTTOM SECTION --}}
    {{-- ========================================================= --}}
    <div class="row">
        {{-- TOP PROJECT TYPES --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header border-0 bg-white pt-3">
                    <h3 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-layer-group text-primary mr-2"></i>
                        ประเภทโครงการ
                    </h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>ประเภท</th>
                                <th class="text-center">โครงการ</th>
                                <th class="text-right">สัดส่วน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-handshake text-primary mr-2"></i> บริการวิชาการ</td>
                                <td class="text-center font-weight-bold">54</td>
                                <td class="text-right">42.2%</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-chalkboard-teacher text-info mr-2"></i> ฝึกอบรม</td>
                                <td class="text-center font-weight-bold">36</td>
                                <td class="text-right">28.1%</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user-tie text-success mr-2"></i> วิทยากร</td>
                                <td class="text-center font-weight-bold">21</td>
                                <td class="text-right">16.4%</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-file-signature text-warning mr-2"></i> สัญญาจ้าง</td>
                                <td class="text-center font-weight-bold">17</td>
                                <td class="text-right">13.3%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ACTION ITEMS --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header border-0 bg-white pt-3">
                    <h3 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-bell text-warning mr-2"></i>
                        รายการที่ควรติดตาม
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="mr-3">
                            <span class="badge badge-warning p-3 rounded-circle">
                                <i class="fas fa-clock fa-lg text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold">โครงการรอพิจารณา</div>
                            <div class="small text-muted">มี 12 โครงการที่รอการดำเนินการ</div>
                        </div>
                        <div><span class="badge badge-warning text-white px-2 py-1">12</span></div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <div class="mr-3">
                            <span class="badge badge-danger p-3 rounded-circle">
                                <i class="fas fa-exclamation-triangle fa-lg text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold">โครงการล่าช้า</div>
                            <div class="small text-muted">พบโครงการที่ใช้เวลาดำเนินงานเกินกำหนด</div>
                        </div>
                        <div><span class="badge badge-danger px-2 py-1">5</span></div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <div class="mr-3">
                            <span class="badge badge-info p-3 rounded-circle">
                                <i class="fas fa-file-alt fa-lg text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold">รอรายงานผล</div>
                            <div class="small text-muted">โครงการที่ควรติดตามการส่งรายงาน</div>
                        </div>
                        <div><span class="badge badge-info text-white px-2 py-1">8</span></div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <span class="badge badge-success p-3 rounded-circle">
                                <i class="fas fa-check fa-lg text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold">ปิดโครงการแล้ว</div>
                            <div class="small text-muted">โครงการที่ดำเนินการเสร็จสมบูรณ์</div>
                        </div>
                        <div><span class="badge badge-success px-2 py-1">69</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- ========================================================= --}}
{{-- ย้าย SCRIPT มาไว้ใน Section สำหรับสคริปต์โดยเฉพาะ --}}
{{-- ========================================================= --}}
@section('scripts')
{{-- หากในระบบมีไฟล์ Chart.js อยู่แล้ว สามารถเปลี่ยนเป็น <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script> ได้ครับ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // =====================================================
    // 1. Project Trend Chart
    // =====================================================
    const trendCanvas = document.getElementById('projectTrendChart');
    if (trendCanvas) {
        new Chart(trendCanvas, {
            type: 'line',
            data: {
                labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
                datasets: [{
                    label: 'โครงการ',
                    data: [6, 8, 11, 9, 13, 15, 12, 14, 11, 16, 7, 6],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    // =====================================================
    // 2. Project Status Chart
    // =====================================================
    const statusCanvas = document.getElementById('projectStatusChart');
    if (statusCanvas) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: ['รอพิจารณา', 'กำลังดำเนินการ', 'เสร็จสิ้น'],
                datasets: [{
                    data: [12, 47, 69],
                    backgroundColor: ['#ffc107', '#17a2b8', '#28a745'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>
@endsection