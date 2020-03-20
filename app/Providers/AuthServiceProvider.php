<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Region;
use App\Models\Wereda;
use App\Models\Zone;
use App\Policies\CityPolicies;
use App\Policies\RegionsPolicies;
use App\Policies\UsersPolicies;
use App\Policies\WeredaPolicies;
use App\Policies\ZonePolicies;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Region::class => RegionsPolicies::class,
        Zone::class => ZonePolicies::class,
        Wereda::class => WeredaPolicies::class,
        City::class => CityPolicies::class,
        User::class => UsersPolicies::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        //
    }
}
