<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSignatureRole extends Model
{
    use HasFactory;
    protected $table = 'master_signature_roles';

    protected $fillable = [
        'name_th',
        'is_active',
    ];
}
