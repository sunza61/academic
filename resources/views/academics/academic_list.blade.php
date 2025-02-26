@extends('layouts.main_all')
@section('content')
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
</style>

<div class="row mt-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1 bg-gradient- position-relative">
            <img class="card-img-top custom-img" src="{{ asset('adminlte/dist/img/img-0.jpg') }}">
            <div class="overlay-text">SCI.PSU - OVERVIEW</div>
        </div>
    </div>
</div>
<div class="row mt-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1">
            <div class="card-body">
                <h4 class="card-title">PUBLICATION</h4>
                <p class="card-text">
                <div class="row mt-1">
                    <div class="col-md-4 col-lg-4 mb-0">
                        <p class="card-text">
                            จำนวนผลการตีพิมพ์(เรื่อง)
                            <br><br><br><br><br><br><br>
                        </p>
                    </div>
                    <div class="col-md-4 col-lg-4 mb-0">
                        <p class="card-text">
                            ผลงานตีพิมพ์ในฐานข้อมูล ISI(ตามค่า Q)
                            <br><br><br><br><br><br><br>
                        </p>
                    </div>
                    <div class="col-md-4 col-lg-4 mb-0">
                        <p class="card-text">
                            ผลงานตีพิมพ์ในฐานข้อมูล Scopus (ตามค่า Q)
                            <br><br><br><br><br><br><br>
                        </p>
                    </div>
                    <div class="col-md-4 col-lg-4 mb-0">
                        <p class="card-text">
                            Quartile
                            <br><br><br><br><br><br><br>
                        </p>
                    </div>
                    <div class="col-md-8 col-lg-8 mb-0">
                        <p class="card-text">
                            Total documents
                            <br><br><br><br><br><br><br>
                        </p>
                    </div>
                </div>
            </div>
            </p>
        </div>
    </div>
</div>




<div class="row mt-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1">
            <div class="card-body">
                <h4 class="card-title">PUBLICATION</h4>
                <p class="card-text">
                    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row mt-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-1">
            <div class="card-body">
                <h4 class="card-title">PUBLICATION</h4>
                <p class="card-text">
                    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                </p>
            </div>
        </div>
    </div>
</div>

@endsection