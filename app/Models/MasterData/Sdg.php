<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sdg extends Model
{
    use HasFactory;
    protected $table = "sdgs";
    protected $fillable = [
        'icon_url',
        'name_th',
        'name_en',
        'description',
        'is_active'
    ];
}
