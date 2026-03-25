<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicLocation extends Model
{
    use HasFactory;
    protected $table = "academic_locations";
    protected $fillable = [
        'academic_project_id',
        'location_name',
        'address_detail',
        'province_id',
        'latitude',
        'longitude',
    ];
}
