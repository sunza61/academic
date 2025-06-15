@extends('layouts.main_all')
@section('content')

<head>
    <link href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css?')}}{{sha1(time())}}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css?')}}{{sha1(time())}}" rel="stylesheet">

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
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="card shadow-none mb-1">
                <div class="card-body">
                    <h4 class="card-title"><strong>PUBLICATION</strong></h4>
                    <p class="card-text">
                    <div class="row mt-1">
                        <div class="col-md-4 mb-0">
                            <p class="card-text">
                                จำนวนผลการตีพิมพ์(เรื่อง)
                            </p>
                            <div class="chart-container">
                                <canvas id="publicationChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4 mb-0">
                            <p class="card-text">
                                ผลงานตีพิมพ์ในฐานข้อมูล ISI (ตามค่า Q)
                            </p>
                            <div class="chart-container">
                                <canvas id="publicationChartISI"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4 mb-0">
                            <p class="card-text">
                                ผลงานตีพิมพ์ในฐานข้อมูล Scopus (ตามค่า Q)
                            </p>
                            <div class="chart-container">
                                <canvas id="publicationChartScopus"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-8 mb-0">
                            <p class="card-text">
                                Total documents
                            </p>
                            <div class="chart-container">
                                <canvas id="publicationChartTotalDocuments"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4  mb-0">
                            <p class="card-text">
                                Quartile
                            </p>
                            <div class="chart-container">
                                <canvas id="publicationChartQuartile"></canvas>
                            </div>
                        </div>
                    </div>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="card shadow-none mb-1">
                <div class="card-body">
                    <h4 class="card-title"><strong>SDGs</strong></h4>
                    <p class="card-text">
                    <div class="row mt-1">
                        <div class="col-md-4 mb-0">
                            <p class="card-text">
                                PSU Support SDGs
                            </p>
                            <div class="chart-container">
                                <canvas id="SDGsChartPSU" height="500"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4 mb-0">
                            <br><br>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="tabledata_list" class="table table-sm" role="grid" aria-describedby="tabledata_list_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting" tabindex="0"><a class="d-flex justify-content-center"> SDGs </a></th>
                                                <th class="sorting" tabindex="0"><a class="d-flex justify-content-center"> Output  </a></th>
                                                <th class="sorting" tabindex="0"><a class="d-flex justify-content-center"> Project  </a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div>G1</div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">10</div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">12</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>G2</div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">10</div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">18</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>G3</div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">10</div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">120</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4 mb-0">
                            <p class="card-text">
                                Top3 of SDGs (Publications)
                            </p>
                            <div class="col-sm-12">
                                <table id="tabledata_top3" class="table table-bordered-0 table-striped dataTable dtr-inline" role="grid" aria-describedby="tabledata_list_info">
                                    <thead>

                                    </thead>
                                    <tbody>
                                        <tr id="">
                                            <td>
                                                <div>SDGs 1</div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column align-items-center">
                                                    <img src="http://172.28.80.250/suny/web/scire/public/adminlte/dist/img/user3-128x128.jpg" class="rounded-circle" width="35" height="35">

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column align-items-center">
                                                    <img src="http://172.28.80.250/suny/web/scire/public/adminlte/dist/img/user1-128x128.jpg" class="rounded-circle" width="35" height="35">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column align-items-center">
                                                    <img src="http://172.28.80.250/suny/web/scire/public/adminlte/dist/img/user4-128x128.jpg" class="rounded-circle" width="35" height="35">
                                                </div>
                                            </td>

                                        </tr>
                                        <tr id="">
                                            <td>
                                                <div>SDGs 2</div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    10
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    18
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    12
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="">
                                            <td>
                                                <div>SDGs 3</div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    10
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    120
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    12
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-1">
        <div class="col-md-12 col-lg-12 col-xl-10">
            <div class="card shadow-none mb-1">
                <div class="card-body">
                    <h4 class="card-title"><strong>RESEARCH PROJECT</strong></h4>
                    <p class="card-text">
                    <div class="row mt-1">
                        <div class="col-md-12 col-lg-12 col-xl-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="card-text">
                                        จำนวนโครงการวิจัยทั้งหมด(ตามปีงบประมาณ)
                                    </p>
                                    <div class="chart-container">
                                        <canvas id="researchProjectsByYear"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p class="card-text">
                                        จำนวนโครงการวิจัยทั้งหมด
                                    </p>
                                    <div class="col-md-12 text-center">
                                        <input type="text" class="knob"
                                            value="616"
                                            readonly
                                            data-width="150"
                                            data-height="150"
                                            data-angleArc="180"
                                            data-angleOffset="270"
                                            data-fgColor="#66CC66"
                                            data-min="0"
                                            data-max="1000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p class="card-text">
                                        จำนวนโครงการตามประเภท BPD
                                    </p>
                                    <div class="chart-container">
                                        <canvas id="researchProjectsByBPDType"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p class="card-text">
                                        จำนวนงบประมาณของโครงการวิจัย (บาท)
                                    </p>
                                    <div class="chart-container">
                                        <canvas id="researchBudgetAmount"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p class="card-text">
                                        จำนวนงบประมาณทั้งหมด (ล้านบาท)
                                    </p>
                                    <div class="col-md-12 text-center">
                                        <input type="text" class="knob"
                                            value="792.6"
                                            readonly
                                            data-width="150"
                                            data-height="150"
                                            data-angleArc="180"
                                            data-angleOffset="270"
                                            data-fgColor="#ed913b"
                                            data-min="0"
                                            data-max="1000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p class="card-text">
                                        จำนวนงบประมาณตามประเภท BPD
                                    </p>
                                    <div class="chart-container">
                                        <canvas id="researchBudgetByBPDType"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-xl-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="card-text">
                                        External Funding
                                    </p>
                                    <div class="chart-container">
                                        <canvas id="externalFunding"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <p class="card-text">
                                            Project Staus
                                        </p>
                                        <div class="chart-container">
                                            <canvas id="projectStaus"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="card-text">
                                            Research Area
                                        </p>
                                        <div class="chart-container">
                                            <canvas id="researchArea"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="card-text">
                                        จำนวนงบประมาณที่ได้รับการจัดสรรของแต่ละแหล่งทุน (บาท)
                                    </p>
                                    <div class="chart-container">
                                        <canvas id="allocatedBudgetByFundingSource"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <p class="card-text">
                                            จำนวนโครงการวิจัยแยกตาม SDG (โครงการ)
                                        </p>
                                        <div class="chart-container">
                                            <canvas id="researchProjectsBySDG"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="card-text">
                                            จำนวนงบประมาณที่สนับสนุนแต่ละ SDG (บาท)
                                        </p>
                                        <div class="chart-container">
                                            <canvas id="researchBudgetBySDG"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    xxxxx
                                    <div id="chartdiv" style="width:100%; height:400px;"></div>
                                </div>
                                <div class="col-md-2">
                                    <p class="card-text">
                                        Research Area
                                    </p>
                                    <div class="chart-container">
                                        <canvas id="researchNetwork"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-2">
            <div class="card shadow-none mb-1">
                <div class="card-body">
                    <h4 class="card-title"><strong>INTELLECTUAL PROPERTY: IP</strong></h4>
                    <p class="card-text">
                    <div class="row mt-1 justify-content-center">
                        <div class="col-auto">
                            <div class="chart-container position-relative">
                                <canvas id="cluster"></canvas>
                                <br>
                                <div class="cluster-label text-center"><b>Cluster</b></div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row mt-1">
                        <div class="col-6">
                            <div class="cluster-label text-center">xxx</div>
                        </div>
                        <div class="col-6">
                            <div class="cluster-label text-center">xxx</div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-6">
                            <div class="cluster-label text-center"><b>Patent</b></div>
                        </div>
                        <div class="col-6">
                            <div class="cluster-label text-center"> <b>Petty Patent</b></div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row mt-1">
                        <div class="col-6">
                            <div class="cluster-label text-center">xxx</div>
                        </div>
                        <div class="col-6">
                            <div class="cluster-label text-center">xxx</div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-6">
                            <div class="cluster-label text-center"><b>Copyright</b></div>
                        </div>
                        <div class="col-6">
                            <div class="cluster-label text-center"> <b>Registered Designs</b></div>
                        </div>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>

   

    @endsection

    @section('script')
    <!-- ChartJS -->
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
   
    <script>
        $(document).ready(function() {
            $("#tabledata_list").DataTable({
                responsive: false,
                searching: false,
                pageLength: 10,
                paging: false,
                ordering: true,
                info: false,
                lengthChange: true // เปิดให้เลือกจำนวนแถว
            });
        });
        $(document).ready(function() {
            $("#tabledata_top3").DataTable({
                responsive: true,
                searching: false,
                pageLength: 10,
                paging: false,
                ordering: true,
                info: false,
                lengthChange: true // เปิดให้เลือกจำนวนแถว
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('publicationChart').getContext('2d');
            var barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['2020', '2021', '2023', '2024', '2025'],
                    datasets: [{
                        backgroundColor: 'rgba(119, 158, 203, 0.8)',
                        borderColor: 'rgba(176, 196, 222, 0.8)',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('publicationChartISI').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                    datasets: [{
                        backgroundColor: [
                            'rgba(173, 216, 230, 0.8)',
                            'rgba(135, 206, 250, 0.8)',
                            'rgba(176, 196, 222, 0.8)',
                            'rgba(119, 158, 203, 0.8)',
                            'rgba(100, 149, 237, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'right'
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('publicationChartScopus').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                    datasets: [{
                        backgroundColor: [
                            'rgba(173, 216, 230, 0.8)',
                            'rgba(135, 206, 250, 0.8)',
                            'rgba(176, 196, 222, 0.8)',
                            'rgba(119, 158, 203, 0.8)',
                            'rgba(100, 149, 237, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'right'
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('publicationChartQuartile').getContext('2d');

            // ข้อมูลจริงของแต่ละประเภทในแต่ละปี
            var rawData = {
                years: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                journalPapers: [10, 15, 12, 7, 30], // จำนวน Journal Papers
                conferencePapers: [8, 20, 18, 5, 40], // จำนวน Conference Papers
                bookChapters: [10, 13, 10, 7, 16] // จำนวน Book Chapters
            };

            // คำนวณผลรวมของแต่ละปี
            var totalPerYear = rawData.years.map((_, i) =>
                rawData.journalPapers[i] + rawData.conferencePapers[i] + rawData.bookChapters[i]
            );

            // คำนวณเปอร์เซ็นต์ของแต่ละประเภทในแต่ละปี
            var journalPapersPercent = rawData.journalPapers.map((val, i) => (val / totalPerYear[i]) * 100);
            var conferencePapersPercent = rawData.conferencePapers.map((val, i) => (val / totalPerYear[i]) * 100);
            var bookChaptersPercent = rawData.bookChapters.map((val, i) => (val / totalPerYear[i]) * 100);

            var barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: rawData.years, // มีแค่ปีละ 1 แท่ง
                    datasets: [{
                            label: 'Journal Papers (%)',
                            backgroundColor: 'rgba(173, 216, 230, 0.8)',
                            borderColor: '#fff',
                            borderWidth: 1,
                            data: journalPapersPercent,
                            stack: 'Stack 0' // ซ้อนกันใน stack เดียว
                        },
                        {
                            label: 'Conference Papers (%)',
                            backgroundColor: 'rgba(135, 206, 250, 0.8)',
                            borderColor: '#fff',
                            borderWidth: 1,
                            data: conferencePapersPercent,
                            stack: 'Stack 0' // ซ้อนกันใน stack เดียว
                        },
                        {
                            label: 'Book Chapters (%)',
                            backgroundColor: 'rgba(176, 196, 222, 0.8)',
                            borderColor: '#fff',
                            borderWidth: 1,
                            data: bookChaptersPercent,
                            stack: 'Stack 0' // ซ้อนกันใน stack เดียว
                        }
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('publicationChartTotalDocuments').getContext('2d');

            // ข้อมูลจริงของแต่ละประเภทในแต่ละปี
            var rawData = {
                years: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                journalPapers: [10, 15, 12, 7, 30], // จำนวน Journal Papers
                conferencePapers: [8, 20, 18, 5, 40], // จำนวน Conference Papers
                bookChapters: [10, 13, 10, 7, 16] // จำนวน Book Chapters
            };

            // คำนวณผลรวมของแต่ละปี
            var totalPerYear = rawData.years.map((_, i) =>
                rawData.journalPapers[i] + rawData.conferencePapers[i] + rawData.bookChapters[i]
            );

            // คำนวณเปอร์เซ็นต์ของแต่ละประเภทในแต่ละปี
            var journalPapersPercent = rawData.journalPapers.map((val, i) => (val / totalPerYear[i]) * 100);
            var conferencePapersPercent = rawData.conferencePapers.map((val, i) => (val / totalPerYear[i]) * 100);
            var bookChaptersPercent = rawData.bookChapters.map((val, i) => (val / totalPerYear[i]) * 100);

            var pieData = rawData.years.map((year, index) => {
                return {
                    label: `Year ${year}`,
                    data: [
                        journalPapersPercent[index], // Data for Journal Papers
                        conferencePapersPercent[index], // Data for Conference Papers
                        bookChaptersPercent[index] // Data for Book Chapters
                    ],
                    backgroundColor: [
                        'rgba(173, 216, 230, 0.8)', // สีสำหรับ Journal Papers
                        'rgba(135, 206, 250, 0.8)', // สีสำหรับ Conference Papers
                        'rgba(176, 196, 222, 0.8)' // สีสำหรับ Book Chapters
                    ]
                };
            });

            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Journal Papers', 'Conference Papers', 'Book Chapters'], // Labels สำหรับแต่ละส่วน
                    datasets: pieData.map((dataSet) => {
                        return {
                            label: dataSet.label,
                            data: dataSet.data,
                            backgroundColor: dataSet.backgroundColor
                        };
                    })
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top', // แสดง Legend ด้านบน
                            labels: {
                                boxWidth: 20, // ขนาดของกล่องสีใน legend
                                font: {
                                    size: 12 // ขนาดตัวอักษรใน Legend
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    // แสดง label พร้อมเปอร์เซ็นต์ของแต่ละประเภท
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(1) + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('SDGsChartPSU').getContext('2d');

            var rawData = {
                years: ['2021', '2022', '2023', '2024', '2025'],
                G1: [10, 15, 12, 7, 30],
                G2: [8, 20, 18, 5, 40],
                G3: [10, 13, 100, 7, 16],
                G4: [5, 10, 15, 20, 25],
                G5: [12, 18, 22, 26, 30],
                G6: [14, 16, 20, 24, 28],
                G7: [7, 14, 21, 28, 35],
                G8: [9, 11, 13, 15, 17],
                G9: [3, 6, 9, 12, 15],
                G10: [2, 4, 6, 8, 10],
                G11: [11, 13, 15, 17, 19],
                G12: [1, 3, 5, 7, 9],
                G13: [6, 12, 18, 24, 30],
                G14: [8, 16, 24, 32, 40],
                G15: [4, 8, 12, 16, 20],
                G16: [5, 10, 15, 20, 25],
                G17: [7, 14, 21, 28, 35]
            };

            var totalPerYear = rawData.years.map((_, i) =>
                Object.keys(rawData).filter(key => key !== 'years').reduce((sum, key) => sum + rawData[key][i], 0)
            );

            var datasets = Object.keys(rawData).filter(key => key !== 'years').map((key, index) => {
                var colors = `hsl(${index * 20}, 70%, 60%)`;
                return {
                    label: key,
                    backgroundColor: colors,
                    borderColor: '#fff',
                    borderWidth: 1,
                    data: rawData[key].map((val, i) => (val / totalPerYear[i]) * 100),
                    stack: 'Stack 0'
                };
            });

            var barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: rawData.years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                display: false // ซ่อนตัวเลขและเส้นสเกลแกน Y
                            }
                        }]
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('cluster').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                    datasets: [{
                        backgroundColor: [
                            'rgba(173, 216, 230, 0.8)',
                            'rgba(135, 206, 250, 0.8)',
                            'rgba(176, 196, 222, 0.8)',
                            'rgba(119, 158, 203, 0.8)',
                            'rgba(100, 149, 237, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                        position: 'right'
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('researchProjectsByYear').getContext('2d');
            var barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['2020', '2021', '2023', '2024', '2025'],
                    datasets: [{
                        backgroundColor: 'rgba(119, 158, 203, 0.8)',
                        borderColor: 'rgba(176, 196, 222, 0.8)',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                }
            });
        });
    </script>
    <script>
        $(function() {
            $(".knob").knob();
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('researchProjectsByBPDType').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                    datasets: [{
                        backgroundColor: [
                            'rgba(173, 216, 230, 0.8)',
                            'rgba(135, 206, 250, 0.8)',
                            'rgba(176, 196, 222, 0.8)',
                            'rgba(119, 158, 203, 0.8)',
                            'rgba(100, 149, 237, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                        position: 'right'
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('researchBudgetAmount').getContext('2d');
            var barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['2020', '2021', '2023', '2024', '2025'],
                    datasets: [{
                        backgroundColor: 'rgba(119, 158, 203, 0.8)',
                        borderColor: 'rgba(176, 196, 222, 0.8)',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('researchBudgetByBPDType').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                    datasets: [{
                        backgroundColor: [
                            'rgba(173, 216, 230, 0.8)',
                            'rgba(135, 206, 250, 0.8)',
                            'rgba(176, 196, 222, 0.8)',
                            'rgba(119, 158, 203, 0.8)',
                            'rgba(100, 149, 237, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                        position: 'right'
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('externalFunding').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                    datasets: [{
                        backgroundColor: [
                            'rgba(173, 216, 230, 0.8)',
                            'rgba(135, 206, 250, 0.8)',
                            'rgba(176, 196, 222, 0.8)',
                            'rgba(119, 158, 203, 0.8)',
                            'rgba(100, 149, 237, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                        position: 'right'
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('projectStaus').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                    datasets: [{
                        backgroundColor: [
                            'rgba(173, 216, 230, 0.8)',
                            'rgba(135, 206, 250, 0.8)',
                            'rgba(176, 196, 222, 0.8)',
                            'rgba(119, 158, 203, 0.8)',
                            'rgba(100, 149, 237, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                        position: 'right'
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('researchArea').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                    datasets: [{
                        backgroundColor: [
                            'rgba(173, 216, 230, 0.8)',
                            'rgba(135, 206, 250, 0.8)',
                            'rgba(176, 196, 222, 0.8)',
                            'rgba(119, 158, 203, 0.8)',
                            'rgba(100, 149, 237, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                        position: 'right'
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('researchNetwork').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
                    datasets: [{
                        backgroundColor: [
                            'rgba(173, 216, 230, 0.8)',
                            'rgba(135, 206, 250, 0.8)',
                            'rgba(176, 196, 222, 0.8)',
                            'rgba(119, 158, 203, 0.8)',
                            'rgba(100, 149, 237, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                        position: 'right'
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('allocatedBudgetByFundingSource').getContext('2d');
            var barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['2020', '2021', '2023', '2024', '2025'],
                    datasets: [{
                        backgroundColor: 'rgba(119, 158, 203, 0.8)',
                        borderColor: 'rgba(176, 196, 222, 0.8)',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('researchProjectsBySDG').getContext('2d');
            var barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['2020', '2021', '2023', '2024', '2025'],
                    datasets: [{
                        backgroundColor: 'rgba(119, 158, 203, 0.8)',
                        borderColor: 'rgba(176, 196, 222, 0.8)',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('researchBudgetBySDG').getContext('2d');
            var barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['2020', '2021', '2023', '2024', '2025'],
                    datasets: [{
                        backgroundColor: 'rgba(119, 158, 203, 0.8)',
                        borderColor: 'rgba(176, 196, 222, 0.8)',
                        borderWidth: 1,
                        data: [28, 48, 40, 19, 86]
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                }
            });
        });
    </script>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script>
am5.ready(function() {

  var root = am5.Root.new("chartdiv");

  var chart = root.container.children.push(
    am5xy.XYChart.new(root, {
      panX: true,
      panY: true,
      wheelX: "panX",
      wheelY: "zoomX"
    })
  );

  var xAxis = chart.xAxes.push(
    am5xy.CategoryAxis.new(root, {
      categoryField: "category",
      renderer: am5xy.AxisRendererX.new(root, {})
    })
  );

  var yAxis = chart.yAxes.push(
    am5xy.ValueAxis.new(root, {
      renderer: am5xy.AxisRendererY.new(root, {})
    })
  );

  var series = chart.series.push(
    am5xy.ColumnSeries.new(root, {
      name: "จำนวน",
      xAxis: xAxis,
      yAxis: yAxis,
      valueYField: "value",
      categoryXField: "category"
    })
  );

  var data = [
    { category: "ม.ค.", value: 120 },
    { category: "ก.พ.", value: 150 },
    { category: "มี.ค.", value: 90 },
  ];

  xAxis.data.setAll(data);
  series.data.setAll(data);

  series.appear(1000);
  chart.appear(1000, 100);

});
</script>
    @endsection