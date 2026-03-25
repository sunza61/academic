<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrganizations extends Model
{
    use HasFactory;
    protected $table = "client_organizations";
    protected $fillable = [
        'academic_project_id',
        'organization_id',
        'service_years',
    ];
}
