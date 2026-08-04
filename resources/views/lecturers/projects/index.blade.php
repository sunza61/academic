@extends('layouts.main_all')
@section('style')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-sm-6 text-left mt-2">
        <a href="{{ route('projects.select-type') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
        <h3 class="m-0 font-weight-bold">รายการโครงการ: <span class="text-primary">{{ $projectType->name_th }}</span></h3>
    </div>
    <div class="col-sm-6 text-right mt-4">
        <a href="{{ route('lecturers.projects.create', ['type_id' => $projectType->id]) }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle"></i> เพิ่มโครงการ{{ $projectType->name_th }}
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">ประวัติการสร้างโครงการของคุณ</h3>
    </div>
    <div class="card-body">
        <table id="projectTable" class="table table-bordered table-striped table-hover w-100">
            <thead>
                <tr class="text-center bg-light">
                    <th width="5%" class="align-middle">ลำดับ</th>
                    <th width="5%" class="align-middle">รหัสโครงการ</th>
                    <th width="30%" class="align-middle">ชื่อโครงการ (TH)</th>
                    <th width="15%" class="align-middle">วันที่เริ่ม - สิ้นสุด</th>
                    <th width="15%" class="align-middle">ผู้สร้างโครงการ</th>
                    <th width="15%" class="align-middle">สถานะภาพรวม</th>
                    <th width="15%" class="align-middle">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $key => $item)
                <tr>
                    <td class="text-center align-middle">{{ $key + 1 }}</td>

                    <td class="text-center align-middle text-success font-weight-bold">
                        {{ $item->id }}
                    </td>

                    <td class="align-middle">
                        <strong>{{ $item->name_th ?? 'ยังไม่ระบุชื่อโครงการ' }}</strong>
                    </td>

                    <td class="text-center align-middle text-sm">
                        @if($item->start_date && $item->end_date)
                        <span class="text-dark">{{ \Carbon\Carbon::parse($item->start_date)->addYears(543)->format('d/m/Y') }}</span>
                        <span class="text-muted" style="font-size: 0.9em;">ถึง</span>
                        <span class="text-dark">{{ \Carbon\Carbon::parse($item->end_date)->addYears(543)->format('d/m/Y') }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td class="text-center align-middle">
                        <span class="text-muted" style="font-size: 0.95em;">
                            <i class="fas fa-user-circle mr-1 text-info"></i>
                            {{ $item->name ?? 'ไม่ระบุ' }}
                        </span>
                    </td>

                    <td class="text-center align-middle">
                        @if($item->overall_status == 100)
                        <span class="badge badge-secondary px-2 py-1"><i class="fas fa-edit"></i> {{$item->overall_statuses_name_th}}</span>
                        @elseif($item->overall_status == 110)
                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-hourglass-half"></i> {{$item->overall_statuses_name_th}}</span>
                        @elseif($item->overall_status == 200)
                        <span class="badge badge-warning px-2 py-1"><i class="fas fa-hourglass-half"></i> {{$item->overall_statuses_name_th}}</span>
                        @elseif($item->overall_status == 300)
                        <span class="badge badge-info px-2 py-1"><i class="fas fa-check"></i> {{$item->overall_statuses_name_th}}</span>
                        @elseif($item->overall_status == 400)
                        <span class="badge badge-primary px-2 py-1"><i class="fas fa-bullhorn"></i> {{$item->overall_statuses_name_th}}</span>
                        @elseif($item->overall_status == 500)
                        <span class="badge badge-dark px-2 py-1"><i class="fas fa-door-closed"></i> {{$item->overall_statuses_name_th}}</span>
                        @elseif($item->overall_status == 600)
                        <span class="badge" style="background-color: #fd7e14; color: white;"><i class="fas fa-spinner fa-spin"></i> {{$item->overall_statuses_name_th}}</span>
                        @elseif($item->overall_status == 700)
                        <span class="badge badge-info px-2 py-1"><i class="fas fa-clipboard-list"></i> {{$item->overall_statuses_name_th}}</span>
                        @elseif($item->overall_status == 800)
                        <span class="badge badge-success px-2 py-1"><i class="fas fa-flag-checkered"></i> {{$item->overall_statuses_name_th}}</span>
                        @elseif($item->overall_status == 900)
                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> {{$item->overall_statuses_name_th}}</span>
                        @else
                        <span class="badge badge-light px-2 py-1">ไม่ทราบสถานะ</span>
                        @endif
                        @if($item->overall_status == 110 || $item->overall_status == 900)
                        <div class="mt-1">
                            <button type="button" class="btn btn-xs btn-outline-danger btn-view-reason"
                                data-reason="{{ $item->log_reason }}"
                                data-by="{{ $item->log_action_by }}"
                                data-date="{{ $item->log_action_date }}"
                                data-title="{{ $item->overall_status == 110 ? 'เหตุผลการตีกลับ' : 'เหตุผลการยกเลิก' }}">
                                <i class="fas fa-comment-dots"></i> อ่านเหตุผล
                            </button>
                        </div>
                        @endif
                    </td>

                    <td class="text-left align-middle text-nowrap">
                        
                        <a href="{{ route('lecturers.projects.show', $item->id) }}" class="btn btn-sm btn-info mr-1 mb-1" data-toggle="tooltip" title="ดูรายละเอียดโครงการ">
                            <i class="fas fa-search"></i>
                        </a>
                     

                        @if($item->can_edit)
                        <a href="{{ route('lecturers.projects.edit', $item->id) }}" class="btn btn-sm btn-warning mr-1 mb-1" data-toggle="tooltip" title="แก้ไขโครงการ">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif

                        @if($item->can_report)
                        <a href="{{ route('lecturers.projects.report', $item->id) }}" class="btn btn-sm btn-success mr-1 mb-1" data-toggle="tooltip" title="รายงานผลโครงการ/ปิดโครงการ">
                            <i class="fas fa-flag-checkered"></i>
                        </a>
                        @endif

                        @if($item->can_cancel)
                        <button type="button" class="btn btn-sm btn-secondary btn-cancel-project mr-1 mb-1"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->name_th }}"
                            data-toggle="tooltip" title="ยกเลิกโครงการ">
                            <i class="fas fa-ban"></i>
                        </button>
                        @endif

                        @if($item->show_delete_btn)
                        <form action="{{ route('lecturers.projects.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger btn-delete-project mr-1 mb-1"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name_th }}"
                                data-status="{{ $item->overall_status }}"
                                data-is-admin="{{ auth()->user()->hasRole('admin') ? 'true' : 'false' }}"
                                data-toggle="tooltip"
                                title="ลบโครงการ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalCancelProject" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-dark" style="background-color: #ffc107;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> ยืนยันการยกเลิกโครงการ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="formCancelProject">
                    <input type="hidden" id="cancel_project_id">
                    <p class="mb-2" style="font-size: 1.1em;">คุณกำลังจะยกเลิกโครงการ: <br><strong id="cancel_project_name" class="text-danger"></strong></p>

                    <div class="alert alert-danger py-2 px-3 shadow-sm" style="font-size: 0.9em;">
                        <i class="fas fa-info-circle"></i> การยกเลิกโครงการจะไม่สามารถย้อนกลับได้ และจำเป็นต้องระบุเหตุผลเพื่อเก็บเป็นประวัติให้ผู้ตรวจสอบ
                    </div>

                    <div class="form-group mt-3">
                        <label for="cancel_reason" class="font-weight-bold">เหตุผลการยกเลิก <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancel_reason" rows="3" placeholder="ระบุเหตุผล เช่น วิทยากรยกเลิกกะทันหัน, ผู้สมัครไม่ถึงเกณฑ์..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิดหน้าต่าง</button>
                <button type="button" class="btn btn-danger" id="btn-submit-cancel">
                    <i class="fas fa-ban mr-1"></i> ยืนยันยกเลิกโครงการ
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalViewReason" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold" id="reasonTitle">เหตุผลการดำเนินการ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="reason-content-wrapper">
                    <h6 class="text-muted mb-2"><i class="fas fa-info-circle mr-1"></i> รายละเอียดเหตุผล:</h6>
                    <div id="reasonText" class="p-3 border rounded bg-white" style="min-height: 100px; white-space: pre-wrap;">
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="row text-sm text-muted">
                        <div class="col-6">
                            <strong>ดำเนินการโดย:</strong> <br>
                            <span id="reasonBy">-</span>
                        </div>
                        <div class="col-6 text-right">
                            <strong>วันที่ดำเนินการ:</strong> <br>
                            <span id="reasonDate">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
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
        cancelProjectBaseUrl: "{{ url('lecturers/projects') }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>

<script src="{{ asset('js/lecturers/projects/index.js?v=' . time()) }}"></script>
@endsection
