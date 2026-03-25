<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectHasExpertise extends Model
{
    use HasFactory;
    protected $table = "project_has_expertises";
    protected $fillable = [
        'project_contract_id',
        'project_expertise_id',
    ];
}
