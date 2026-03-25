<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProject extends Model
{
    use HasFactory;
    protected $table = "academic_projects";
    protected $fillable = [
        'project_type_id',
        'service_group_id',
        'center_id',
        'name_th',
        'brief_description',
        'rationale',
        'fiscal_year_id',
        'start_date',
        'end_date',
        'overall_status',
        'cancel_reason',
        'aod_status',
        'aod_code',
        'region_type',
        'created_by',
        'update_by',
        'del_status',
    ];
}
