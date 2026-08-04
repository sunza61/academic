@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-file-invoice-dollar"></i> หมวดหมู่รายจ่าย (Budget Expenses)</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.budget-expenses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มหมวดหมู่รายจ่าย
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
            <table class="table table-bordered table-hover table-striped" id="expenseTable">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th width="35%">หมวดหมู่หลัก (Main Category)</th>
                        <th width="35%">หมวดหมู่ย่อย (Sub Category)</th>
                        <th class="text-center" width="10%">สถานะ</th>
                        <th class="text-center" width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $key => $expense)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <span class="text-muted"><i class="fas fa-folder-open"></i> {{ $expense->mainCategory->name_th ?? 'ไม่พบข้อมูลหลัก' }}</span>
                            </td>
                            <td><strong>{{ $expense->name_th }}</strong></td>
                            <td class="text-center">
                                @if($expense->is_active)
                                    <span class="badge badge-success">เปิดใช้งาน</span>
                                @else
                                    <span class="badge badge-danger">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('master-data.budget-expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning" title="แก้ไข"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('master-data.budget-expenses.destroy', $expense->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $expense->name_th }}" title="ลบข้อมูล"><i class="fas fa-trash"></i></button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/master-data/budget-expenses/index.js?v=' . time()) }}"></script>
@endsection