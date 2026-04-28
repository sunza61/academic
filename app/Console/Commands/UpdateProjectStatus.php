<?php

namespace App\Console\Commands;

use App\Models\Academic\AcademicProject;
use App\Models\AcademicProjectLog;
use App\Models\Training\TrainingProject;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateProjectStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'academic:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'อัปเดตสถานะโครงการตามวันที่กำหนด (เปิดรับสมัคร, ปิดรับสมัคร, เริ่มโครงการ)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();
        $this->info("Starting status update for date: $today");

        // 🌟 เพิ่ม 600 เข้าไปในรายการที่ต้องตรวจสอบด้วย
        $projects = AcademicProject::whereIn('overall_status', [300, 400, 500, 600])
            ->get();

        $countOpen = 0;
        $countClose = 0;
        $countProcess = 0;
        $countWaitReport = 0; // สำหรับสถานะ 700

        foreach ($projects as $project) {
            $newStatus = $project->overall_status;
            $training = TrainingProject::where('academic_project_id', $project->id)->first();

            // ----------------------------------------------------------------
            // 🛑 เช็คตามลำดับความสำคัญ (ยึดตามวันที่ปัจจุบัน)
            // ----------------------------------------------------------------

            // 1. [สถานะ 700] : ถ้าวันนี้ "เลย" วันสิ้นสุดโครงการ (end_date) มาแล้ว
            if ($today > $project->end_date) {
                $newStatus = 700;
            } 
            // 2. [สถานะ 600] : ถ้าวันนี้ถึงวัน "เริ่มโครงการ" (start_date) แล้ว (และยังไม่เลย end_date)
            elseif ($today >= $project->start_date) {
                $newStatus = 600;
            } 
            // 3. [สถานะ 400-500] : เช็คเรื่องการรับสมัคร (กรณีสถานะยังไม่ถึง 600)
            elseif ($training && $training->start_regis_date && $training->end_regis_date) {
                if ($today > $training->end_regis_date) {
                    $newStatus = 500;
                } elseif ($today >= $training->start_regis_date && $today <= $training->end_regis_date) {
                    $newStatus = 400;
                }
            }

           // ----------------------------------------------------------------
            // 🔄 อัปเดตเมื่อสถานะเปลี่ยน (ต้องเดินหน้าเท่านั้น)
            // ----------------------------------------------------------------
            if ($newStatus > $project->overall_status) {
                
                $project->update([
                    'overall_status' => $newStatus,
                    'update_by' => null // 🌟 เปลี่ยนจาก 0 เป็น null
                ]);

                // เก็บ Log การเปลี่ยนสถานะ
                AcademicProjectLog::create([
                    'academic_project_id' => $project->id,
                    'user_id'             => null, // 🌟 เปลี่ยนจาก 0 เป็น null
                    'action'              => 'auto_update_status',
                    'status_code'         => $newStatus,
                ]);

                if ($newStatus == 400) $countOpen++;
                if ($newStatus == 500) $countClose++;
                if ($newStatus == 600) $countProcess++;
                if ($newStatus == 700) $countWaitReport++;
            }
        }

        $this->info("Updated: Open($countOpen), Close($countClose), In-Progress($countProcess), Finished-WaitReport($countWaitReport)");
        
        if($countOpen > 0 || $countClose > 0 || $countProcess > 0 || $countWaitReport > 0) {
            Log::info("Academic Scheduler: Status updated on $today. [Open:$countOpen, Close:$countClose, Process:$countProcess, WaitReport:$countWaitReport]");
        }

        return 0;
    }
}