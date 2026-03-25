<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetGroup extends Model
{
    use HasFactory;
    protected $table = "target_groups";
    protected $fillable = [
        'name_th',
        'name_en',
        'description',
        'parent_id',  // เพิ่มเข้ามาใหม่
        'level',      // เพิ่มเข้ามาใหม่
        'group_type', // เพิ่มเข้ามาใหม่
        'is_active'
    ];

    // ==========================================
    // 🌟 สิ่งที่ระบบฟ้องว่าหาไม่เจอ คือฟังก์ชันนี้ครับ 🌟
    // ==========================================
    
    // 1. ความสัมพันธ์ชี้กลับไปหาตัวแม่ (Parent)
    public function parent()
    {
        // อย่าลืมใส่คลาสให้ตรงกับ namespace ปัจจุบันด้วยนะครับ
        return $this->belongsTo(TargetGroup::class, 'parent_id');
    }

    // 2. ความสัมพันธ์ดึงลูกๆ ชั้นถัดไป (Children)
    public function children()
    {
        return $this->hasMany(TargetGroup::class, 'parent_id');
    }

    // 3. กวาดลูกหลานทั้งหมด (Recursive)
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    // 4. Accessor สำหรับสร้างเส้นทาง Breadcrumb (เช่น A > B > C)
    public function getFullPathAttribute()
    {
        $path = [$this->name_th];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name_th);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }
}
