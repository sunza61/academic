@extends('layouts.main_all')
@section('style')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark font-weight-bold">
                        <i class="fas fa-clipboard-check text-primary mr-2"></i> พิจารณาอนุมัติโครงการ
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif

            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title text-dark font-weight-bold">
                        <i class="fas fa-list text-muted mr-1"></i> รายการโครงการที่รอการพิจารณา <span class="badge badge-warning ml-1">สถานะ 200</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="approvalTable" class="table table-bordered table-striped table-hover mb-0">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th width="5%">ลำดับ</th>
                                    <th width="10%">รหัส</th>
                                    <th width="30%">ชื่อโครงการ</th>
                                    <th width="15%">ประเภทโครงการ</th>
                                    <th width="15%">ผู้ขออนุมัติ</th>
                                    <th width="10%">วันที่ส่งมา</th>
                                    <th width="15%">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $index => $p)
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td class="text-center align-middle font-weight-bold text-success">{{ $p->id }}</td>
                                    <td class="align-middle text-primary font-weight-bold">{{ $p->name_th }}</td>
                                    <td class="text-center align-middle">{{ $p->project_type_name ?? '-' }}</td>
                                    <td class="align-middle">
                                        <i class="fas fa-user text-muted mr-1"></i> {{ $p->creator_name ?? 'ไม่ระบุ' }}
                                    </td>
                                    <td class="text-center align-middle text-muted">
                                        {{ \Carbon\Carbon::parse($p->updated_at)->addYears(543)->format('d/m/Y') }}<br>
                                        <small>{{ \Carbon\Carbon::parse($p->updated_at)->format('H:i') }} น.</small>
                                    </td>
                                    <td class="text-center align-middle text-nowrap">
                                        
                                        <a href="{{ route('trainings.projects.show', ['project' => $p->id, 'from' => 'approvals']) }}" class="btn btn-sm btn-info mr-1" data-toggle="tooltip" title="ดูรายละเอียด">
                                            <i class="fas fa-search"></i>
                                        </a>

                                        <form action="{{ route('admin.approvals.approve', $p->id) }}" method="POST" class="d-inline form-approve">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-sm btn-success btn-approve-project mr-1" data-name="{{ $p->name_th }}" data-toggle="tooltip" title="อนุมัติโครงการ">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger btn-reject-project" 
                                            data-id="{{ $p->id }}" 
                                            data-name="{{ $p->name_th }}" 
                                            data-toggle="tooltip" title="ตีกลับให้แก้ไข">
                                            <i class="fas fa-reply"></i>
                                        </button>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-reply mr-2"></i> ตีกลับโครงการ (แก้ไข)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formReject" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-body bg-light">
                    <div class="form-group mb-3">
                        <label class="text-muted mb-1">โครงการที่ต้องการตีกลับ:</label>
                        <div id="rejectProjectName" class="font-weight-bold text-dark" style="font-size: 1.1em; border-left: 4px solid #dc3545; padding-left: 10px;"></div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-danger">ระบุเหตุผลที่ตีกลับ <span class="text-danger">*</span></label>
                        <textarea name="reject_reason" id="reject_reason" class="form-control border-danger" rows="4" placeholder="เช่น กรุณาแก้ไขขอบเขตงบประมาณในส่วนที่ 4..." required></textarea>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle"></i> เหตุผลนี้จะถูกบันทึกในประวัติ และส่งกลับไปให้ผู้ขออนุมัติอ่านครับ
                        </small>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-danger shadow-sm" id="btnConfirmReject">
                        <i class="fas fa-paper-plane mr-1"></i> ยืนยันการตีกลับ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/custom-crud.js') }}?v={{ time() }}"></script>
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script>
    window.ROUTES = {
        rejectBaseUrl: "{{ route('admin.approvals.reject', ':id') }}"
    };
</script>

<script src="{{ asset('js/admin/approvals/index.js?v=' . time()) }}"></script>
@endsection