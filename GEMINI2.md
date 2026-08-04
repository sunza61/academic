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
