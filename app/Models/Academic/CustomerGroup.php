<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;
    protected $table = "customer_groups";
    protected $fillable = [
        'academic_project_id',
        'customer_type_id',
        'name_th',
        'name_en',
        'description',
    ];
}
