<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Enums\AccountTypeEnum;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('view-freelancers', function (User $user) {
            return $user->accountType == AccountTypeEnum::OFFICE_ACCOUNT->value || $user->accountType == AccountTypeEnum::COMPANY_ACCOUNT->value
                ? Response::allow()
                : Response::deny('غير مسموح باظهار المسوقين لك');
        });
    }
}
