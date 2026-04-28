@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-user-tag"></i> ข้อมูลตำแหน่งในโครงการ (Project Positions)</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.project-positions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มตำแหน่ง
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
            <table class="table table-bordered table-hover table-striped" id="positionTable">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th width="35%">ชื่อตำแหน่ง (ไทย/อังกฤษ)</th>
                        <th width="30%">รายละเอียดเพิ่มเติม</th>
                        <th class="text-center" width="15%">เงื่อนไขเฉพาะ</th>
                        <th class="text-center" width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($positions as $key => $pos)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <strong>{{ $pos->name_th }}</strong>
                                @if($pos->name_en) <br><small class="text-muted">{{ $pos->name_en }}</small> @endif
                            </td>
                            <td>{{ $pos->description ?? '-' }}</td>
                            <td class="text-center">
                                @if($pos->is_unique)
                                    <span class="badge badge-warning text-dark"><i class="fas fa-star"></i> มีได้คนเดียวในโครงการ</span>
                                @else
                                    <span class="badge badge-secondary">ทั่วไป</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('master-data.project-positions.edit', $pos->id) }}" class="btn btn-sm btn-warning" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('master-data.project-positions.destroy', $pos->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $pos->name_th }}" title="ลบข้อมูล">
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
    <script src="{{ asset('js/master-data/project-positions/index.js?v=' . time()) }}"></script>
@endsection