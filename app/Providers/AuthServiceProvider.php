<?php

namespace App\Providers;

use App\Models\Academic\AcademicProject;
use App\Models\Project\ProjectContract;
use App\Models\Training\TrainingProject;
use App\Policies\ProjectContractPolicy;
use App\Policies\TrainingProjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        //TrainingProject::class => TrainingProjectPolicy::class,
        TrainingProject::class => TrainingProjectPolicy::class,
        ProjectContract::class => ProjectContractPolicy::class,
        AcademicProject::class => TrainingProjectPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

       // สิทธิ์ Super Admin
       Gate::before(function ($user, $ability) {
        return $user->hasRole('admin') ? true : null;
    });
    }
}
