<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();

            $id = Auth::user()->Id;
            $user = User::find($id);

            $password = $user->password;
            $old_password = $inputs['old_password'];


            return $password == md5($old_password);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
