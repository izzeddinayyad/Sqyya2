<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
//    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function redirectTo()
    {
        $role = auth()->user()->role;
        switch ($role) {
            case 'org_owner':
                return route('organization.dashboard');
            case 'representative':
                return route('delegate.dashboard');
            case 'driver':
                return route('driver.dashboard');
            default:
                return '/home';
        }
    }

    public function login(Request $request)
    {
        Log::info('بدأت دالة تسجيل الدخول');
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        Log::info('تم التحقق من الفاليديشن');

        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            Log::info('البريد غير موجود');
            return back()->withInput()->with('error', 'البريد الإلكتروني غير صحيح');
        }
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            Log::info('كلمة المرور غير صحيحة');
            return back()->withInput()->with('error', 'كلمة المرور غير صحيحة');
        }
        Log::info('تم تسجيل الدخول بنجاح');
        return redirect()->intended($this->redirectTo());
    }
}
