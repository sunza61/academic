@extends('layouts.main_all')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="m-0"><i class="fas fa-map-marked-alt text-success"></i> แดชบอร์ดเจ้าหน้าที่งานแผน (Plan)</h3>
    </div>
    <div class="col-sm-6 text-right">
        <span class="badge badge-success p-2">
            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
        </span>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row mt-3">
    <div class="col-md-3">
        <div class="small-box bg-success shadow-sm">
            <div class="inner">
                <h3>{{ $projects->count() }}</h3>
                <p>โครงการรอตรวจสอบแผนงาน</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <a href="#" class="small-box-footer">ดูข้อมูลเพิ่มเติม <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold">รายการโครงการที่ต้องตรวจสอบแผนงานและยุทธศาสตร์</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="planTable">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th width="15%">รหัสโครงการ</th>
                        <th width="45%">ชื่อโครงการ</th>
                        <th width="10%" class="text-center">ประเภท</th>
                        <th class="text-center" width="10%">สถานะ</th>
                        <th class="text-center" width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $key => $project)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td><span class="badge badge-light border">{{ $project->id }}</span></td>
                            <td>
                                <strong>{{ $project->name_th }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $project->project_type_id }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning">{{ $project->overall_status }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('trainings.projects.show', $project->id) }}" class="btn btn-sm btn-success" title="ตรวจสอบแผนงาน">
                                    <i class="fas fa-search"></i> ตรวจแผน
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plan/dashboard.js?v=' . time()) }}"></script>
@endsection
