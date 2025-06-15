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
</style>

<div class="row mt-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1 bg-gradient- position-relative">
            <img class="card-img-top custom-img" src="{{ asset('adminlte/dist/img/img-0.jpg') }}">
            <div class="overlay-text">ACADEMIC & RESEARCHER</div>
        </div>
    </div>
</div>
<div class="row mt-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1">
            <div class="card-body">
                <div class="row">
                    <div class="col-4 px-0">
                        <h4 class="card-title text-start">ACADEMIC & RESEARCHER</h4>
                    </div>
                    <div class="col-8">
                        <input id="searchInput" class="form-control form-control-navbar" type="search" placeholder="Search Researcher" aria-label="Search">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-1">
    <div class="col-md-4 col-lg-3 col-xl-3">
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
    <div class="col-md-8 col-lg-9 col-xl-9">
        <div class="card mb-1">
            <div class="card-body">
                <div class="row mt-1">
                    <div class="col-md-12 mb-0">
                        <div class="row mt-1">
                            <div class="col-md-6 mb-0" style="border-right: 1px solid #ccc;">
                                <div class="row mt-1">
                                    <div class="col-md-12 col-lg-6 col-xl-2 mb-0">
                                        <div class="row mt-1">
                                            <div class="col-md-12 mb-0">
                                                <div class="inner" style="margin-top: 50px;">
                                                    <center>
                                                        <h3>xxx</h3>
                                                        <p><b>All Publication</b></p>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10 mb-0">
                                        <div class="col-md-12 mb-0 d-flex justify-content-center">
                                            <div class="table-responsive">
                                                <table style="border-collapse: separate; border-spacing: 30px 20px; text-align: center; margin-left: auto; margin-right: auto;">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: left;"></th>
                                                            <th><strong>TEIR1</strong></th>
                                                            <th><strong>Q1</strong></th>
                                                            <th><strong>Q2</strong></th>
                                                            <th><strong>Q3</strong></th>
                                                            <th><strong>Q4</strong></th>
                                                            <th><strong>TOTAL</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th style="text-align: left;"><strong>ISI</strong></th>
                                                            <td>XX</td>
                                                            <td>XX</td>
                                                            <td>XX</td>
                                                            <td>XX</td>
                                                            <td>XX</td>
                                                            <td>XXX</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="text-align: left;"><strong>SCOPUS&ISI</strong></th>
                                                            <td>XX</td>
                                                            <td>XX</td>
                                                            <td>XX</td>
                                                            <td>XX</td>
                                                            <td>XX</td>
                                                            <td>XXX</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-0">
                                <p class="card-text"><strong>
                                        RESEARCH PROJECTS
                                    </strong></p>
                                <div class="row mt-1">
                                    <div class="col-md-4 mb-0">
                                        <div class="inner">
                                            <center>
                                                <h3>xxx</h3>
                                                <p>Research Project Overall</p>
                                            </center>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-0">
                                        <div class="inner">
                                            <center>
                                                <h3>xxx</h3>
                                                <p>Ongoing</p>
                                            </center>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-0">
                                        <div class="inner">
                                            <center>
                                                <h3>xxx</h3>
                                                <p>Finished</p>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <hr>
                <div class="row mt-1">
                    <div class="col-md-12 mb-0">
                        <div class="row mt-1">
                            <div class="col-md-6 mb-0" style="border-right: 1px solid #ccc;">
                                <p class="card-text"><strong>
                                        INTELLECTUAL PROPERTY: IP
                                    </strong></p>
                                <div class="row mt-1">
                                    <div class="col-md-4 mb-0">
                                        <div class="row mt-1">
                                            <div class="col-md-12 mb-0">
                                                <div class="inner" style="margin-top: 50px;">
                                                    <center>
                                                        <h3>xxx</h3>
                                                        <p><b>All IP</b></p>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 mb-0">
                                        <div class="row mt-1">
                                            <div class="col-md-3 mb-0">
                                                <div class="inner">
                                                    <center>
                                                        <h3>xxx</h3>
                                                        <p>Patent</p>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-0">
                                                <div class="inner">
                                                    <center>
                                                        <h3>xxx</h3>
                                                        <p>Petty Patent</p>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-0">
                                                <div class="inner">
                                                    <center>
                                                        <h3>xxx</h3>
                                                        <p>Copyright</p>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-0">
                                                <div class="inner">
                                                    <center>
                                                        <h3>xxx</h3>
                                                        <p>Registered Designs</p>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-0">
                                                <div class="inner">
                                                    <center>
                                                        <h3>xxx</h3>
                                                        <p>ได้รับเลขทะเบียนแล้ว</p>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-0">
                                                <div class="inner">
                                                    <center>
                                                        <h3>xxx</h3>
                                                        <p>อยู่ระหว่างดำเนินการ</p>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-0">
                                <div class="row mt-1">
                                    <div class="col-md-12 mb-0">
                                        <p class="card-text"><strong>
                                                EXPARTIES (SCOPUS)
                                            </strong></p>
                                        <p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-1">
                                    <div class="col-md-12 mb-0">
                                        <p class="card-text"><strong>
                                                SDGs (SCOPUS)
                                            </strong></p>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                    <div class="col-md-2 col-lg-1 col-xl-1 mb-2">
                                        <div style="width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 10px; overflow: hidden;"><img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-1">
    <div class="col-md-12  col-xl-6">
        <div class="card mb-1">
            <div class="card-body">
                <p class="card-text"><strong>
                        EDUCATION
                    </strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-12  col-xl-6">
        <div class="card mb-1">
            <div class="card-body">
                <p class="card-text"><strong>
                        PUBLICATIONS
                    </strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-12  col-xl-6">
        <div class="card mb-1">
            <div class="card-body">
                <p class="card-text"><strong>
                        RESEARCH PROJECTS
                    </strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-12  col-xl-6">
        <div class="card mb-1">
            <div class="card-body">
                <p class="card-text"><strong>
                        IP
                    </strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-12  col-xl-6">
        <div class="card mb-1">
            <div class="card-body">
                <p class="card-text"><strong>
                        AWARD&HONOR
                    </strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-12  col-xl-6">
        <div class="card mb-1">
            <div class="card-body">
                <p class="card-text"><strong>
                        ACADEMIC SERVICE
                    </strong></p>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')



<script>

</script>

@endsection