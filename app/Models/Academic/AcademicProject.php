<?php

namespace App\Models\Academic;

use App\Models\AcademicProjectLog;
use App\Models\MasterData\AcademicProjectSignature;
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

    // =========================================
    // 🌟 ความสัมพันธ์: 1 โครงการ มีผู้ลงนามได้หลายคน (hasMany)
    // =========================================
    public function signatures()
    {
        // ระบุพาทของ Model ลายเซ็นให้ตรงกับที่คุณวัชกรใช้นะครับ (อิงจากตอนที่เรา Insert)
        return $this->hasMany(AcademicProjectSignature::class, 'academic_project_id', 'id')
                    ->orderBy('sign_order', 'asc'); // แถมการเรียงลำดับให้ด้วยเลย ดึงปุ๊บเรียง 1-10 ให้ปั๊บ
    }

    public function latestLog()
    {
        return $this->hasOne(AcademicProjectLog::class, 'academic_project_id')->latest('id');
    }
}


