<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $table = "organizations";
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
        'is_active',
    ];
}
