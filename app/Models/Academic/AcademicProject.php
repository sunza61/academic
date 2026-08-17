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
        'project_code',
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

    /**
     * คำนวณรหัสโครงการรูปแบบใหม่: [ปีงบประมาณ]-[อักษรย่อ]-[เลขรัน]
     * ตัวอย่าง: 2569-TR-0001
     */
    public static function generateProjectCode($fiscalYearId, $projectTypeId)
    {
        // 1. ดึงปีงบประมาณ
        $fiscalYear = \App\Models\MasterData\FiscalYears::find($fiscalYearId);
        $yearCode = $fiscalYear ? $fiscalYear->fiscal_year_be : now()->addYears(543)->year;

        // 2. ดึงอักษรย่อประเภทโครงการ
        $projectType = \App\Models\MasterData\ProjectType::find($projectTypeId);
        $abbr = $projectType ? $projectType->abbreviation : 'XX';

        // 3. หาเลขรันล่าสุดของประเภทนี้ในปีงบประมาณนี้
        $lastProject = self::where('fiscal_year_id', $fiscalYearId)
            ->where('project_type_id', $projectTypeId)
            ->whereNotNull('project_code')
            ->orderBy('project_code', 'desc')
            ->first();

        $runningNumber = 1;
        if ($lastProject) {
            // แยกส่วนเลขรันออกมาจากรหัสเดิม (เช่น 2569-TR-0001 -> 0001)
            $parts = explode('-', $lastProject->project_code);
            $lastNumber = (int) end($parts);
            $runningNumber = $lastNumber + 1;
        }

        // 4. จัดรูปแบบเป็น 4 หลัก (0001)
        return $yearCode . '-' . $abbr . '-' . str_pad($runningNumber, 4, '0', STR_PAD_LEFT);
    }
}


