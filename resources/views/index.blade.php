@extends('layouts.main_all')
@section('content')

    <!-- ชื่อหัวข้อระบบงาน -->
    <div class="row">
        <div class="col"></div>
        <div class="row justify-content-center">
            <h2>ทดสอบ Master</h2>
        </div>
        <div class="col"></div>
    </div>
    <br>
    <!-- /.ชื่อหัวข้อระบบงาน -->
    <!-- แสดงช่องสถานะ -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">รอดำเนินการ</span>
                    <span class="info-box-number">xxxxxx</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box md-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-tools"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">อยู่ระหว่างดำเนิน</span>
                    <span class="info-box-number">xxxxxxxx</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box md-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">ดำเนินการเสร็จสิ้น</span>
                    <span class="info-box-number">xxxxxxxx</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box md-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-calendar-week"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">รอผลประเมิน</span>
                    <span class="info-box-number">xxxxxxxx</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /.แสดงช่องสถานะ --> 
    <!-- แสดง data tabel -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-titel">รายการแจ้งซ่อมทั้งหมด</h4>
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="example1_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="number">เลขที่</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="nmae">ผู้แจ้ง</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="date">วันที่แจ้ง</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="subject">เรื่องที่แจ้ง</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="operator">ผู้ดำเนินการ</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="status">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr role="row" class="odd">
                                    <td tabindex="0" class="sorting_1">A001</td>
                                    <td>สมชาย บุญมา</td>
                                    <td>4/11/2020 09.00น.</td>
                                    <td>กล้องจุลทัศเลนส์แตก</td>
                                    <td>-</td>
                                    <td>รอดำเนินการ</td>
                                </tr>
                                <tr role="row" class="even">
                                    <td tabindex="0" class="sorting_1">A002</td>
                                    <td>สมหญิง  ใจจริง</td>
                                    <td>2/11/2020 09.10น.</td>
                                    <td>เครื่อง UV ดับ ไฟไม่เข้า</td>
                                    <td>กรุงประกาย อัมรินทร์</td>
                                    <td>อยู่ระหว่างดำเนินการ</td>
                                </tr>
                                <tr role="row" class="odd">
                                    <td tabindex="0" class="sorting_1">A003</td>
                                    <td>สมบูรณ์  กินอิ่ม</td>
                                    <td>1/11/2020 09.30น.</td>
                                    <td>เครื่องวัดอุณหภูมิเสีย</td>
                                    <td>อัมรินทร์</td>
                                    <td>อยู่ระหว่างดำเนินการ</td>
                                </tr>
                                <tr role="row" class="even">
                                    <td tabindex="0" class="sorting_1">A004</td>
                                    <td>ทองดี  ทองเค</td>
                                    <td>1/11/2020 08.30น.</td>
                                    <td>เครื่องสกัด DNA เปิดไม่ติด</td>
                                    <td>เศียร</td>
                                    <td>ดำเนินการเสร็จ</td>
                                </tr>
                            </tbody>
                            <!-- ท้ายตาราง 
                            <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">เลขที่</th>
                                    <th rowspan="1" colspan="1">ชื่อผู้แจ้ง</th>
                                    <th rowspan="1" colspan="1">วันที่แจ้ง</th>
                                    <th rowspan="1" colspan="1">เรื่องที่แจ้ง</th>
                                    <th rowspan="1" colspan="1">ผู้ดำเนินการ</th>
                                    <th rowspan="1" colspan="1">สถานะ</th>
                                </tr>
                            </tfoot>
                            -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> 

@endsection