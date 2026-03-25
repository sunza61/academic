<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProjectEvaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'academic_project_id',
        
        // 🌟 เพิ่ม satisfaction_score เข้ามา
        'satisfaction_score', 'satisfaction_percent', 'satisfaction_range', 'satisfaction_level',
        
        // 🌟 เพิ่ม dissatisfaction_score เข้ามา
        'dissatisfaction_score', 'dissatisfaction_percent', 'dissatisfaction_range', 'dissatisfaction_level',
        
        'improvement_apply', 'impact', 'integration', 'integration_eval',
        
        // ❌ เอา evaluation_score ตัวเก่าออกไปแล้วนะครับ
        'sroi_score', 'award_count', 'industrial_value', 'project_achievement' 
    ];
}
