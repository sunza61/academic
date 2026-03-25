<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicDepartment extends Model
{
    use HasFactory;
    protected $table = "academic_departments";
    protected $fillable = [
        'academic_project_id',
        'department_id',
    ];
}
