@extends('layouts.main_all')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-tachometer-alt mr-2"></i> หน้าแรก (Dashboard)</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            @if(auth()->user()->hasRole('admin'))
            <h5 class="mb-3 text-dark font-weight-bold">ภาพรวมระบบสำหรับผู้ดูแล</h5>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm">
                        <div class="inner">
                            <h3>?</h3>
                            <p>โครงการรออนุมัติ (สเตตัส 200)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <a href="{{ route('admin.approvals.index') }}" class="small-box-footer">
                            ไปหน้าพิจารณา <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3>?</h3>
                            <p>โครงการที่กำลังดำเนินการ (600)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-running"></i>
                        </div>
                        <a href="{{ route('trainings.projects.index') }}" class="small-box-footer">
                            ดูรายละเอียด <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info shadow-sm">
                <h5><i class="icon fas fa-info"></i> ยินดีต้อนรับเข้าสู่ระบบจัดการโครงการ</h5>
                คุณสามารถเริ่มต้นใช้งานโดยเลือกเมนูจากแถบด้านซ้ายมือครับ
            </div>
            @endif

        </div>
    </section>
</div>
@endsection