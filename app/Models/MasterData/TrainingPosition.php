<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingPosition extends Model
{
    use HasFactory;
    protected $table = "training_positions";
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
    ];
}
