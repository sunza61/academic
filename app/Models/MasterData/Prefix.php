<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefix extends Model
{
    use HasFactory;
    protected $table = "prefixes";
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
    ];
}
