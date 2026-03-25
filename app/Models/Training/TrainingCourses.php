<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCourses extends Model
{
    use HasFactory;
    protected $table = "training_courses";
    protected $fillable = [
        'training_project_id',
        'course_name',
    ];
}
