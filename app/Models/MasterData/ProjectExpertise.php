<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectExpertise extends Model
{
    use HasFactory;
    protected $table = "project_expertises";
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
        'is_active',
    ];
}
