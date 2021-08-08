@extends('layouts.main_all')

@section('content')



<div class="card-body">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif

    <!-- ชื่อหัวข้อระบบงาน -->
    <div class="row">
        <div class="row justify-content-center">
            <h2>รับมอบหมายงานคำขอแจ้งซ่อมเครื่องมือวิทยาศาสตร์</h2>
        </div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
    <br>
    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="Getjob" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="Getjob_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="Getjob" rowspan="1" colspan="1" aria-sort="ascending" aria-label="number">เลขที่</th>
                                    <th class="sorting" tabindex="0" aria-controls="Getjob" rowspan="1" colspan="1" aria-label="status">สถานะ</th>
                                    <th class="sorting" tabindex="0" aria-controls="Getjob" rowspan="1" colspan="1" aria-label="subject">เรื่องที่แจ้ง</th>
                                    <th class="sorting" tabindex="0" aria-controls="Getjob" rowspan="1" colspan="1" aria-label="date">วันที่แจ้ง</th>
                                    <th class="sorting" tabindex="0" aria-controls="Getjob" rowspan="1" colspan="1" aria-label="status">รับมอบหมายงาน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr role="row" class="odd">
                                    <td tabindex="0" class="sorting_1">A001</td>
                                    <td>สมชาย บุญมา</td>
                                    <td>4/11/2020 09.00น.</td>
                                    <td>กล้องจุลทัศเลนส์แตก</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button type="button" class="btn btn-block btn-info" data-toggle="modal" style="width:120px;height:40px" data-target="#ModalGetjob">รับมอบหมาย</button>
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
    </div>


    <!-- Modal GetJob-->
    <div class="modal fade" id="ModalGetjob" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-xl" role="document">
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
                                    <div class="form-group row">
                                        <table>
                                            <tr>
                                                <td>xxxxxxxxxxxxxxxxxx</td>
                                            </tr>
                                            <tr>
                                                <td>xxxxxxxxxxxxxxxxxx</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-info">รับมอบหมายงาน</button>
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
</div>

@endsection