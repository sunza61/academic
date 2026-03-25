<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicTargetGroup extends Model
{
    use HasFactory;
    protected $table = "academic_target_groups";
    protected $fillable = [
        'academic_project_id',
        'target_group_id',
        'nationality_id',
        'total',
        'customer_group_id'
    ];
}
