<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicBudget extends Model
{
    use HasFactory;
    protected $table = "academic_budgets";
    protected $fillable = [
        'academic_project_id',
        'total_budget_summary',
        'total_advance_amount',
        'total_fine_amount',
        'remuneration_fee',
        'operation_fee',
        'service_fee_percent',
        'service_fee_amount',
        'alloc_uni_percent',
        'alloc_uni_amount',
        'alloc_campus_percent',
        'alloc_campus_amount',
        'alloc_dept_percent',
        'alloc_dept_amount',
        'fund_research_percent',
        'fund_research_amount',
        'faculty_percent',
        'faculty_amount',
        'center_percent',
        'center_amount',
    ];
}
