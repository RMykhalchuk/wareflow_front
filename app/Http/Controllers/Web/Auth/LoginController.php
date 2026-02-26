<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class LoginController extends Controller
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(): string
    {
        return 'login';
    }

    /**
     * @psalm-return array{password: mixed, phone?: mixed, email?: mixed}
     */
    protected function credentials(Request $request): array
    {
        $login = $request->get('login');
        $res = [
            'password' => $request->get('password')
        ];

        $validator = Validator::make(['email' => $login], [
            'email' => 'required|email'
        ]);

        if ($validator->passes()) {
            $res['email'] = $login;
        } else {
            $res['phone'] = $login;
        }

        return $res;
    }

    public function showLoginForm(): \Illuminate\Contracts\View\View
    {
        return view('auth.loginNew');
    }
}
