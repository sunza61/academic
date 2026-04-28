@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-coins"></i> หมวดหมู่รายรับ (Budget Incomes)</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.budget-incomes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มหมวดหมู่รายรับ
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-2">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card shadow-sm border-0 mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="incomeTable">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th width="30%">หมวดหมู่หลัก (Main Category)</th>
                        <th width="30%">หมวดหมู่ย่อย (Sub Category)</th>
                        <th class="text-center" width="10%">เงื่อนไขพิเศษ</th>
                        <th class="text-center" width="10%">สถานะ</th>
                        <th class="text-center" width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incomes as $key => $income)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <span class="text-muted"><i class="fas fa-folder-open"></i> {{ $income->mainCategory->name_th ?? 'ไม่พบข้อมูลหลัก' }}</span>
                            </td>
                            <td>
                                <strong>{{ $income->name_th }}</strong>
                            </td>
                            <td class="text-center">
                                @if($income->is_service_fee)
                                    <span class="badge badge-info" data-toggle="tooltip" title="เป็นรายได้ประเภทค่าบริการวิชาการ"><i class="fas fa-hand-holding-usd"></i> Service Fee</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($income->is_active)
                                    <span class="badge badge-success">เปิดใช้งาน</span>
                                @else
                                    <span class="badge badge-danger">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('master-data.budget-incomes.edit', $income->id) }}" class="btn btn-sm btn-warning" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('master-data.budget-incomes.destroy', $income->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $income->name_th }}" title="ลบข้อมูล">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
    <script src="{{ asset('js/master-data/budget-incomes/index.js?v=' . time()) }}"></script>
@endsection