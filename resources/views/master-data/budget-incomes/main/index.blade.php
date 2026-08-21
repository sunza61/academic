@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-folder-open"></i> หมวดหมู่หลักรายรับ</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.budget-incomes.main.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มหมวดหมู่หลัก
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
        <table class="table table-bordered table-hover table-striped" id="mainIncomeTable">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" width="10%">#</th>
                    <th>ชื่อหมวดหมู่หลัก</th>
                    <th class="text-center" width="15%">สถานะ</th>
                    <th class="text-center" width="20%">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mainCategories as $key => $main)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $main->name_th }}</td>
                        <td class="text-center">
                            @if($main->is_active)
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            @else
                                <span class="badge badge-danger">ปิดใช้งาน</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('master-data.budget-incomes.main.edit', $main->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('master-data.budget-incomes.main.destroy', $main->id) }}" method="POST" class="d-inline form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $main->name_th }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">ไม่มีข้อมูลหมวดหมู่หลัก</td>
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
    <script src="{{ asset('js/master-data/budget-incomes/main/index.js?v=' . time()) }}"></script>
@endsection
