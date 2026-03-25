<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetExpenseMainCategory extends Model
{
    use HasFactory;
    protected $table = "budget_expense_main_categories";
    
    protected $fillable = [
        'name_th',
        'is_active',
    ];

    public function subCategories()
    {
        return $this->hasMany(BudgetExpenseCategorie::class, 'main_category_id', 'id');
    }
}
