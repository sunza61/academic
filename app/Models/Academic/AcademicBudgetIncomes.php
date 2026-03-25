<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicBudgetIncomes extends Model
{
    use HasFactory;
    protected $table = "academic_budget_incomes";
    protected $fillable = [
        'academic_project_id',
        'category_id',
        'description',
        'unit_cost',
        'quantity',
        'total_amount',
    ];
}
