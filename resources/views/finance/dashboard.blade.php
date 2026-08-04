@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-file-invoice-dollar text-primary"></i> แดชบอร์ดเจ้าหน้าที่การเงิน (Finance)</h3>
    </div>
    <div class="col-sm-6 text-right">
        {{-- นำชื่อเจ้าหน้าที่ออกตามคำขอ --}}
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row mt-3">
    <div class="col-md-3">
        <div class="small-box bg-success shadow-sm">
            <div class="inner">
                <h3>{{ $projects->count() }}</h3>
                <p>โครงการที่เสร็จสิ้นแล้ว</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-double"></i>
            </div>
            <a href="#" class="small-box-footer">ดูข้อมูลเพิ่มเติม <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold">รายการโครงการที่{{ $statusName }} (รอตรวจสอบงบประมาณสรุป)</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="financeTable">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th width="12%">รหัสโครงการ</th>
                        <th width="33%">ชื่อโครงการ</th>
                        <th class="text-right" width="10%">รายรับ</th>
                        <th class="text-right" width="10%">รายจ่าย</th>
                        <th class="text-right" width="10%">คงเหลือสุทธิ</th>
                        <th class="text-center" width="10%">สถานะ</th>
                        <th class="text-center" width="10%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $key => $project)
                        @php
                            $income = $project->total_income ?? 0;
                            $expense = $project->total_expense ?? 0;
                            $balance = $income - $expense;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td><span class="badge badge-light border">{{ $project->id }}</span></td>
                            <td>
                                <strong>{{ $project->name_th }}</strong>
                            </td>
                            <td class="text-right text-success font-weight-bold">
                                {{ number_format($income, 2) }}
                            </td>
                            <td class="text-right text-danger font-weight-bold">
                                {{ number_format($expense, 2) }}
                            </td>
                            <td class="text-right font-weight-bold {{ $balance >= 0 ? 'text-primary' : 'text-danger' }}">
                                {{ number_format($balance, 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">{{ $statusName }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('trainings.projects.show', $project->id) }}" class="btn btn-sm btn-primary" title="ตรวจสอบรายละเอียด">
                                    <i class="fas fa-search"></i> ตรวจสอบงบ
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/finance/dashboard.js?v=' . time()) }}"></script>
@endsection
