<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPosition extends Model
{
    use HasFactory;
    protected $table = "project_positions";
    protected $fillable = [
        'is_unique',
        'name_th',
        'name_en',
        'description',  
    ];
}
