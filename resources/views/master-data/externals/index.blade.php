@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-users"></i> ข้อมูลบุคคลภายนอก (Externals)</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.externals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มบุคคลภายนอก
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
            <table class="table table-bordered table-hover table-striped" id="externalTable">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th width="25%">ชื่อ - นามสกุล</th>
                        <th width="20%">หน่วยงาน/สังกัด</th>
                        <th width="20%">ติดต่อ (โทร/อีเมล)</th>
                        <th class="text-center" width="10%">สถานะ</th>
                        <th class="text-center" width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($externals as $key => $ext)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <strong>{{ $ext->prefix->name_th ?? '' }}{{ $ext->firstname }} {{ $ext->lastname }}</strong>
                            </td>
                            <td>{{ $ext->department }}</td>
                            <td>
                                @if($ext->phone) <i class="fas fa-phone text-muted"></i> {{ $ext->phone }}<br> @endif
                                @if($ext->email) <i class="fas fa-envelope text-muted"></i> {{ $ext->email }} @endif
                            </td>
                            <td class="text-center">
                                @if($ext->is_active)
                                    <span class="badge badge-success">เปิดใช้งาน</span>
                                @else
                                    <span class="badge badge-danger">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('master-data.externals.edit', $ext->id) }}" class="btn btn-sm btn-warning" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('master-data.externals.destroy', $ext->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $ext->firstname }} {{ $ext->lastname }}" title="ลบข้อมูล">
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
    <script src="{{ asset('js/master-data/externals/index.js?v=' . time()) }}"></script>
@endsection