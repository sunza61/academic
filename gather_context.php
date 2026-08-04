<?php
// ชื่อไฟล์: gather_context.php
// วิธีรัน: พิมพ์คำสั่ง php gather_context.php ใน Terminal

// 1. ระบุโฟลเดอร์ที่ต้องการให้ AI อ่าน (เพิ่ม-ลดได้ตามต้องการ)
$targetDirectories = [
    'app',
    'routes',
    'resources/views',
    'database/migrations',
    'public/js'
];

// 2. ระบุนามสกุลไฟล์ที่ต้องการดึงมา (กรองพวกรูปภาพ หรือไฟล์แปลกๆ ออก)
$allowedExtensions = ['php', 'js', 'css', 'html', 'json'];

// 3. ชื่อไฟล์ผลลัพธ์ที่จะได้
$outputFile = 'GEMINI.md';

$outputContent = "# รายละเอียด Source Code ของโปรเจกต์\n\n";
$outputContent .= "> ไฟล์นี้ถูกสร้างขึ้นอัตโนมัติเพื่อใช้เป็น Context สำหรับ Gemini AI\n\n";

$fileCount = 0;

foreach ($targetDirectories as $dir) {
    if (!is_dir($dir)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            continue;
        }

        $ext = strtolower($file->getExtension());
        
        if (in_array($ext, $allowedExtensions)) {
            $filePath = $file->getPathname();
            // จัด Format ให้อ่านง่ายใน Markdown
            $filePathDisplay = str_replace('\\', '/', $filePath); 
            $content = file_get_contents($filePath);
            
            $outputContent .= "## File: `{$filePathDisplay}`\n";
            $outputContent .= "```{$ext}\n";
            $outputContent .= $content . "\n";
            $outputContent .= "```\n\n";
            
            $fileCount++;
        }
    }
}

file_put_contents($outputFile, $outputContent);

echo "✅ รวบรวมไฟล์สำเร็จ!\n";
echo "📂 จำนวนไฟล์ที่อ่าน: {$fileCount} ไฟล์\n";
echo "📄 ไฟล์ผลลัพธ์ถูกบันทึกไว้ที่: {$outputFile}\n";
echo "🚀 ตอนนี้คุณสามารถนำไฟล์ {$outputFile} ไปโยนใส่ Gemini CLI ได้เลยครับ!\n";
?>