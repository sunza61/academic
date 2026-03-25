<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    use HasFactory;
    protected $table = "customer_types";
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
        'is_active',
    ];
}
