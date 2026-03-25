<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpectedOutcomes extends Model
{
    use HasFactory;
    protected $table = "expected_outcomes";
    protected $fillable = [
        'academic_project_id',
        'description',
    ];
}
