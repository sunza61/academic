<?php

namespace App\Models\Project;

use App\Models\Academic\AcademicProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectContract extends Model
{
    use HasFactory;
    protected $table = "project_contracts";
    protected $fillable = [
        'academic_project_id',
        'campus_id',
        'planned_report_date',
        'memo_file_path',
        'contract_number',
        'signing_date',
        'keywords',
        'special_conditions',
        'contract_file_path',
        'contract_file_link',
        'status',
        'total_budget',
        'duration_years',
        'duration_months',
        'duration_days',
        'employer_id',
        'signatory_personnel_id',
        'project_leader_id',
        'is_authorized_rep',
        'project_status',
    ];

    public function academicProject()
{
    // academic_project_id คือ FK ในตารางลูก, id คือ PK ในตารางแม่
    return $this->belongsTo(AcademicProject::class, 'academic_project_id', 'id');
}
}
