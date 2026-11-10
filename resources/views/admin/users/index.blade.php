@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-users-cog"></i> จัดการผู้ใช้งาน (User Management)</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> เพิ่มผู้ใช้งาน
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
            <table class="table table-bordered table-hover table-striped align-middle" id="userTable">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center align-middle" width="5%">#</th>
                        <th class="text-center align-middle" width="22%">ชื่อ - นามสกุล</th>
                        <th class="text-center align-middle" width="12%">รหัสบุคลากร</th>
                        <th class="text-center align-middle" width="15%">Username</th>
                        <th class="text-center align-middle" width="18%">Email</th>
                        <th class="text-center align-middle" width="15%">สิทธิ (Roles)</th>
                        <th class="text-center align-middle" width="13%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                            <td class="align-middle">
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td class="text-center align-middle">
                                {{ $user->personid ?? '-' }}
                            </td>
                            <td class="text-center align-middle">{{ $user->username }}</td>
                            <td class="align-middle">{{ $user->email ?? '-' }}</td>
                            <td class="text-center align-middle">
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge text-white px-2 py-1" style="background-color: #6f4e37; font-size: 0.9rem;">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-secondary px-2 py-1" style="font-size: 0.9rem;">ไม่มีสิทธิ</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="แก้ไขข้อมูล">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $user->name }}" title="ลบผู้ใช้งาน">
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
    <script src="{{ asset('js/admin/users/index.js?v=' . time()) }}"></script>
@endsection
