<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSchedulesLocation extends Model
{
    use HasFactory;
    protected $table = "training_schedules_locations";
    protected $fillable = [
        'training_project_id',
        'location_name',
        'address_detail',
        'province_id',
        'latitude',
        'longitude',
        'training_schedule_id'
    ];
}
