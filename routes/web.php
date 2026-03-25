<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// นำเข้า Controller ของระบบเดิมที่คุณมี
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\InformController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResearchAreaController;
use App\Http\Controllers\ScopusController;

// นำเข้า Controller ใหม่ของระบบบริการวิชาการที่เราเพิ่งออกแบบ
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectDeliveryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MasterData\EmployerController;
use App\Http\Controllers\MasterData\BudgetCategoryController;
use App\Http\Controllers\MasterData\ProjectTypeController;
use App\Http\Controllers\MasterData\SdgController;
use App\Http\Controllers\MasterData\TargetGroupController;
use App\Http\Controllers\Projects\ContractProjectController;
use App\Http\Controllers\Projects\ProjectSelectionController;
use App\Http\Controllers\Projects\TrainingProjectController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ระบบ Authentication (Login, Register, Logout)
Auth::routes();

// หน้าแรกสุดก่อน Login (ถ้ามีหน้า Landing Page ให้เรียก view('index') ที่นี่)
Route::get('/', function () {
    return view('index');
});

// แก้ไข Route /home ที่ซ้ำกัน ให้เหลือแค่ตัวเดียวสำหรับ Manager/User ทั่วไป
Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| ระบบบริการวิชาการ (ACADEMIC SERVICE)
| ต้อง Login ก่อนถึงจะเข้าได้ เลยเอามาครอบไว้ใน Middleware 'auth'
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // หน้าแรก (Dashboard ของ Admin/ระบบบริการวิชาการ)
    // ใช้ DashboardController หรือจะใช้ HomeController ก็ได้ตามที่คุณสะดวกครับ
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // เมนู "ข้อมูลพื้นฐาน" (Master Data)
    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::resource('employers', EmployerController::class);
        Route::resource('budget-categories', BudgetCategoryController::class);
        Route::resource('project-types', ProjectTypeController::class);
        Route::resource('sdgs', SdgController::class);
        Route::resource('target-groups', TargetGroupController::class);
    });

    // เมนู "จัดโครงการ/รับงานบริการวิชาการ" (สัญญาจ้าง)
    Route::prefix('contracts')->name('contracts.')->group(function () {
        Route::resource('projects', ContractProjectController::class);
    });

    // เมนู "เปิดให้บริการวิชาการ" (งานอบรม ประชุม สัมมนา)
    Route::prefix('trainings')->name('trainings.')->group(function () {
        Route::resource('projects', TrainingProjectController::class);
    });
    Route::post('trainings/projects/ajax/customer-groups', [TrainingProjectController::class, 'storeCustomerGroupAjax'])->name('trainings.projects.store-customer-group-ajax');
    Route::post('trainings/projects/ajax/externals', [TrainingProjectController::class, 'storeExternalAjax'])->name('trainings.projects.store-external-ajax');
    Route::post('/trainings/projects/store-schedule-ajax', [TrainingProjectController::class, 'storeScheduleAjax'])->name('trainings.schedules.storeAjax');
    Route::get('/trainings/schedules/{id}/edit-ajax', [TrainingProjectController::class, 'editScheduleAjax'])->name('trainings.schedules.editAjax');
    Route::delete('/trainings/schedules/{id}/delete-ajax', [TrainingProjectController::class, 'deleteScheduleAjax'])->name('trainings.schedules.deleteAjax');
    Route::post('trainings/projects/ajax/target-groups', [TrainingProjectController::class, 'storeTargetGroupAjax'])->name('trainings.projects.store-target-group-ajax');
    Route::put('trainings/projects/{id}/cancel', [TrainingProjectController::class, 'cancelProject'])->name('trainings.projects.cancel');


    // เมนู "บันทึกรายงาน" 
    Route::prefix('deliveries')->name('deliveries.')->group(function () {
        Route::get('/', [ProjectDeliveryController::class, 'index'])->name('index');
    });

    // เมนู "รายงาน" 
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
    });

    // โซนพนักงานต้อนรับ (เลือกประเภทโครงการ)
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/select-type', [ProjectSelectionController::class, 'index'])->name('select-type');
        Route::get('/gateway/{id}', [ProjectSelectionController::class, 'gateway'])->name('gateway');
    });
});
