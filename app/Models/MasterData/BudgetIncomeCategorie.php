<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetIncomeCategorie extends Model
{
    use HasFactory;
    protected $table = "budget_income_categories";
    protected $fillable = [
        'main_category_id',
        'name_th',
        'is_service_fee',
        'is_active',
    ];
    public function mainCategory()
    {
        return $this->belongsTo(BudgetIncomeMainCategory::class, 'main_category_id', 'id');
    }
}
