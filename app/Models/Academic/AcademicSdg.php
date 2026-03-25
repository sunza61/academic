<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSdg extends Model
{
    use HasFactory;
    protected $table = "academic_sdgs";
    protected $fillable = [
        'academic_project_id',
        'sdg_id',
    ];
}
