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
<div class="row mt-0">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1">
            <div class="card-body">
                <h4 class="card-title-academic">RESEARCHER AREA : xxxxxxxx </h4>
                <br>
                <div class="row mt-1">
                    <div class="col-md-4 col-lg-3 col-xl-3 mb-4">
                        <div class="card mb-1">
                            <div class="card-body p-0">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center">Anchana Prathep</h3>
                                    <p class="text-muted text-center">Division of Biological Science</p>
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item">
                                            <b>TEL</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>EMAIL</b> <a class="float-right">xxxxxxxx@xxx.xxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>RESEARCH NETWORK</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>PUBLICATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>H-INDEX (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>CITATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-4 col-lg-3 col-xl-3 mb-4">
                        <div class="card mb-1">
                            <div class="card-body p-0">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center">Anchana Prathep</h3>
                                    <p class="text-muted text-center">Division of Biological Science</p>
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item">
                                            <b>TEL</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>EMAIL</b> <a class="float-right">xxxxxxxx@xxx.xxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>RESEARCH NETWORK</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>PUBLICATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>H-INDEX (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>CITATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-4 col-lg-3 col-xl-3 mb-4">
                        <div class="card mb-1">
                            <div class="card-body p-0">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center">Anchana Prathep</h3>
                                    <p class="text-muted text-center">Division of Biological Science</p>
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item">
                                            <b>TEL</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>EMAIL</b> <a class="float-right">xxxxxxxx@xxx.xxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>RESEARCH NETWORK</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>PUBLICATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>H-INDEX (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>CITATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <div class="col-md-4 col-lg-3 col-xl-3 mb-4">
                        <div class="card mb-1">
                            <div class="card-body p-0">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center">Anchana Prathep</h3>
                                    <p class="text-muted text-center">Division of Biological Science</p>
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item">
                                            <b>TEL</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>EMAIL</b> <a class="float-right">xxxxxxxx@xxx.xxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>RESEARCH NETWORK</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>PUBLICATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>H-INDEX (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>CITATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-4 col-lg-3 col-xl-3 mb-4">
                        <div class="card mb-1">
                            <div class="card-body p-0">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="User profile picture">
                                    </div>
                                    <h3 class="profile-username text-center">Anchana Prathep</h3>
                                    <p class="text-muted text-center">Division of Biological Science</p>
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item">
                                            <b>TEL</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>EMAIL</b> <a class="float-right">xxxxxxxx@xxx.xxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>RESEARCH NETWORK</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>PUBLICATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>H-INDEX (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>CITATIONS (SCOPUS)</b> <a class="float-right">xxxxxxxx</a>
                                        </li>
                                    </ul>
                                </div>
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
                    return $("<div>").html(data).text(); // ดึงค่า text ออกมา
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