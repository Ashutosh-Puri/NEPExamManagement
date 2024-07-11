<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Admissiondata;
use Illuminate\Support\Facades\Gate;
use App\Policies\Faculty\FacultyPolicy;
use App\Policies\User\AdmissionData\AdmissionDataPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Admissiondata::class => AdmissionDataPolicy::class,
        Faculty::class => FacultyPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        //  Rolls
        //  
        //  1	Super Admin
        //  2	Admin
        //  3	Principal
        //  4	CEO
        //  5	Head
        //  6	Teaching
        //  7	Co Ordinator
        //  8	Supervisor
        //  9	Assistant To Supervisor
        //  10	Assistant CAP Director
        //  11	Admin Clerk
        //  12	Cap Clerk
        //  13	Clerk
        //  14	Admission Clerk
        //  15	Non Teaching


        //  Role Types
        //  1 Super Admin
        //  2 Teaching
        //  3 Non Teaching
        //  4 Management Member
        //  5 Other

        //*****************************************************************************************************************//
        //                                                                                                                 //
        //     Super  Admin Gates                                                                                          //
        //                                                                                                                 //
        //*****************************************************************************************************************//


        Gate::before(function ($user, $ability) {
            if (auth()->guard('user')->check()) {
                return $user->role_id==1 ? true : null;
            }
            return null;
        });



        //*****************************************************************************************************************//
        //                                                                                                                 //
        //          User  Gates                                                                                            //
        //                                                                                                                 //
        //*****************************************************************************************************************//


        Gate::define('User Super Admin', function (User $user) {
            return $user->role_id === 1;
        });

        Gate::define('User Admin', function (User $user) {
            return $user->role_id === 2;
        });

        Gate::define('User Principal', function (User $user) {
            return $user->role_id === 3;
        });

        Gate::define('User CEO',function (User $user) {
            return $user->role_id === 4;
        });

        Gate::define('User Head', function (User $user) {
            return $user->role_id === 5;
        });

        Gate::define('User Teaching', function (User $user) {
            return $user->role_id === 6;
        });

        Gate::define('User Co Ordinator', function (User $user) {
            return $user->role_id === 7;
        });

        Gate::define('User Supervisor', function (User $user) {
            return $user->role_id === 8;
        });

        Gate::define('User Assistant To Supervisor', function (User $user) {
            return $user->role_id === 9;
        });

        Gate::define('User Assistant CAP Director', function (User $user) {
            return $user->role_id === 10;
        });

        Gate::define('User Admin Clerk', function (User $user) {
            return $user->role_id === 11;
        });

        Gate::define('User CAP Clerk', function (User $user) {
            return $user->role_id === 12;
        });

        Gate::define('User Clerk', function (User $user) {
            return $user->role_id === 13;
        });

        Gate::define('User Admission Clerk', function (User $user) {
            return $user->role_id === 14;
        });

        Gate::define('User Non Teaching', function (User $user) {
            return $user->role_id === 15;
        });

        //*****************************************************************************************************************//
        //                                                                                                                 //
        //          Faculty  Gates                                                                                         //
        //                                                                                                                 //
        //*****************************************************************************************************************//


        Gate::define('Super Admin', function (Faculty $faculty) {

            $allowedRoleIds = [1];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();
        });

        Gate::define('Admin', function (Faculty $faculty) {

            $allowedRoleIds = [2];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Principal', function (Faculty $faculty) {

            $allowedRoleIds = [3];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });


        Gate::define('CEO', function (Faculty $faculty) {

            $allowedRoleIds = [4];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Head', function (Faculty $faculty) {

            $allowedRoleIds = [5];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Teacher', function (Faculty $faculty) {

            $allowedRoleIds = [6];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Co Ordinator', function (Faculty $faculty) {

            $allowedRoleIds = [7];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Supervisor', function (Faculty $faculty) {

            $allowedRoleIds = [8];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Assistant To Supervisor', function (Faculty $faculty) {

            $allowedRoleIds = [9];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Assistant CAP Director', function (Faculty $faculty) {

            $allowedRoleIds = [10];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Admin Clerk', function (Faculty $faculty) {

            $allowedRoleIds = [11];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('CAP Clerk', function (Faculty $faculty) {

            $allowedRoleIds = [12];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Clerk', function (Faculty $faculty) {

            $allowedRoleIds = [13];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Admission Clerk', function (Faculty $faculty) {

            $allowedRoleIds = [14];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

        Gate::define('Non Teaching', function (Faculty $faculty) {

            $allowedRoleIds = [15];

            return $faculty->roles->where('pivot.status', 1)->pluck('id')->intersect($allowedRoleIds)->isNotEmpty();

        });

    }
}