@extends('layouts.main_all')
@section('content')
<style>
  .stepwizard-step p {
    margin-top: 0px;
    color: #666;
  }

  .stepwizard-row {
    display: table-row;
  }

  .stepwizard {
    display: table;
    width: 100%;
    position: relative;
  }

  .stepwizard-step button[enabled] {
    /*opacity: 1 !important;
    filter: alpha(opacity=100) !important;*/
  }

  .stepwizard .btn.disabled,
  .stepwizard .btn[disabled],
  .stepwizard fieldset[disabled] .btn {
    opacity: 1 !important;
    color: #bbb;
  }

  .stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-index: 0;
  }

  .stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
  }

  .btn-circle {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 6px 0;
    font-size: 12px;
    line-height: 1.428571429;
    border-radius: 15px;
  }

  .modal {
    overflow-y: auto;
  }
</style>
<style>
  .modal {
    overflow-y: auto;
  }
</style>
<style>
  .container {
    max-width: 500px;
  }

  dl,
  ol,
  ul {
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .imgPreview img {
    padding: 8px;
    max-width: 100px;
  }
</style>

<!-- ชื่อหัวข้อระบบงาน -->
<div class="row">
  <div class="col-5">
    <h2>แจ้งซ่อมเครื่องมือวิทยาศาสตร์</h2>
  </div>
  <div class="col"></div>
  <div class="col"></div>
</div>
<div class="stepwizard">
  <div class="stepwizard-row setup-panel">
    <div class="stepwizard-step col-xs-3">
      <a href="" type="button" class="btn btn-default btn-circle">1</a>
      <p><small>ระบุหมายเลขครุภัณฑ์</small></p>
    </div>
    <div class="stepwizard-step col-xs-3">
      <a href="" type="button" class="btn btn-success btn-circle">2</a>
      <p><small>ระบุรายละเอียด</small></p>
    </div>
  </div>
</div>
<br>

<form action="{{route('addrepair_cer')}}" method="post" enctype="multipart/form-data">
  @csrf
  <?php
  date_default_timezone_set("Asia/Bangkok");
  ?>
  <input name="noti_personid" type="hidden" value="{{ Auth::user()->personid }}">
  <input type="hidden" id="time" name="noti_date" value="{{ now() }}">
  <input name="equipment_key" type="hidden" value="{{ $data->EQUIPMENT_KEY }}">
  <input name="status_repair" type="hidden" value="{{ '1' }}">


  <div class="panel panel-primary setup-content">
    <div class="panel-heading">
      <h3 class="panel-title">ระบุรายละเอียด</h3>
    </div>
    <br>
    <div class="panel-body">
      <div class="form-row">
        <div class="form-group col-md-8">
          <label class="control-label">หมายเลขครุภัณฑ์</label>
          <input maxlength="200" type="text" required="required" name="equipment_id" value="{{$data->EQUIPMENT_ID}}" class="form-control" placeholder="" readonly />
        </div>
        <div class="form-group col-md-2">
          <label for="inputZip">ประวัติการซ่อม</label>

          <!-- Button trigger modal -->
          <button type="button" class="btn btn-block btn-info" data-toggle="modal" style="width:80px;height:40px" data-target="#myModal">ดู</button>

        </div>
        <div class="form-group col-md-2">

        </div>
      </div>

      <div class="form-group">
        <label class="control-label">ชื่อรายการ</label>
        <input maxlength="300" type="text" required="required" class="form-control" name="specification" value="{{$data->SPECIFICATION}}" placeholder="" readonly />
      </div>
      <div class="form-group">
        <label class="control-label">ยี่ห้อ - รุ่น</label>
        <input maxlength="300" type="text" required="required" name="brand_name" value="{{$data->BRAND_NAME}}" class="form-control" placeholder="" readonly />
      </div>
      <div class="form-group">
        <label class="control-label">เรื่องที่แจ้งซ่อม</label>
        <input maxlength="300" type="text" required="required" name="subject" value="" class="form-control" placeholder="" />
      </div>
      <div class="form-group">
        <label class="control-label">อาการเสีย</label>
        <textarea class="form-control" type="text" required="required" name="symptom" value="" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label class="control-label">สถานที่ใช้</label>
        <input maxlength="300" type="text" required="required" name="place" value="" class="form-control" placeholder="" />
      </div>
      <div class="form-group">
        <label class="control-label">เบอร์โทรติดต่อ</label>
        <input maxlength="300" type="text" required="required" name="phone_number" value="" class="form-control" placeholder="" />
      </div>
      <div class="form-group">
        <label class="control-label">แนบไฟล์/รูปภาพปัญหา</label>
        <div class="input-group">
          <div class="custom-file">
            <input type="file" name="imageFile[]" class="custom-file-input" value="" id="images" multiple="multiple">
            <label class="custom-file-label" for="images">Choose image</label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label"></label>

        <div class="user-image mb-3 text-center">
          <div class="imgPreview"> </div>
        </div>
      </div>
    </div>
    <button class="btn btn-success pull-right" type="submit">ส่ง</button>
  </div>

</form>
<br><br>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">ประวัติการแจ้งซ่อมครุภัณฑ์</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="control-label">หมายเลขครุภัณฑ์</label>
          <input maxlength="300" type="text" required="required" name="EQUIPMENT_ID" value="{{$data->EQUIPMENT_ID}}" class="form-control" placeholder="" readonly />
        </div>
        <div class="form-group">
          <label class="control-label">ชื่อรายการ</label>
          <input maxlength="300" type="text" required="required" name="EQUIPMENT_ID" value="{{$data->SPECIFICATION}}" class="form-control" placeholder="" readonly />
        </div>
        <div class="form-group">
          <label class="control-label">ยี่ห้อ - รุ่น</label>
          <input maxlength="300" type="text" required="required" name="EQUIPMENT_ID" value="{{$data->BRAND_NAME}}" class="form-control" placeholder="" readonly />
        </div>
      </div>
      <br>
      <div class="card">
        <div class="card-body">
          <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
              <div class="col-sm-12">
                <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="tabledata_history">
                  <thead>
                    <tr role="row">
                      <th class="sorting_asc" tabindex="0" aria-controls="tabledata_history" rowspan="1" colspan="1" aria-sort="ascending" aria-label="number">เลขที่</th>
                      <th class="sorting" tabindex="0" aria-controls="tabledata_history" rowspan="1" colspan="1" aria-label="nmae">ผู้แจ้ง</th>
                      <th class="sorting" tabindex="0" aria-controls="tabledata_history" rowspan="1" colspan="1" aria-label="date">วันที่แจ้ง</th>
                      <th class="sorting" tabindex="0" aria-controls="tabledata_history" rowspan="1" colspan="1" aria-label="subject">อาการ</th>
                      <th class="sorting" tabindex="0" aria-controls="tabledata_history" rowspan="1" colspan="1" aria-label="operator">ผู้ดำเนินการ</th>
                      <th class="sorting" tabindex="0" aria-controls="tabledata_history" rowspan="1" colspan="1" aria-label="status">วิธีแก้ไข</th>
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
                      <td>สมหญิง ใจจริง</td>
                      <td>2/11/2020 09.10น.</td>
                      <td>เครื่อง UV ดับ ไฟไม่เข้า</td>
                      <td>กรุงประกาย อัมรินทร์</td>
                      <td>อยู่ระหว่างดำเนินการ</td>
                    </tr>
                    <tr role="row" class="odd">
                      <td tabindex="0" class="sorting_1">A003</td>
                      <td>สมบูรณ์ กินอิ่ม</td>
                      <td>1/11/2020 09.30น.</td>
                      <td>เครื่องวัดอุณหภูมิเสีย</td>
                      <td>อัมรินทร์</td>
                      <td>อยู่ระหว่างดำเนินการ</td>
                    </tr>
                    <tr role="row" class="even">
                      <td tabindex="0" class="sorting_1">A004</td>
                      <td>ทองดี ทองเค</td>
                      <td>1/11/2020 08.30น.</td>
                      <td>เครื่องสกัด DNA เปิดไม่ติด</td>
                      <td>เศียร</td>
                      <td>ดำเนินการเสร็จ</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>


@endsection
@section('script')
<script>
  $(function() {
    $("#autocomplete-5").autocomplete({
      source: "http://172.28.80.250/sun/ldap/public/autocomplete",
      minLength: 2
    });
  });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(function() {
        // Multiple images preview with JavaScript
        var multiImgPreview = function(input, imgPreviewPlaceholder) {

            if (input.files) {
                var filesAmount = input.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function(event) {
                        $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
                    }

                    reader.readAsDataURL(input.files[i]);
                }
            }

        };

        $('#images').on('change', function() {
            multiImgPreview(this, 'div.imgPreview');
        });
        });    
    </script>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
@endsection