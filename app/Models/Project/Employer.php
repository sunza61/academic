<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;
    protected $table = "employers";
    protected $fillable = [
        'employer_type_id',
        'name_th',
        'address',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
