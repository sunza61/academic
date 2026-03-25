@extends('layouts.main_all')

@section('content')
<div class="row mb-3">
    <div class="col-12 text-center mt-4">
        <h2 class="font-weight-bold">สร้างโครงการ / รับงานบริการวิชาการ</h2>
        <p class="text-muted">กรุณาเลือกประเภทโครงการที่คุณต้องการดำเนินการ</p>
    </div>
</div>

<div class="row justify-content-center">
    @forelse($projectTypes as $type)
        <div class="col-md-4 col-sm-6 mb-4">
            <a href="{{ route('projects.gateway', $type->id) }}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm border-0 project-card-hover">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-primary">
                            <i class="fas fa-clipboard-list fa-3x"></i>
                        </div>
                        <h5 class="font-weight-bold">{{ $type->name_th }}</h5>
                        <p class="text-muted small mt-2 mb-0">คลิกเพื่อเข้าสู่หน้าจัดการโครงการประเภทนี้</p>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12 text-center">
            <div class="alert alert-warning shadow-sm">
                <i class="fas fa-exclamation-triangle"></i> ยังไม่มีการตั้งค่าประเภทโครงการในระบบ 
            </div>
        </div>
    @endforelse
</div>

<style>
    /* CSS เล็กน้อยเพื่อเพิ่มลูกเล่นตอนเอาเมาส์ชี้ (Hover Effect) ให้ดูเป็นปุ่มที่กดได้ */
    .project-card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 10px;
        cursor: pointer;
    }
    .project-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        border: 1px solid #007bff !important;
    }
</style>
@endsection
@section('script')
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endsection