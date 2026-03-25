<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $table = "provinces";
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
    ];
}
