<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicObjective extends Model
{
    use HasFactory;
    protected $table = "academic_objectives";
    protected $fillable = [
        'academic_project_id',
        'target_group_id ',
    ];
}
