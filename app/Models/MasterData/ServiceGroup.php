<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceGroup extends Model
{
    use HasFactory;
    protected $table = "service_groups";
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
        'is_active',
    ];
}
