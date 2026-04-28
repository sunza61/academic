public function handle()
    {
        $today = Carbon::now()->toDateString();
        $this->info("Starting status update for date: $today");

        // 🌟 ดึงเฉพาะโครงการที่ "รอเปิดรับสมัคร(300)", "กำลังรับสมัคร(400)" และ "ปิดรับสมัคร/เตรียมงาน(500)"
        // เราจะไม่ยุ่งกับ 100(ฉบับร่าง), 200(รออนุมัติ), 800(เสร็จสิ้น), 900(ยกเลิก)
        $projects = AcademicProject::whereIn('overall_status', [300, 400, 500])
            ->get();

        $countOpen = 0;
        $countClose = 0;
        $countProcess = 0;

        foreach ($projects as $project) {
            // ตั้งค่าสถานะเริ่มต้นให้เท่ากับสถานะปัจจุบัน
            $newStatus = $project->overall_status;
            
            // ดึงข้อมูลตาราง Training ที่ผูกกันอยู่ (ถ้ามี)
            // *หมายเหตุ: ถ้า Model AcademicProject ผูก relationship ไว้ ใช้ $project->trainingProject ได้เลย
            // แต่ถ้าไม่ได้ผูก ให้คิวรี่เอาตามด้านล่างนี้ครับ
            $training = TrainingProject::where('academic_project_id', $project->id)->first();

            // ----------------------------------------------------------------
            // 🛑 เช็คตามลำดับความสำคัญ (ยึดตามวันที่ปัจจุบัน)
            // ----------------------------------------------------------------

            // 1. ถ้าถึงวัน "เริ่มโครงการ" แล้ว บังคับข้ามไป 600 (อยู่ระหว่างดำเนินการ) ทันที
            if ($today >= $project->start_date) {
                $newStatus = 600;
            } 
            // 2. ถ้ายังไม่ถึงวันเริ่มโครงการ ให้ไปเช็คเรื่อง "วันรับสมัคร" (กรณีมีการรับสมัคร)
            elseif ($training && $training->start_regis_date && $training->end_regis_date) {
                
                // ถ้าวันนี้ "เลย" วันสิ้นสุดรับสมัครไปแล้ว -> 500 (ปิดรับสมัคร)
                if ($today > $training->end_regis_date) {
                    $newStatus = 500;
                } 
                // ถ้าวันนี้อยู่ในช่วงรับสมัครพอดี -> 400 (เปิดรับสมัคร)
                elseif ($today >= $training->start_regis_date && $today <= $training->end_regis_date) {
                    $newStatus = 400;
                }
            }

            // ----------------------------------------------------------------
            // 🔄 ทำการอัปเดต (เมื่อสถานะเปลี่ยนไป และ "ต้องเดินหน้าเท่านั้น" ห้ามถอยหลัง)
            // ----------------------------------------------------------------
            if ($newStatus > $project->overall_status) {
                
                // 1. อัปเดตสถานะในตารางหลัก
                $project->update([
                    'overall_status' => $newStatus,
                    'update_by' => 0 // 0 หรือ null เพื่อสื่อว่าเป็นการทำงานโดย System (Cron Job)
                ]);

                // 2. เก็บ Log ประวัติการทำงานไว้ด้วยว่าระบบเป็นคนเปลี่ยน!
                \App\Models\Academic\AcademicProjectLog::create([
                    'academic_project_id' => $project->id,
                    'user_id'             => 0, // 0 = System
                    'action'              => 'auto_update_status',
                    'status_code'         => $newStatus,
                ]);

                // เก็บสถิติสำหรับ Report ท้าย Command
                if ($newStatus == 400) $countOpen++;
                if ($newStatus == 500) $countClose++;
                if ($newStatus == 600) $countProcess++;
            }
        }

        $this->info("Updated: Open($countOpen), Close($countClose), Processing($countProcess)");
        
        if($countOpen > 0 || $countClose > 0 || $countProcess > 0) {
            Log::info("Academic Scheduler: Status updated on $today. [Open:$countOpen, Close:$countClose, Process:$countProcess]");
        }

        return 0;
    }