<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetIncomeMainCategory extends Model
{
    use HasFactory;
    protected $table = "budget_income_main_categories";
    
    protected $fillable = [
        'name_th',
        'is_active',
    ];

    // 🌟 สร้างความสัมพันธ์: 1 หมวดหมู่หลัก มีได้หลายหมวดหมู่ย่อย (1-to-Many)
    public function subCategories()
    {
        return $this->hasMany(BudgetIncomeCategorie::class, 'main_category_id', 'id');
    }
}
