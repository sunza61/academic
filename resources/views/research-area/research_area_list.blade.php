@extends('layouts.main_all')
@section('content')

<head>
    <link href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css?')}}{{sha1(time())}}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css?')}}{{sha1(time())}}" rel="stylesheet">
</head>
<style>
    .custom-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .overlay-text {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        color: white;
        font-size: 36px;
        font-weight: bold;
        text-align: left;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .card-title-academic {
        font-size: 30px !important;
        /* ปรับขนาดตามต้องการ */
        font-weight: bold;
        line-height: 1.2;
        /* ควบคุมระยะห่างระหว่างบรรทัด */
    }

    .alphabet-filter {
        display: flex;
        flex-wrap: wrap;
        /* ให้ขึ้นบรรทัดใหม่เมื่อพื้นที่ไม่พอ */
        justify-content: center;
        /* จัดให้อยู่กึ่งกลาง */
        gap: 5px;
        /* กำหนดระยะห่างระหว่างตัวอักษร */
        padding: 10px;
    }

    .letter {
        flex: 1 1 30px;
        /* ขนาดขั้นต่ำ 30px ปรับขนาดตามพื้นที่ */
        text-align: center;
        padding: 10px;
        background: #007bff;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        min-width: 30px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .letter {
            flex: 1 1 20px;
            /* ลดขนาดตัวอักษรเมื่อหน้าจอเล็ก */
            padding: 8px;
        }
    }

    @media (max-width: 480px) {
        .letter {
            flex: 1 1 15px;
            padding: 6px;
        }
    }
</style>

<div class="row mt-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1 bg-gradient- position-relative">
            <img class="card-img-top custom-img" src="{{ asset('adminlte/dist/img/img-0.jpg') }}">
            <div class="overlay-text">RESEARCHER AREA</div>
        </div>
    </div>
</div>
<div class="row mt-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1">
            <div class="card-body">
                <div class="row">
                    <div class="col-4 px-0">
                        <h4 class="card-title text-start">RESEARCHER AREA</h4>
                    </div>
                    <div class="col-8">
                        <input id="searchInput" class="form-control form-control-navbar" type="search" placeholder="Search RearchArea" aria-label="Search">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1">
            <div class="card-body">
                <h4 class="card-title-academic">RESEARCHER AREA</h4>
                <div class="card-body">
                    <div id="place" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alphabet-filter">
                                        <span class="letter" data-letter="A">A</span>
                                        <span class="letter" data-letter="B">B</span>
                                        <span class="letter" data-letter="C">C</span>
                                        <span class="letter" data-letter="D">D</span>
                                        <span class="letter" data-letter="E">E</span>
                                        <span class="letter" data-letter="F">F</span>
                                        <span class="letter" data-letter="G">G</span>
                                        <span class="letter" data-letter="H">H</span>
                                        <span class="letter" data-letter="I">I</span>
                                        <span class="letter" data-letter="J">J</span>
                                        <span class="letter" data-letter="K">K</span>
                                        <span class="letter" data-letter="L">L</span>
                                        <span class="letter" data-letter="M">M</span>
                                        <span class="letter" data-letter="N">N</span>
                                        <span class="letter" data-letter="O">O</span>
                                        <span class="letter" data-letter="P">P</span>
                                        <span class="letter" data-letter="Q">Q</span>
                                        <span class="letter" data-letter="R">R</span>
                                        <span class="letter" data-letter="S">S</span>
                                        <span class="letter" data-letter="T">T</span>
                                        <span class="letter" data-letter="U">U</span>
                                        <span class="letter" data-letter="V">V</span>
                                        <span class="letter" data-letter="W">W</span>
                                        <span class="letter" data-letter="X">X</span>
                                        <span class="letter" data-letter="Y">Y</span>
                                        <span class="letter" data-letter="Z">Z</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <table id="tabledata_list" class="table table-bordered-0 table-striped dataTable dtr-inline" role="grid" aria-describedby="tabledata_list_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting" tabindex="0" aria-controls="tabledata_list" rowspan="1" colspan="1" aria-label="No"><a class="d-flex justify-content-center"> No. </a></th>
                                            <th class="sorting" tabindex="0" aria-controls="tabledata_list" rowspan="1" colspan="1" aria-label="name"><a class="d-flex justify-content-center"> RESEARCHER AREA </a></th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="">
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    1
                                                </div>
                                            </td>
                                            <td>
                                                <a href="http://172.28.80.250/suny/web/scire/public/research-area-detail">เทคโนโลยีกับกลยุทธ์ในการดำเนินธุรกิจ</a>
                                            </td>
                                           
                                        </tr>
                                        <tr id="">
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    2
                                                </div>
                                            </td>
                                            <td>
                                                <div>การจัดการความรู้เพื่อการบริหารเทคโนโลยีและนวัตกรรม</div>
                                            </td>
                                           
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function() {
        var table = $("#tabledata_list").DataTable({
            "pageLength": 50,
            "responsive": true,
            "autoWidth": false,
            "lengthChange": false,
             "columnDefs": [{
                "targets": 1,
                "render": function(data, type, row) {
                    return data; // ✅ เก็บ HTML เอาไว้
                }
            }]
        });

        $(".alphabet-filter .letter").on("click", function() {
            var letter = $(this).data("letter");

            // ใช้ regex แบบตรงตัว (Case-sensitive)
            var regex = "^" + letter + ".*";
            table.column(1).search(regex, true, false).draw();
        });

        $("#searchInput").on("keyup", function() {
            table.search(this.value).draw();
        });

    });
</script>

@endsection