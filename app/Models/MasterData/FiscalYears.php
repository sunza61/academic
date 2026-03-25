<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalYears extends Model
{
    use HasFactory;
    protected $table = "fiscal_years";
    protected $fillable = [
        'year_be',
        'year_ad',
        'fiscal_year_be',
        'fiscal_year_ad',
        'description',
    ];
}
