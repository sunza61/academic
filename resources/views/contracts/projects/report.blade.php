@extends('layouts.main_all') 

@section('content')
<div class="row mb-3 mt-2">
    <div class="col-sm-8">
        <h3 class="m-0 font-weight-bold text-dark">
            <i class="fas fa-flag-checkered text-success mr-2"></i> รายงานผลและปิดโครงการ: <span class="text-primary">{{ $project->name_th }}</span>
        </h3>
    </div>
    <div class="col-sm-4 text-right">
        <a href="{{ route('contracts.projects.index', ['type_id' => $project->project_type_id]) }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> กลับไปหน้าตาราง
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> <strong>เกิดข้อผิดพลาด!</strong> กรุณาตรวจสอบข้อมูลในฟอร์มด้านล่าง
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="alert alert-info shadow-sm border-0 mb-4">
    <i class="fas fa-info-circle fa-lg mr-2"></i> 
    <strong>ข้อควรทราบ:</strong> กรุณากรอกข้อมูลสรุปผลการประเมินและผลสัมฤทธิ์ของโครงการ เมื่อกดยืนยันบันทึกข้อมูล โครงการนี้จะถูกเปลี่ยนสถานะเป็น <strong>"เสร็จสิ้นโครงการ"</strong> ทันที
</div>

<form action="{{ route('contracts.projects.save-report', $project->id) }}" method="POST" id="form-report">
    @csrf
    
    <div class="card shadow-sm border-0 mb-4 project-section">
        <div class="card-header bg-custom-dark text-white py-2">
            <h5 class="card-title mb-0 mt-1"><i class="fas fa-smile mr-2"></i> ส่วนที่ 1: สรุปผลการประเมินความพึงพอใจ</h5>
        </div>
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-md-6 border-right">
                    <h6 class="font-weight-bold text-success mb-3"><i class="fas fa-thumbs-up"></i> ด้านความพึงพอใจ</h6>
                    <div class="row">
                        <div class="col-sm-5 form-group">
                            <label>คะแนน <small class="text-danger font-weight-bold">(เต็ม 5)</small></label>
                            <div class="input-group">
                                <input type="number" name="satisfaction_score" id="satisfaction_score" class="form-control text-center text-primary font-weight-bold @error('satisfaction_score') is-invalid @enderror" value="{{ old('satisfaction_score', $projectEvaluation->satisfaction_score ?? '') }}" step="0.01" min="0" max="5" placeholder="0.00">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text bg-light"><i class="fas fa-arrow-right"></i></span>
                                </div>
                                <input type="number" name="satisfaction_percent" id="satisfaction_percent" class="form-control text-center text-success font-weight-bold" value="{{ old('satisfaction_percent', $projectEvaluation->satisfaction_percent ?? '') }}" readonly style="background-color: #e9ecef;" placeholder="0.00">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white">%</span>
                                </div>
                            </div>
                            @error('satisfaction_score') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>พิสัย (Range)</label>
                            <input type="text" name="satisfaction_range" class="form-control text-center @error('satisfaction_range') is-invalid @enderror" value="{{ old('satisfaction_range', $projectEvaluation->satisfaction_range ?? '') }}">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>ระดับ</label>
                            <select name="satisfaction_level" class="form-control @error('satisfaction_level') is-invalid @enderror">
                                <option value="">-- เลือก --</option>
                                <option value="5" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '5') ? 'selected' : '' }}>มากที่สุด</option>
                                <option value="4" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '4') ? 'selected' : '' }}>มาก</option>
                                <option value="3" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '3') ? 'selected' : '' }}>ปานกลาง</option>
                                <option value="2" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '2') ? 'selected' : '' }}>น้อย</option>
                                <option value="1" {{ (old('satisfaction_level', $projectEvaluation->satisfaction_level ?? '') == '1') ? 'selected' : '' }}>น้อยที่สุด</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-danger mb-3"><i class="fas fa-thumbs-down"></i> ด้านความไม่พึงพอใจ</h6>
                    <div class="row">
                        <div class="col-sm-5 form-group">
                            <label>คะแนน <small class="text-danger font-weight-bold">(เต็ม 5)</small></label>
                            <div class="input-group">
                                <input type="number" name="dissatisfaction_score" id="dissatisfaction_score" class="form-control text-center text-danger font-weight-bold @error('dissatisfaction_score') is-invalid @enderror" value="{{ old('dissatisfaction_score', $projectEvaluation->dissatisfaction_score ?? '') }}" step="0.01" min="0" max="5" placeholder="0.00">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text bg-light"><i class="fas fa-arrow-right"></i></span>
                                </div>
                                <input type="number" name="dissatisfaction_percent" id="dissatisfaction_percent" class="form-control text-center text-warning font-weight-bold" value="{{ old('dissatisfaction_percent', $projectEvaluation->dissatisfaction_percent ?? '') }}" readonly style="background-color: #e9ecef;" placeholder="0.00">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>พิสัย (Range)</label>
                            <input type="text" name="dissatisfaction_range" class="form-control text-center @error('dissatisfaction_range') is-invalid @enderror" value="{{ old('dissatisfaction_range', $projectEvaluation->dissatisfaction_range ?? '') }}">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>ระดับ</label>
                            <select name="dissatisfaction_level" class="form-control @error('dissatisfaction_level') is-invalid @enderror">
                                <option value="">-- เลือก --</option>
                                <option value="1" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '1') ? 'selected' : '' }}>น้อยที่สุด</option>
                                <option value="2" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '2') ? 'selected' : '' }}>น้อย</option>
                                <option value="3" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '3') ? 'selected' : '' }}>ปานกลาง</option>
                                <option value="4" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '4') ? 'selected' : '' }}>มาก</option>
                                <option value="5" {{ (old('dissatisfaction_level', $projectEvaluation->dissatisfaction_level ?? '') == '5') ? 'selected' : '' }}>มากที่สุด</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 project-section">
        <div class="card-header bg-custom-dark text-white py-2">
            <h5 class="card-title mb-0 mt-1"><i class="fas fa-clipboard-check mr-2"></i> ส่วนที่ 2: การบูรณาการ และ ผลกระทบ</h5>
        </div>
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-md-6 border-right">
                    <div class="form-group">
                        <label class="text-primary"><i class="fas fa-hand-point-right"></i> การนำผลประเมินไปปรับปรุง</label>
                        <textarea name="improvement_apply" class="form-control @error('improvement_apply') is-invalid @enderror" rows="4">{{ old('improvement_apply', $projectEvaluation->improvement_apply ?? '') }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-primary"><i class="fas fa-hand-point-right"></i> ผลกระทบของกิจกรรม</label>
                        <textarea name="impact" class="form-control @error('impact') is-invalid @enderror" rows="4">{{ old('impact', $projectEvaluation->impact ?? '') }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="text-info"><i class="fas fa-hand-point-right"></i> การบูรณาการ</label>
                        <textarea name="integration" class="form-control @error('integration') is-invalid @enderror" rows="4">{{ old('integration', $projectEvaluation->integration ?? '') }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-info"><i class="fas fa-hand-point-right"></i> การประเมินการบูรณาการ / การนำผลไปปรับปรุง</label>
                        <textarea name="integration_eval" class="form-control @error('integration_eval') is-invalid @enderror" rows="4">{{ old('integration_eval', $projectEvaluation->integration_eval ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 project-section">
        <div class="card-header bg-custom-dark text-white py-2">
            <h5 class="card-title mb-0 mt-1"><i class="fas fa-chart-pie mr-2"></i> ส่วนที่ 3: ผลสัมฤทธิ์และมูลค่าโครงการ (ถ้ามี)</h5>
        </div>
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>คะแนน SROI</label>
                    <input type="number" name="sroi_score" class="form-control text-center text-primary font-weight-bold @error('sroi_score') is-invalid @enderror" value="{{ old('sroi_score', $projectEvaluation->sroi_score ?? '') }}" step="0.01" min="0" placeholder="0.00">
                </div>
                <div class="col-md-4 form-group">
                    <label>จำนวนรางวัล <small class="text-muted">(รางวัล)</small></label>
                    <input type="number" name="award_count" class="form-control text-center text-success font-weight-bold @error('award_count') is-invalid @enderror" value="{{ old('award_count', $projectEvaluation->award_count ?? '') }}" min="0" placeholder="0">
                </div>
                <div class="col-md-4 form-group">
                    <label>มูลค่าที่ส่งมอบให้ภาคอุตสาหกรรม</label>
                    <div class="input-group">
                        <input type="number" name="industrial_value" class="form-control text-right text-danger font-weight-bold @error('industrial_value') is-invalid @enderror" value="{{ old('industrial_value', $projectEvaluation->industrial_value ?? '') }}" step="0.01" min="0" placeholder="0.00">
                        <div class="input-group-append">
                            <span class="input-group-text bg-white">บาท</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-group mb-0">
                    <label class="text-dark"><i class="fas fa-award text-warning"></i> ผลสัมฤทธิ์โครงการ (สิ่งที่ได้รับ)</label>
                    <textarea name="project_achievement" class="form-control @error('project_achievement') is-invalid @enderror" rows="3">{{ old('project_achievement', $projectEvaluation->project_achievement ?? '') }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-white text-right py-4 border-top">
            <a href="{{ route('contracts.projects.index', ['type_id' => $project->project_type_id]) }}" class="btn btn-secondary btn-lg shadow-sm mr-2">
                <i class="fas fa-times mr-1"></i> ยกเลิก
            </a>
            <button type="submit" class="btn btn-success btn-lg shadow-sm" id="btn-submit-report">
                <i class="fas fa-check-circle mr-1"></i> ยืนยันบันทึกรายงาน และปิดโครงการ
            </button>
        </div>
    </div>
</form>

@endsection

@section('script')
<script src="{{ asset('js/contracts/projects/report.js?v=' . time()) }}"></script>
@endsection