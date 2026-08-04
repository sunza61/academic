<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicBudgetExpenses extends Model
{
    use HasFactory;
    protected $table = "academic_budget_expenses";
    protected $fillable = [
        'academic_project_id',
        'category_id',
        'description',
        'cost_per_unit',
        'factor_1',
        'factor_2',
        'uom',
        'total_amount',
        'can_average',
        'expense_type'
    ];
}
