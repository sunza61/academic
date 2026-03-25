<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerType extends Model
{
    use HasFactory;
    protected $table = "employer_types";
    protected $fillable = [
        'code',
        'name_th',
        'name_en',
        'description',
        'is_active',
    ];
}
