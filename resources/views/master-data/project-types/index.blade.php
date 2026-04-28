@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">ข้อมูลประเภทโครงการ</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.project-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มประเภทโครงการ
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">รายการประเภทโครงการทั้งหมด</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="text-center">
                    <th width="5%">ลำดับ</th>
                    <th>ชื่อประเภทโครงการ</th>
                    <th width="10%">สถานะ</th>
                    <th width="20%">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projectTypes as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->name_th }}</td>
                    <td class="text-center align-middle">
                        @if($item->is_active)
                            <span class="badge badge-success px-2 py-1">เปิดใช้งาน</span>
                        @else
                            <span class="badge badge-secondary px-2 py-1">ปิดใช้งาน</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('master-data.project-types.edit', $item->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> แก้ไข
                        </a>

                        <form action="{{ route('master-data.project-types.destroy',$item->id) }}"
                            method="POST"
                            class="form-delete d-inline">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> ลบ
                            </button>

                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">ไม่มีข้อมูลประเภทโครงการ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
