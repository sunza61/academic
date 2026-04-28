@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">ตั้งค่า SDGs</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.sdgs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มข้อมูล SDG
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">รายการเป้าหมายการพัฒนาที่ยั่งยืน (SDGs)</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="text-center">
                    <th width="5%">ลำดับ</th>
                    <th width="10%">ไอคอน</th>
                    <th>ชื่อเป้าหมาย (TH/EN)</th>
                    <th width="10%">สถานะ</th>
                    <th width="20%">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sdgs as $key => $item)
                <tr>
                    <td class="text-center align-middle">{{ $key + 1 }}</td>
                    <td class="text-center align-middle">
                        @if($item->icon_url)
                        <img src="{{ $item->icon_url }}" alt="SDG Icon" width="40" height="40" class="img-thumbnail" style="object-fit: contain;">
                        @else
                        <span class="text-muted"><i class="fas fa-image fa-2x"></i></span>
                        @endif
                    </td>
                    <td class="align-middle">
                        <strong>{{ $item->name_th }}</strong><br>
                        <span class="text-muted text-sm">{{ $item->name_en }}</span>
                    </td>
                    <td class="text-center align-middle">
                        @if($item->is_active)
                        <span class="badge badge-success px-2 py-1">เปิดใช้งาน</span>
                        @else
                        <span class="badge badge-secondary px-2 py-1">ปิดใช้งาน</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        <a href="{{ route('master-data.sdgs.edit', $item->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> แก้ไข
                        </a>
                        <form action="{{ route('master-data.sdgs.destroy', $item->id) }}" method="POST" class="form-delete d-inline">
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
                    <td colspan="5" class="text-center text-muted py-4">ไม่มีข้อมูล SDG</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


