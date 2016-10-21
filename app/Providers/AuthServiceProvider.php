<?php

namespace App\Providers;

use App\Capability;
use App\User;
use Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        /**
         * Check if user is allowed to access an arbitrary user object.
         */
        Gate::define(
            'get-user',
            function (User $user) {
                return $user->hasCapability(Capability::VIEW_USER_NAMES);
            }
        );

        /**
         * Check if user is allowed to update a user object.
         */
        Gate::define(
            'update-user',
            function (User $user, User $subject) {
                return $user->hasCapability(Capability::MANAGE_USERS) || ($user == $subject);
            }
        );
    }
}
