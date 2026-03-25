@extends('layouts.main_all')

@section('content')
<div class="row mb-3">
    <div class="col-sm-6 text-left mt-2">
        <a href="{{ route('projects.select-type') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
        <h3 class="m-0 font-weight-bold">รายการโครงการ: <span class="text-primary">{{ $projectType->name_th }}</span></h3>
    </div>
    <div class="col-sm-6 text-right mt-4">
        <a href="{{ route('trainings.projects.create', ['type_id' => $projectType->id]) }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle"></i> เพิ่มโครงการ{{ $projectType->name_th }}
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-custom-dark text-white">
        <h3 class="card-title">ประวัติการสร้างโครงการของคุณ</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="text-center bg-light">
                    <th width="5%">ลำดับ</th>
                    <th>ชื่อโครงการ (TH)</th>
                    <th width="15%">วันที่เริ่ม - สิ้นสุด</th>
                    <th width="15%">สถานะภาพรวม</th>
                    <th width="15%">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $key => $item)
                <tr>
                    <td class="text-center align-middle">{{ $key + 1 }}</td>
                    <td class="align-middle">
                        <strong>{{ $item->name_th ?? 'ยังไม่ระบุชื่อโครงการ' }}</strong>
                    </td>
                    <td class="text-center align-middle text-sm">
                        @if($item->start_date && $item->end_date)
                        {{ \Carbon\Carbon::parse($item->start_date)->addYears(543)->format('d/m/Y') }} <br>ถึง<br>
                        {{ \Carbon\Carbon::parse($item->end_date)->addYears(543)->format('d/m/Y') }}
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    
                    <td class="text-center align-middle">
                        @if($item->overall_status == 100)
                            <span class="badge badge-secondary px-2 py-1"><i class="fas fa-edit"></i> เตรียมการ / ฉบับร่าง</span>
                        @elseif($item->overall_status == 200)
                            <span class="badge badge-warning px-2 py-1"><i class="fas fa-hourglass-half"></i> เสนอขออนุมัติ</span>
                        @elseif($item->overall_status == 300)
                            <span class="badge badge-info px-2 py-1"><i class="fas fa-check"></i> อนุมัติแล้ว</span>
                        @elseif($item->overall_status == 400)
                            <span class="badge badge-primary px-2 py-1"><i class="fas fa-bullhorn"></i> เปิดรับสมัคร</span>
                        @elseif($item->overall_status == 500)
                            <span class="badge badge-dark px-2 py-1"><i class="fas fa-door-closed"></i> ปิดรับสมัคร</span>
                        @elseif($item->overall_status == 600)
                            <span class="badge" style="background-color: #fd7e14; color: white;"><i class="fas fa-spinner fa-spin"></i> กำลังดำเนินการ</span>
                        @elseif($item->overall_status == 700)
                            <span class="badge badge-info px-2 py-1"><i class="fas fa-clipboard-list"></i> รอประเมินผล</span>
                        @elseif($item->overall_status == 800)
                            <span class="badge badge-success px-2 py-1"><i class="fas fa-flag-checkered"></i> เสร็จสิ้นโครงการ</span>
                        @elseif($item->overall_status == 900)
                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> ยกเลิกโครงการ</span>
                        @else
                            <span class="badge badge-light px-2 py-1">ไม่ทราบสถานะ</span>
                        @endif
                    </td>

                    <td class="text-center align-middle">
                        <a href="{{ route('trainings.projects.show', $item->id) }}" class="btn btn-sm btn-info shadow-sm mb-1" title="ดูรายละเอียด">
                            <i class="fas fa-search"></i>
                        </a>
                        
                        <a href="{{ route('trainings.projects.edit', $item->id) }}" class="btn btn-sm btn-warning shadow-sm mb-1" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form action="{{ route('trainings.projects.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm shadow-sm mb-1 btn-delete-project" title="ลบ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @if($item->overall_status >= 300 && $item->overall_status < 800)
                            <button type="button" class="btn btn-warning btn-sm shadow-sm mb-1 btn-cancel-project" 
                                data-id="{{ $item->id }}" 
                                data-name="{{ $item->name_th }}"
                                title="ยกเลิกโครงการ">
                                <i class="fas fa-ban"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>
                        คุณยังไม่มีประวัติการสร้างโครงการประเภทนี้<br>
                        คลิกที่ปุ่ม <strong>"เพิ่มโครงการ"</strong> เพื่อเริ่มต้นใช้งาน
                    </td>
                </tr>
                @endforelse
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
@endsection

@section('script')
<script src="{{ asset('js/custom-crud.js') }}?v={{ time() }}"></script>
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
    $(document).ready(function() {
        
        // 1. เมื่อกดปุ่มยกเลิกโครงการ (ดึง ID และ ชื่อ มาใส่ Modal)
        $('.btn-cancel-project').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            
            $('#cancel_project_id').val(id);
            $('#cancel_project_name').text(name);
            $('#cancel_reason').val(''); // เคลียร์ข้อความเก่าทิ้ง
            
            $('#modalCancelProject').modal('show');
        });

        // 2. เมื่อกดยืนยันใน Modal
        $('#btn-submit-cancel').click(function() {
            let id = $('#cancel_project_id').val();
            let reason = $('#cancel_reason').val();

            if(reason.trim() === '') {
                Swal.fire({
                    icon: 'warning', 
                    title: 'ข้อมูลไม่ครบ', 
                    text: 'กรุณาระบุเหตุผลการยกเลิกโครงการด้วยครับ'
                });
                return;
            }

            let btn = $(this);
            let originalText = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> กำลังประมวลผล...').prop('disabled', true);

            $.ajax({
                url: "{{ url('trainings/projects') }}/" + id + "/cancel",
                type: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    cancel_reason: reason
                },
                success: function(response) {
                    if(response.success) {
                        $('#modalCancelProject').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'ยกเลิกสำเร็จ!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // รีเฟรชหน้าให้ตารางอัปเดตเป็น Status 900
                        });
                    }
                },
                error: function(xhr) {
                    btn.html(originalText).prop('disabled', false);
                    let errMsg = 'เกิดข้อผิดพลาดในการยกเลิก';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error', 
                        title: 'ผิดพลาด', 
                        text: errMsg
                    });
                }
            });
        });

    });
</script>
@endsection