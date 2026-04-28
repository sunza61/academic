<?php

namespace App\Models;

use App\Models\Academic\AcademicProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProjectLog extends Model
{
    use HasFactory;
    protected $table = 'academic_project_logs';

    protected $fillable = [
        'academic_project_id',
        'user_id',
        'action',
        'status_code',
        'comment',
    ];
    // ดึงชื่อคนทำรายการ
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 📌 Relation โยงกลับไปหาโปรเจกต์แม่
    public function project()
    {
        return $this->belongsTo(AcademicProject::class, 'academic_project_id');
    }
}
