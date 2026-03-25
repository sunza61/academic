<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingMember extends Model
{
    use HasFactory;
    protected $table = "training_members";
    protected $fillable = [
        'training_project_id',
        'member_type',
        'personnel_id',
        'external_prefix',
        'external_firstname',
        'external_lastname',
        'external_department',
        'training_position_id',
        'email',
        'external_id',
        'training_schedule_id'
    ];
}
