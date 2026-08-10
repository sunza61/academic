@extends('layouts.main_all')
@section('title', 'Academic Service Dashboard')

@section('content')

<style>
    body {
        background-color: #f4f6f9;
        /* สีพื้นหลังมาตรฐาน Dashboard */
    }

    /* =========================================
       HERO DASHBOARD (ปรับให้ดูเป็นศูนย์ข้อมูล)
    ========================================== */
    .hero-dashboard {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #fff;
        padding: 40px 30px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .hero-dashboard::after {
        content: '\f080';
        /* Icon Chart */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        font-size: 15rem;
        right: -20px;
        bottom: -40px;
        color: rgba(255, 255, 255, 0.03);
        transform: rotate(-15deg);
    }

    /* =========================================
       WIDGETS & CARDS
    ========================================== */
    .info-box-custom {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        height: 100%;
        border-left: 5px solid transparent;
        transition: transform 0.2s;
    }

    .info-box-custom:hover {
        transform: translateY(-3px);
    }

    .info-box-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
    }

    .info-box-content {
        flex: 1;
    }

    .info-box-text {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-box-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
    }

    .info-box-desc {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 5px;
    }

    /* =========================================
       SDG PROGRESS BARS
    ========================================== */
    .sdg-progress-item {
        margin-bottom: 15px;
    }

    .sdg-progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .sdg-progress-bar {
        height: 8px;
        background-color: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }

    .sdg-progress-fill {
        height: 100%;
        border-radius: 4px;
    }

    /* Dashboard Card (General) */
    .dash-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        border: none;
        height: 100%;
    }

    .dash-card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dash-card-title {
        margin: 0;
        font-weight: 700;
        font-size: 1.1rem;
        color: #334155;
    }
</style>

<div class="container-fluid mt-3">

    {{-- =========================================================
         HEADER (Hero Dashboard)
    ========================================================= --}}
    <div class="hero-dashboard">
        {{-- ปุ่มเข้าสู่ระบบ (มุมขวาบน) --}}
        <div class="position-absolute" style="top: 25px; right: 30px; z-index: 10;">
            <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 font-weight-bold" style="border-width: 2px;">
                <i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบสำหรับบุคลากร
            </a>
        </div>

        <h2 class="font-weight-bold mb-2 pr-5">ระบบข้อมูลบริการวิชาการ (Academic Service Public Data)</h2>
        <p class="mb-0 text-light" style="font-size: 1.1rem; opacity: 0.8;">
            รายงานสถิติภาพรวมโครงการและงบประมาณ คณะวิทยาศาสตร์ มหาวิทยาลัยสงขลานครินทร์
        </p>
        <div class="mt-3">
            <span class="badge badge-primary px-3 py-2 mr-2" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                <i class="far fa-calendar-alt mr-1"></i> ปีงบประมาณ 2569
            </span>
            <span class="badge badge-dark px-3 py-2" style="background: rgba(0,0,0,0.3);">
                <i class="fas fa-globe mr-1"></i> Public View
            </span>
        </div>
    </div>

    {{-- =========================================================
         TOP WIDGETS (เน้นงบประมาณ & จำนวนโครงการ)
    ========================================================= --}}
    <div class="row mb-4">
        {{-- Total Budget --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="info-box-custom" style="border-left-color: #10b981;">
                <div class="info-box-icon" style="background: #ecfdf5; color: #10b981;">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="info-box-content">
                    <div class="info-box-text">งบประมาณหมุนเวียนรวม</div>
                    <div class="info-box-number">24.5M <span style="font-size: 1rem; color: #64748b;">บาท</span></div>
                    <div class="info-box-desc"><i class="fas fa-arrow-up text-success"></i> +12% จากปีที่แล้ว</div>
                </div>
            </div>
        </div>

        {{-- Total Projects --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="info-box-custom" style="border-left-color: #3b82f6;">
                <div class="info-box-icon" style="background: #eff6ff; color: #3b82f6;">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="info-box-content">
                    <div class="info-box-text">โครงการบริการวิชาการรวม</div>
                    <div class="info-box-number">128</div>
                    <div class="info-box-desc">ดำเนินการเสร็จสิ้นแล้ว 69 โครงการ</div>
                </div>
            </div>
        </div>

        {{-- Training / Participants --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="info-box-custom" style="border-left-color: #8b5cf6;">
                <div class="info-box-icon" style="background: #f5f3ff; color: #8b5cf6;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info-box-content">
                    <div class="info-box-text">ผู้รับบริการ/เข้าร่วมอบรม</div>
                    <div class="info-box-number">3,240</div>
                    <div class="info-box-desc">จาก 36 หลักสูตรฝึกอบรม</div>
                </div>
            </div>
        </div>

        {{-- Networks / Partners --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="info-box-custom" style="border-left-color: #f59e0b;">
                <div class="info-box-icon" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="info-box-content">
                    <div class="info-box-text">หน่วยงานเครือข่าย/ลูกค้า</div>
                    <div class="info-box-number">85</div>
                    <div class="info-box-desc">ภาครัฐและเอกชน</div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         CHARTS & SDG SECTION
    ========================================================= --}}
    <div class="row mb-4">

        {{-- กราฟโดนัท สัดส่วนโครงการ --}}
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5 class="dash-card-title"><i class="fas fa-chart-pie text-primary mr-2"></i> สัดส่วนโครงการตามประเภท</h5>
                </div>
                <div class="card-body">
                    <canvas id="projectTypeChart" style="height: 250px;"></canvas>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between small mb-2">
                            <span><i class="fas fa-circle text-primary mr-1"></i> บริการวิชาการ</span>
                            <span class="font-weight-bold">42% (54 โครงการ)</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span><i class="fas fa-circle text-info mr-1"></i> ฝึกอบรม</span>
                            <span class="font-weight-bold">28% (36 โครงการ)</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="fas fa-circle text-warning mr-1"></i> สัญญาจ้าง/วิจัย</span>
                            <span class="font-weight-bold">30% (38 โครงการ)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- กราฟแท่ง สถิติงบประมาณรายเดือน --}}
        <div class="col-lg-8">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5 class="dash-card-title"><i class="fas fa-chart-bar text-success mr-2"></i> มูลค่าโครงการบริการวิชาการรายเดือน (ล้านบาท)</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool"><i class="fas fa-download"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="budgetTrendChart" style="height: 310px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         SDG IMPACT & RECENT SUCCESS
    ========================================================= --}}
    <div class="row mb-4">

        {{-- SDG Progress --}}
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5 class="dash-card-title"><i class="fas fa-globe-asia text-success mr-2"></i> การตอบสนองเป้าหมายการพัฒนาที่ยั่งยืน (SDGs)</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-4">สัดส่วนโครงการที่สอดคล้องกับเป้าหมาย SDGs สูงสุด 5 อันดับแรกของคณะ</p>

                    <div class="sdg-progress-item">
                        <div class="sdg-progress-label">
                            <span style="color: #4C9F38;"><i class="fas fa-heartbeat mr-1"></i> SDG 3: สุขภาพและความเป็นอยู่ที่ดี</span>
                            <span>45 โครงการ</span>
                        </div>
                        <div class="sdg-progress-bar">
                            <div class="sdg-progress-fill" style="width: 85%; background-color: #4C9F38;"></div>
                        </div>
                    </div>

                    <div class="sdg-progress-item">
                        <div class="sdg-progress-label">
                            <span style="color: #C5192D;"><i class="fas fa-book-reader mr-1"></i> SDG 4: การศึกษาที่เท่าเทียม</span>
                            <span>38 โครงการ</span>
                        </div>
                        <div class="sdg-progress-bar">
                            <div class="sdg-progress-fill" style="width: 72%; background-color: #C5192D;"></div>
                        </div>
                    </div>

                    <div class="sdg-progress-item">
                        <div class="sdg-progress-label">
                            <span style="color: #FD6925;"><i class="fas fa-industry mr-1"></i> SDG 9: อุตสาหกรรม นวัตกรรม โครงสร้างพื้นฐาน</span>
                            <span>32 โครงการ</span>
                        </div>
                        <div class="sdg-progress-bar">
                            <div class="sdg-progress-fill" style="width: 60%; background-color: #FD6925;"></div>
                        </div>
                    </div>

                    <div class="sdg-progress-item">
                        <div class="sdg-progress-label">
                            <span style="color: #3F7E44;"><i class="fas fa-leaf mr-1"></i> SDG 13: การรับมือการเปลี่ยนแปลงสภาพภูมิอากาศ</span>
                            <span>21 โครงการ</span>
                        </div>
                        <div class="sdg-progress-bar">
                            <div class="sdg-progress-fill" style="width: 40%; background-color: #3F7E44;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Success Projects (แบบกะทัดรัด ไม่มีข้อมูลส่วนบุคคล) --}}
        <div class="col-lg-6">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5 class="dash-card-title"><i class="fas fa-check-circle text-success mr-2"></i> โครงการที่ดำเนินการเสร็จสิ้นล่าสุด</h5>
                    <a href="#" class="small">ดูทั้งหมด</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="border-0">ประเภท</th>
                                <th class="border-0">ชื่อโครงการ</th>
                                <th class="border-0 text-right">ส่งมอบเมื่อ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle"><span class="badge badge-light border text-muted">สัญญาจ้าง</span></td>
                                <td class="align-middle font-weight-bold text-dark">โครงการที่ปรึกษาระบบกำจัดของเสียอุตสาหกรรม</td>
                                <td class="align-middle text-right text-muted small">12 ส.ค. 2569</td>
                            </tr>
                            <tr>
                                <td class="align-middle"><span class="badge badge-light border text-muted">ฝึกอบรม</span></td>
                                <td class="align-middle font-weight-bold text-dark">หลักสูตร Data Analytics for Business</td>
                                <td class="align-middle text-right text-muted small">08 ส.ค. 2569</td>
                            </tr>
                            <tr>
                                <td class="align-middle"><span class="badge badge-light border text-muted">บริการวิชาการ</span></td>
                                <td class="align-middle font-weight-bold text-dark">บริการตรวจวิเคราะห์คุณภาพน้ำบาดาลชุมชน</td>
                                <td class="align-middle text-right text-muted small">01 ส.ค. 2569</td>
                            </tr>
                            <tr>
                                <td class="align-middle"><span class="badge badge-light border text-muted">บริการวิชาการ</span></td>
                                <td class="align-middle font-weight-bold text-dark">การทดสอบคุณสมบัติทางเคมีของยางพารา</td>
                                <td class="align-middle text-right text-muted small">28 ก.ค. 2569</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. กราฟโดนัท สัดส่วนประเภทโครงการ
        var ctxType = document.getElementById('projectTypeChart');
        if (ctxType) {
            new Chart(ctxType, {
                type: 'doughnut',
                data: {
                    labels: ['บริการวิชาการ', 'ฝึกอบรม', 'สัญญาจ้าง/วิจัย'],
                    datasets: [{
                        data: [54, 36, 38],
                        backgroundColor: ['#3b82f6', '#0ea5e9', '#f59e0b'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // 2. กราฟแท่ง มูลค่าโครงการรายเดือน
        var ctxTrend = document.getElementById('budgetTrendChart');
        if (ctxTrend) {
            new Chart(ctxTrend, {
                type: 'bar',
                data: {
                    labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.'],
                    datasets: [{
                        label: 'มูลค่างบประมาณ (ล้านบาท)',
                        data: [2.1, 1.8, 3.2, 2.5, 4.1, 3.8, 4.5, 2.5],
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection