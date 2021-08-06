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

  .stepwizard-step button[disabled] {
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
      <a href="" type="button" class="btn btn-success btn-circle">1</a>
      <p><small>ระบุหมายเลขครุภัณฑ์</small></p>
    </div>
    <div class="stepwizard-step col-xs-3">
      <a href="" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
      <p><small>ระบุรายละเอียด</small></p>
    </div>
  </div>
</div>
<br>

<form action="getequipment" method="get">
  <div class="panel panel-primary setup-content">
    <div class="panel-heading">
      <h3 class="panel-title">ระบุหมายเลขครุภัณฑ์</h3>
    </div>
    <div class="panel-body">
      <br>
      
        <div class="form-group">
          <label class="control-label">ค้นหาหมายเลขครุภัณฑ์</label>
          <input maxlength="" name="EQUIPMENT_ID" id="autocomplete-5" type="text" required="required" class="form-control" placeholder="ระบุหมายเลขครุภัณฑ์" />
        </div>

        <button class="btn btn-primary nextBtn pull-right" type="submit">ต่อไป</button>
      
    </div>
  </div>






  
</form>
<br><br>

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
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"rel = "stylesheet">
@endsection