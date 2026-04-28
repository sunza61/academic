<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class External extends Model
{
    use HasFactory;
    protected $table = "externals";
    protected $fillable = [
        'prefix_id',
        'firstname',
        'lastname',
        'department',
        'phone',
        'email',
        'description',
        'is_active',
    ];

    public function prefix()
    {
        return $this->belongsTo(Prefix::class, 'prefix_id');
    }
}
