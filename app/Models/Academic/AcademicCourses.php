<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCourses extends Model
{
    use HasFactory;
    protected $table = "academic_courses";
    protected $fillable = [
        'academic_project_id',
        'course_id',
    ];
}
