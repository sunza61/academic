<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Academic\AcademicProject;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
{
    // 🌟 ส่งตัวเลขโครงการรออนุมัติไปให้ทุกหน้า (Views)
    View::composer('*', function ($view) {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $pendingCount = AcademicProject::where('overall_status', 200)
                ->where(function ($q) {
                    $q->whereNull('del_status')->orWhere('del_status', 0);
                })
                ->count();
            
            $view->with('pendingCount', $pendingCount);
        } else {
            $view->with('pendingCount', 0);
        }
    });
}
}
