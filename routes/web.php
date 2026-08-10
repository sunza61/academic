<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// นำเข้า Controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\InformController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResearchAreaController;
use App\Http\Controllers\ScopusController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectDeliveryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MasterData\EmployerController;
use App\Http\Controllers\MasterData\BudgetCategoryController;
use App\Http\Controllers\MasterData\BudgetExpenseController;
use App\Http\Controllers\MasterData\BudgetIncomeController;
use App\Http\Controllers\MasterData\ExternalController;
use App\Http\Controllers\MasterData\ProjectPositionController;
use App\Http\Controllers\MasterData\ProjectTypeController;
use App\Http\Controllers\MasterData\SdgController;
use App\Http\Controllers\MasterData\TargetGroupController;
use App\Http\Controllers\Projects\ContractProjectController;
use App\Http\Controllers\Projects\ProjectSelectionController;
use App\Http\Controllers\Projects\TrainingProjectController;
use App\Http\Controllers\Projects\LecturerProjectController;
use App\Http\Controllers\Finance\FinanceDashboardController;
use App\Http\Controllers\Plan\PlanDashboardController;


/*
|--------------------------------------------------------------------------
| โซน Public & ระบบ Authentication
|--------------------------------------------------------------------------
*/

// Authentication
Auth::routes();


// =====================================================
// PUBLIC
// =====================================================

Route::get('/', [HomeController::class, 'publicDashboard'])
    ->name('public.dashboard');


// =====================================================
// AUTHENTICATED USER
// =====================================================

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

});


// Laravel Default Home
Route::get('/home', [HomeController::class, 'index'])
    ->name('home');


/*
|--------------------------------------------------------------------------
| ระบบบริการวิชาการ (ACADEMIC SERVICE)
| 🔒 โซน Private: ต้อง Login ก่อนถึงจะเข้าได้
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 🌟 หน้า Dashboard รวมตามสิทธิ์
    //Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/finance/dashboard', [FinanceDashboardController::class, 'index'])->name('finance.dashboard');
    Route::get('/plan/dashboard', [PlanDashboardController::class, 'index'])->name('plan.dashboard');

    // 🌟 โซนข้อมูลพื้นฐาน: ล็อกประตู! เข้าได้เฉพาะ 'admin' หรือ 'staff'
    Route::middleware(['role:admin,staff'])->prefix('master-data')->name('master-data.')->group(function () {
        Route::resource('employers', EmployerController::class);
        Route::resource('budget-categories', BudgetCategoryController::class);
        Route::resource('project-types', ProjectTypeController::class);
        Route::resource('sdgs', SdgController::class);
        Route::resource('target-groups', TargetGroupController::class);
        Route::resource('externals', ExternalController::class);
        Route::resource('project-positions', ProjectPositionController::class);

        Route::resource('budget-incomes', BudgetIncomeController::class);
        Route::post('budget-incomes/main/store-ajax', [BudgetIncomeController::class, 'storeMainAjax'])->name('budget-incomes.storeMainAjax');

        Route::resource('budget-expenses', BudgetExpenseController::class);
        Route::post('budget-expenses/main/store-ajax', [BudgetExpenseController::class, 'storeMainAjax'])->name('budget-expenses.storeMainAjax');
    });

    // 🌟 เลือกประเภทโครงการ
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/select-type', [ProjectSelectionController::class, 'index'])->name('select-type');
        Route::get('/gateway/{id}', [ProjectSelectionController::class, 'gateway'])->name('gateway');
    });

    // 🌟 เมนูสัญญาจ้าง
    Route::prefix('contracts')->name('contracts.')->group(function () {
        Route::resource('projects', ContractProjectController::class);
        Route::post('projects/ajax/externals', [ContractProjectController::class, 'storeExternalAjax'])->name('projects.store-external-ajax');
        Route::post('projects/ajax/target-groups', [ContractProjectController::class, 'storeTargetGroupAjax'])->name('projects.store-target-group-ajax');
        Route::patch('projects/{id}/change-status', [ContractProjectController::class, 'changeStatus'])->name('projects.change-status');
        Route::get('projects/{id}/report', [ContractProjectController::class, 'report'])->name('projects.report');
        Route::post('projects/{id}/report', [ContractProjectController::class, 'saveReport'])->name('projects.save-report');
        Route::put('projects/{id}/cancel', [ContractProjectController::class, 'cancelProject'])->name('projects.cancel');
    });

    // 🌟 เมนูงานอบรม
    Route::prefix('trainings')->name('trainings.')->group(function () {
        Route::resource('projects', TrainingProjectController::class);
        Route::post('projects/ajax/customer-groups', [TrainingProjectController::class, 'storeCustomerGroupAjax'])->name('projects.store-customer-group-ajax');
        Route::post('projects/ajax/externals', [TrainingProjectController::class, 'storeExternalAjax'])->name('projects.store-external-ajax');
        Route::post('projects/store-schedule-ajax', [TrainingProjectController::class, 'storeScheduleAjax'])->name('schedules.storeAjax');
        Route::get('schedules/{id}/edit-ajax', [TrainingProjectController::class, 'editScheduleAjax'])->name('schedules.editAjax');
        Route::delete('schedules/{id}/delete-ajax', [TrainingProjectController::class, 'deleteScheduleAjax'])->name('schedules.deleteAjax');
        Route::post('projects/ajax/target-groups', [TrainingProjectController::class, 'storeTargetGroupAjax'])->name('projects.store-target-group-ajax');
        Route::put('projects/{id}/cancel', [TrainingProjectController::class, 'cancelProject'])->name('projects.cancel');
        Route::patch('projects/{id}/change-status', [TrainingProjectController::class, 'changeStatus'])->name('projects.change-status');
        Route::get('projects/{id}/report', [TrainingProjectController::class, 'report'])->name('projects.report');
        Route::post('projects/{id}/report', [TrainingProjectController::class, 'saveReport'])->name('projects.save-report');
    });

    // 🌟 เมนูวิทยากร (ไม่หักค่าใช้จ่าย)
    Route::prefix('lecturers')->name('lecturers.')->group(function () {
        Route::resource('projects', LecturerProjectController::class);
    });

    // 🌟 เมนูบันทึกรายงาน
    Route::prefix('deliveries')->name('deliveries.')->group(function () {
        Route::get('/', [ProjectDeliveryController::class, 'index'])->name('index');
    });

    // 🌟 เมนูรายงาน
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
    });

    // ==========================================================
    // 👑 โซนผู้ดูแลระบบ: ล็อกเฉพาะ 'admin'
    // ==========================================================
    Route::middleware(['role:admin'])->prefix('admin/approvals')->name('admin.approvals.')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::get('/{id}', [ApprovalController::class, 'show'])->name('show');
        Route::patch('/{id}/approve', [ApprovalController::class, 'approve'])->name('approve');
        Route::patch('/{id}/reject', [ApprovalController::class, 'reject'])->name('reject');
    });
});
