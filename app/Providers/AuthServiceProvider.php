<?php

namespace App\Providers;

use App\Models\CallReport;
use App\Models\City;
use App\Models\ContactGroup;
use App\Models\Region;
use App\Models\SubCity;
use App\Models\Wereda;
use App\Models\Zone;
use App\Policies\CallReportPolicies;
use App\Policies\CityPolicies;
use App\Policies\ContactGroupPolicies;
use App\Policies\RegionsPolicies;
use App\Policies\SubCitiesPolicy;
use App\Policies\UsersPolicies;
use App\Policies\WeredaPolicies;
use App\Policies\ZonePolicies;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Prophecy\Call\Call;

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
        SubCity::class => SubCitiesPolicy::class,
        User::class => UsersPolicies::class,
        CallReport::class => CallReportPolicies::class,
        ContactGroup::class => ContactGroupPolicies::class,
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
