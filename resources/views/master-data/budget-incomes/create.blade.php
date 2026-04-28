@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0">เพิ่มข้อมูลหมวดหมู่รายรับ</h3>
    </div>
    <div class="col-sm-6 text-right">
        <a href="{{ route('master-data.budget-incomes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <form action="{{ route('master-data.budget-incomes.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>หมวดหมู่หลัก <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control select2-basic" id="main_category_id" name="main_category_id" required>
                            <option value="">-- เลือกหมวดหมู่หลัก --</option>
                            @foreach($mains as $main)
                                <option value="{{ $main->id }}">{{ $main->name_th }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#modalNewMain">
                                <i class="fas fa-plus"></i> เพิ่มใหม่
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <label>ชื่อหมวดหมู่ย่อย (ภาษาไทย) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name_th" required>
                </div>

                
                <div class="col-md-6 form-group mt-2">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                        <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกข้อมูล</button>
            <a href="{{ route('master-data.budget-incomes.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
    </form>
</div>

<div class="modal fade" id="modalNewMain" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-folder-plus"></i> เพิ่มหมวดหมู่หลักใหม่</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>ชื่อหมวดหมู่หลัก (ภาษาไทย) <span class="text-danger">*</span></label>
                    <input type="text" id="new_main_name" class="form-control" placeholder="เช่น รายได้จากค่าธรรมเนียมการศึกษา">
                </div>
            </div>
            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="btnSaveMain"><i class="fas fa-save"></i> บันทึก</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    
    <script>
        var storeMainAjaxUrl = "{{ route('master-data.budget-incomes.storeMainAjax') }}";
    </script>
    
    <script src="{{ asset('js/master-data/budget-incomes/create.js?v=' . time()) }}"></script>
@endsection