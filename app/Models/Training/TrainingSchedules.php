<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSchedules extends Model
{
    use HasFactory;
    protected $table = "training_schedules";
    protected $fillable = [
        'training_project_id',
        'schedule_date',
        'event',
        'start_time',
        'end_time',
        'topic'
    ];
}
