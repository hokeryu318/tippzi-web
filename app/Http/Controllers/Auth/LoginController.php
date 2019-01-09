<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $user = User::where([
            'email' => $request->email,
            'password' => md5($request->password)
        ])->first();

        $user_login = User::where([
            'login_name' => $request->email,
            'password' => md5($request->password)
        ])->first();

        if ($user) {
            $this->guard()->login($user, $request->has('remember'));
            return true;
        } elseif ($user_login) {
            $this->guard()->login($user_login, $request->has('remember'));
            return true;
        }

        return false;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

//    public function login(Request $request)
//    {
//        $user = User::where([
//            'email' => $request->email,
//            'password' => md5($request->password)
//        ])->first();
//
//        if ($user) {
//            auth()->login($user);
//
//        }
////        return false;
////        Auth::login($user);
//
//
////        return redirect()->route('dashboard');
//    }
}
