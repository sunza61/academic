<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingScheduleDocument extends Model
{
    use HasFactory;
    protected $table = "training_schedule_documents";

    protected $fillable = [
        'training_schedule_id', // รหัสกิจกรรม (เชื่อมกับตาราง training_schedules)
        'document_name',        // ชื่อเอกสารที่โชว์ให้ User เห็น (เช่น "สไลด์บรรยายช่วงเช้า", "ใบงานที่ 1")
        'file_path',            // ที่เก็บไฟล์ในระบบ (เช่น 'schedules/documents/17045432_slide.pdf')
        'file_type',            // นามสกุลไฟล์ (เช่น pdf, docx, pptx) - เอาไว้โชว์ไอคอนให้สวยๆ
        'file_size',            // ขนาดไฟล์ (Byte/KB) - เผื่ออนาคตเอาไว้ลิมิตการโหลด
    ];

    // สร้าง Relation กลับไปหากิจกรรมแม่ (เผื่อตอน Query ดึงข้อมูลง่ายๆ)
    public function schedule()
    {
        return $this->belongsTo(TrainingSchedules::class, 'training_schedule_id', 'id');
    }
}
