@extends('layouts.main_all')
@section('style')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-sm-8 text-left mt-2">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fas fa-arrow-left"></i> กลับไปหน้า Dashboard
        </a>
        <h3 class="m-0 font-weight-bold">
            @if(request()->query('status') == 110)
                รายการโครงการทั้งหมดที่ตีกลับ
            @elseif(request()->query('attention') == 1)
                รายการโครงการที่ใกล้ครบกำหนด (ใน 7 วัน)
            @else
                รายการโครงการทั้งหมด
            @endif
        </h3>
    </div>
</div>

{{-- ตัวกรองปีงบประมาณ --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('all-projects.index') }}" method="GET" class="form-inline">
            <input type="hidden" name="status" value="{{ request()->query('status') }}">
            <input type="hidden" name="type_id" value="{{ request()->query('type_id') }}">
            <input type="hidden" name="attention" value="{{ request()->query('attention') }}">
            <label class="mr-2">ปีงบประมาณ:</label>
            <select name="fiscal_year_id" class="form-control mr-2" onchange="this.form.submit()">
                @foreach($fiscalYears as $year)
                    <option value="{{ $year->id }}" {{ $fiscalYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->fiscal_year_be }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">โครงการในปีงบประมาณ</h3>
    </div>
    <div class="card-body">
        <table id="projectTable" class="table table-bordered table-striped table-hover w-100">
            <thead>
                <tr class="text-center bg-light">
                    <th width="5%" class="align-middle">ลำดับ</th>
                    <th width="10%" class="align-middle">รหัสโครงการ</th>
                    <th width="20%" class="align-middle">ชื่อโครงการ (TH)</th>
                    <th width="10%" class="align-middle">ประเภทโครงการ</th>
                    <th width="15%" class="align-middle">วันที่เริ่ม - สิ้นสุด</th>
                    <th width="15%" class="align-middle">ผู้สร้างโครงการ</th>
                    <th width="15%" class="align-middle">สถานะภาพรวม</th>
                    <th width="10%" class="align-middle">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $key => $item)
                <tr>
                    <td class="text-center align-middle">{{ $key + 1 }}</td>

                    <td class="text-center align-middle text-success font-weight-bold">
                        @php
                            $route = ($item->project_type_id == 2) ? 'trainings.projects.show' : 'contracts.projects.show';
                        @endphp
                        <a href="{{ route($route, $item->id) }}" class="text-success" title="ดูรายละเอียดโครงการ">
                            {{ $item->project_code }}
                        </a>
                    </td>

                    <td class="align-middle">
                        <strong>{{ $item->name_th ?? 'ยังไม่ระบุชื่อโครงการ' }}</strong>
                    </td>

                    <td class="text-center align-middle">
                        <span class="badge badge-info">{{ $item->type_name }}</span>
                    </td>

                    <td class="text-center align-middle text-sm">
                        @if($item->start_date && $item->end_date)
                        <span class="text-dark">{{ \Carbon\Carbon::parse($item->start_date)->addYears(543)->format('d/m/Y') }}</span>
                        <span class="text-muted" style="font-size: 0.9em;">ถึง</span>
                        <span class="text-dark">{{ \Carbon\Carbon::parse($item->end_date)->addYears(543)->format('d/m/Y') }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td class="text-center align-middle">
                        <span class="text-muted" style="font-size: 0.95em;">
                            {{ $item->creator_name ?? 'ไม่ระบุ' }}
                        </span>
                    </td>

                    <td class="text-center align-middle">
                        <span class="badge badge-primary">{{ $item->status_name }}</span>
                    </td>
                    
                    <td class="text-center align-middle">
                        <a href="{{ route($route, $item->id) }}" class="btn btn-sm btn-info">ดู</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center align-middle">-</td>
                    <td class="text-center align-middle">-</td>
                    <td class="text-center align-middle" colspan="6">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>
                            ไม่พบข้อมูลโครงการในปีงบประมาณนี้
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/projects/all_index.js?v=' . time()) }}"></script>
@endsection
