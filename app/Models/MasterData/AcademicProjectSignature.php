<?php

namespace App\Models\MasterData;

use App\Models\Academic\AcademicProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProjectSignature extends Model
{
    use HasFactory;

    protected $table = 'academic_project_signatures';

    protected $fillable = [
        'academic_project_id',
        'staff_id',
        'signature_role_id',
        'executive_position',
        'sign_order',
    ];

    // โยงไปหาชื่อบทบาท
    public function role()
    {
        return $this->belongsTo(MasterSignatureRole::class, 'signature_role_id');
    }

    // โยงกลับไปหาโปรเจกต์แม่ (สมมติว่า Model หลักอยู่นอกโฟลเดอร์ MasterData)
    public function project()
    {
        return $this->belongsTo(AcademicProject::class, 'academic_project_id');
    }
}
