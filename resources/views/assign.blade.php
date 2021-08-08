@extends('layouts.main_all')

@section('content')


<div class="card-body">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif

    <style>
        .modal {
            overflow-y: auto;
        }
    </style>
    <!-- ชื่อหัวข้อระบบงาน -->
    <div class="row">
    <div class="row justify-content-center">
            <h2>มอบหมายงานคำขอแจ้งซ่อมเครื่องมือวิทยาศาสตร์</h2>
        </div>
        
        <div class="col"></div>
        <div class="col"></div>
    </div>
    <br>
    <br>
    <div class="col-12">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-four-all-tab" data-toggle="pill" href="#custom-tabs-four-all" role="tab" aria-controls="custom-tabs-four-all" aria-selected="true">ทั้งหมด</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-verygood-tab" data-toggle="pill" href="#custom-tabs-four-verygood" role="tab" aria-controls="custom-tabs-four-verygood" aria-selected="false">รอดำเนินการ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-good-tab" data-toggle="pill" href="#custom-tabs-four-good" role="tab" aria-controls="custom-tabs-four-good" aria-selected="false">อยู่ระหว่างดำเนินการ</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-four-all" role="tabpanel" aria-labelledby="custom-tabs-four-all-tab">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="tabledata_rate" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="tabledata_rate_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" aria-controls="tabledata_rate" rowspan="1" colspan="1" aria-sort="ascending" aria-label="number">เลขที่</th>
                                                <th class="sorting" tabindex="0" aria-controls="tabledata_rate" rowspan="1" colspan="1" aria-label="nmae">สถานะ</th>
                                                <th class="sorting" tabindex="0" aria-controls="tabledata_rate" rowspan="1" colspan="1" aria-label="date">เรื่องที่แจ้ง</th>
                                                <th class="sorting" tabindex="0" aria-controls="tabledata_rate" rowspan="1" colspan="1" aria-label="subject">วันที่แจ้ง</th>
                                                <th class="sorting" tabindex="0" aria-controls="tabledata_rate" rowspan="1" colspan="1" aria-label="detail">รายละเอียด</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr role="row" class="odd">
                                                <td tabindex="0" class="sorting_1">A001</td>
                                                <td>สมชาย บุญมา</td>
                                                <td>4/11/2020 09.00น.</td>
                                                <td>กล้องจุลทัศเลนส์แตก</td>

                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <div class="d-flex justify-content-center">
                                                        <button type="button" class="btn btn-block btn-info" data-toggle="modal" style="width:80px;height:40px" data-target="#SendGetJob">ดู</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr role="row" class="even">
                                                <td tabindex="0" class="sorting_1">A002</td>
                                                <td>สมหญิง ใจจริง</td>
                                                <td>2/11/2020 09.10น.</td>
                                                <td>เครื่อง UV ดับ ไฟไม่เข้า</td>

                                                <td></td>

                                            </tr>
                                            <tr role="row" class="odd">
                                                <td tabindex="0" class="sorting_1">A003</td>
                                                <td>สมบูรณ์ กินอิ่ม</td>
                                                <td>1/11/2020 09.30น.</td>
                                                <td>เครื่องวัดอุณหภูมิเสีย</td>

                                                <td></td>

                                            </tr>
                                            <tr role="row" class="even">
                                                <td tabindex="0" class="sorting_1">A004</td>
                                                <td>ทองดี ทองเค</td>
                                                <td>1/11/2020 08.30น.</td>
                                                <td>เครื่องสกัด DNA เปิดไม่ติด</td>

                                                <td></td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-verygood" role="tabpanel" aria-labelledby="custom-tabs-four-verygood-tab">
                        CCCC
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-good" role="tabpanel" aria-labelledby="custom-tabs-four-good-tab">
                        DDDDD
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>


    <!-- Modal SendGetJob-->
    <div class="modal fade" id="SendGetJob" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">รายละเอียดรับมอบหมายงาน</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        เลขที่คำขอ xxxxxxxxx <button type="submit" class="btn btn-default float" data-toggle="modal" style="width:80px;height:40px" data-target="#print">พิมพ์</button>
                        <br><br>
                        <input maxlength="300" type="text" required="required" class="form-control" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" readonly />

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">รายละเอียดครุภัณฑ์</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="place" class="col-sm-2 col-form-label">หมายเลขครุภัณฑ์ :</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="place" placeholder="" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Bad" class="col-sm-2 col-form-label">ชื่อรายการ :</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="place" placeholder="" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">ยี่ห้อ - รุ่น :</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="name" placeholder="" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">รายละเอียดคำขอ</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="place" class="col-sm-2 col-form-label">สถานที่ใช้ :</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="place" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Bad" class="col-sm-2 col-form-label">อาการเสีย :</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" type="text" required="required" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">ชื่อผู้แจ้ง :</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="name" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tel" class="col-sm-2 col-form-label">โทร :</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="tel" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>

                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">เจ้าหน้าที่ดำเนินการ</h3>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <button type="submit" class="btn btn-default float-right" data-toggle="modal" style="width:80px;height:40px" data-target="#addtechnician">เพิ่ม</button>
                                    </div>
                                    <br><br>
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>ที่</th>
                                                        <th>ชื่อช่าง</th>
                                                        <th>ลบ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1.</td>
                                                        <td>ค่าบริการ</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <button type="button" class="btn btn-block btn-danger btn-sm fas fa-trash-alt"></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2.</td>
                                                        <td>150</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <button type="button" class="btn btn-block btn-danger btn-sm fas fa-trash-alt"></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3.</td>
                                                        <td>150</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <button type="button" class="btn btn-block btn-danger btn-sm fas fa-trash-alt"></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4.</td>
                                                        <td>300</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <button type="button" class="btn btn-block btn-danger btn-sm fas fa-trash-alt"></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <!-- /.card-body -->

                            </div>
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">ตารางงานช่าง</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="tel" class="col-sm-2 col-form-label">ช่าง :</label>
                                        <div class="col-sm-10">
                                            <select class="form-control">
                                                <option>option 1</option>
                                                <option>option 2</option>
                                                <option>option 3</option>
                                                <option>option 4</option>
                                                <option>option 5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="tabledata_technician" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="tabledata_technician_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="tabledata_rate" rowspan="1" colspan="1" aria-sort="ascending" aria-label="number">เลขที่</th>
                                                        <th class="sorting" tabindex="0" aria-controls="tabledata_rate" rowspan="1" colspan="1" aria-label="nmae">สถานะ</th>
                                                        <th class="sorting" tabindex="0" aria-controls="tabledata_rate" rowspan="1" colspan="1" aria-label="date">เรื่องที่แจ้ง</th>
                                                        <th class="sorting" tabindex="0" aria-controls="tabledata_rate" rowspan="1" colspan="1" aria-label="subject">วันที่แจ้ง</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr role="row" class="odd">
                                                        <td tabindex="0" class="sorting_1">A001</td>
                                                        <td>สมชาย บุญมา</td>
                                                        <td>4/11/2020 09.00น.</td>
                                                        <td>กล้องจุลทัศเลนส์แตก</td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td tabindex="0" class="sorting_1">A002</td>
                                                        <td>สมหญิง ใจจริง</td>
                                                        <td>2/11/2020 09.10น.</td>
                                                        <td>เครื่อง UV ดับ ไฟไม่เข้า</td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td tabindex="0" class="sorting_1">A003</td>
                                                        <td>สมบูรณ์ กินอิ่ม</td>
                                                        <td>1/11/2020 09.30น.</td>
                                                        <td>เครื่องวัดอุณหภูมิเสีย</td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td tabindex="0" class="sorting_1">A004</td>
                                                        <td>ทองดี ทองเค</td>
                                                        <td>1/11/2020 08.30น.</td>
                                                        <td>เครื่องสกัด DNA เปิดไม่ติด</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-info">บันทึก</button>
                </div>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Print -->

    <div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ใบแจ้งซ่อม</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <br>
                        <div class="d-flex justify-content-center">
                            <h3>ใบแจ้งซ่อม</h3>
                        </div>
                        <br>
                        <div class="d-flex justify-content-center">
                            <h4>งานซ่อมเครื่องมือวิทยาศาสตร์</h4>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5>คณะวิทยาศาสตร์ มหาวิทยาลัยสงขลานครินทร์</h5>
                        </div>
                        <br>
                    </div>
                    <div class="form-group row">
                        <label for="place" class="col-sm-3 col-form-label">เลขที่คำขอ :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="place" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Bad" class="col-sm-3 col-form-label">วันที่แจ้ง :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="place" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Bad" class="col-sm-3 col-form-label">ชื่อผู้แจ้ง :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="place" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Bad" class="col-sm-3 col-form-label">หน่วยงาน :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="place" placeholder="">
                        </div>
                    </div>
                    <br>
                    <label for="Bad" class="col-sm-12 col-form-label"> รายละเอียดครุภัณฑ์</label>
                    <div class="form-group row">
                        <label for="Bad" class="col-sm-3 col-form-label">หมยเลขครุภัณฑ์ :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="place" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Bad" class="col-sm-3 col-form-label">ชื่อรายการ :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="place" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Bad" class="col-sm-3 col-form-label">ยี่ห้อ - รุ่น :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="place" placeholder="">
                        </div>
                    </div>
                    <br>
                    <label for="Bad" class="col-sm-12 col-form-label"> อาการเสีย</label>
                    <div class="col-sm-12">
                        <textarea class="form-control" type="text" required="required" rows="3"></textarea>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="Bad" class="col-sm-3 col-form-label">สถาานที่ใช้ :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="place" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Bad" class="col-sm-3 col-form-label">เบอร์โทรติดต่อ :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="place" placeholder="">
                        </div>
                    </div>
                    <br>
                    <label for="Bad" class="col-sm-12 col-form-label"> การแก้ไข/ข้อเสนอแนะ</label>
                    <div class="col-sm-12">
                        <textarea class="form-control" type="text" required="required" rows="3"></textarea>
                    </div>
                    <br>
                    <br>
                    <label for="Bad" class="col-sm-5 col-form-label"></label>
                    <label for="Bad" class="col-sm-5">ลงชื่อ...........................</label>
                    <label for="Bad" class="col-sm-5 col-form-label"></label>
                    <label for="Bad" class="col-sm-5">(........................................)</label>
                </div>
                <div class="card"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal addtechnician -->

    <div class="modal fade" id="addtechnician" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">เพิ่มช่าง</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>เพิ่มช่าง</h5>
                    <br>
                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ที่</th>
                                        <th>ชื่อช่าง</th>
                                        <th>เพิ่ม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>ค่าบริการ</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-block btn-success btn-sm">เพิ่ม</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>น็อตA</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-block btn-success btn-sm">เพิ่ม</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>น็อตB</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-block btn-success btn-sm">เพิ่ม</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>ค่าบริการอัตราภายนอก</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-block btn-success btn-sm">เพิ่ม</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="card"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection