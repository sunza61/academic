<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    use HasFactory;
    protected $table = "project_types";
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
    ];
}
