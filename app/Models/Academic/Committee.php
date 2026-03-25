<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    use HasFactory;
    protected $table = "committees";
    protected $fillable = [
        'academic_project_id',
        'member_type',
        'personnel_id',
        'external_prefix',
        'external_firstname',
        'external_lastname',
        'external_department',
        'project_position_id',
        'email',
        'remuneration_total',
        'external_id'
    ];
}
