<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProject extends Model
{
    use HasFactory;
    protected $table = "training_projects";
    protected $fillable = [
        'academic_project_id',
        'document_number',
        'project_types',
        'course_type',
        'start_regis_date',
        'end_regis_date',
        'has_collaboration',
        'approval_file',
        'approval_link',
        'training_status',
    ];
}
