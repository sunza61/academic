@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-sitemap"></i> ข้อมูลกลุ่มเป้าหมาย (Target Groups)</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.target-groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มกลุ่มเป้าหมาย
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">รายการกลุ่มเป้าหมายทั้งหมด</h3>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="targetGroupTable">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th width="45%">เส้นทางกลุ่มเป้าหมาย (Hierarchy Path)</th>
                        <th width="15%">ประเภท (Type)</th>
                        <th class="text-center" width="10%">ระดับ</th>
                        <th class="text-center" width="10%">สถานะ</th>
                        <th class="text-center" width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($targetGroups as $key => $group)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <span class="text-muted">{{ $group->parent_id ? $group->parent->full_path . ' > ' : '' }}</span>
                                <strong class="text-primary">{{ $group->name_th }}</strong>
                            </td>
                            <td>
                                @if($group->group_type)
                                    <span class="badge badge-info">{{ $group->group_type }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-secondary">Level {{ $group->level }}</span>
                            </td>
                            <td class="text-center">
                                @if($group->is_active)
                                    <span class="badge badge-success">เปิดใช้งาน</span>
                                @else
                                    <span class="badge badge-danger">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('master-data.target-groups.edit', $group->id) }}" class="btn btn-sm btn-warning" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('master-data.target-groups.destroy', $group->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    
                                    @php
                                        $fullPath = ($group->parent_id ? $group->parent->full_path . ' > ' : '') . $group->name_th;
                                    @endphp
                                    
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $fullPath }}" title="ลบข้อมูล">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>
                                ยังไม่มีข้อมูลกลุ่มเป้าหมายในระบบ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
   
    
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
 

    <script src="{{ asset('js/master-data/target-groups/index.js?v=' . time()) }}"></script>
@endsection