<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    // إرسال رمز التحقق عبر الجوال
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
        ]);
        $phone = $request->phone;
        $code = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);
        // حذف الرموز القديمة
        DB::table('password_resets_sms')->where('phone', $phone)->delete();
        // حفظ الرمز الجديد
        DB::table('password_resets_sms')->insert([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // إرسال الرمز عبر SMS (ضع هنا كود Twilio أو مزود SMS)
        // مثال: SmsService::send($phone, "رمز إعادة تعيين كلمة المرور: $code");
        // حالياً سنعرض الرمز في رسالة نجاح (للتجربة فقط)
        return redirect()->route('password.sms.verify')->with('status', "تم إرسال رمز التحقق إلى رقم الجوال ($code)");
    }

    // عرض صفحة إدخال رمز التحقق
    public function showVerifyCodeForm(Request $request)
    {
        return view('auth.verify_code');
    }

    // التحقق من الرمز
    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'code' => 'required',
        ]);
        $record = DB::table('password_resets_sms')
            ->where('phone', $request->phone)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();
        if (!$record) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.']);
        }
        // تخزين رقم الجوال في الجلسة للسماح بتعيين كلمة مرور جديدة
        session(['reset_phone' => $request->phone]);
        return redirect()->route('password.sms.reset');
    }

    // عرض صفحة تعيين كلمة مرور جديدة
    public function showResetForm(Request $request)
    {
        if (!session('reset_phone')) {
            return redirect()->route('password.sms.verify');
        }
        return view('auth.reset_password_sms');
    }

    // تعيين كلمة مرور جديدة
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);
        $phone = session('reset_phone');
        if (!$phone) {
            return redirect()->route('password.sms.verify');
        }
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return redirect()->route('password.sms.verify');
        }
        $user->password = Hash::make($request->password);
        $user->save();
        // حذف رمز التحقق
        DB::table('password_resets_sms')->where('phone', $phone)->delete();
        session()->forget('reset_phone');
        return redirect()->route('login')->with('status', 'تم تعيين كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.');
    }

    // إرسال كود تحقق (OTP) للبريد
    public function sendEmailCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $email = $request->email;
        $code = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);
        // حذف الأكواد القديمة
        DB::table('password_resets_email')->where('email', $email)->delete();
        // حفظ الكود الجديد
        DB::table('password_resets_email')->insert([
            'email' => $email,
            'code' => $code,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // إرسال الكود عبر البريد (ضع هنا كود Notification)
        Mail::raw("رمز إعادة تعيين كلمة المرور الخاص بك هو: $code", function($message) use ($email) {
            $message->to($email)->subject('رمز إعادة تعيين كلمة المرور');
        });
        return redirect()->route('password.email.verify')->with('status', "تم إرسال رمز التحقق إلى بريدك الإلكتروني.");
    }

    // عرض صفحة إدخال كود التحقق للبريد
    public function showEmailVerifyForm(Request $request)
    {
        return view('auth.passwords.verify_email_code');
    }

    // التحقق من كود البريد
    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required',
        ]);
        $record = DB::table('password_resets_email')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();
        if (!$record) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.']);
        }
        // تخزين البريد في الجلسة للسماح بتعيين كلمة مرور جديدة
        session(['reset_email' => $request->email]);
        return redirect()->route('password.email.reset');
    }

    // عرض صفحة تعيين كلمة مرور جديدة للبريد
    public function showEmailResetForm(Request $request)
    {
        if (!session('reset_email')) {
            return redirect()->route('password.email.verify');
        }
        return view('auth.passwords.reset_email');
    }

    // تعيين كلمة مرور جديدة للبريد
    public function resetEmailPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.email.verify');
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.email.verify');
        }
        $user->password = Hash::make($request->password);
        $user->save();
        // حذف كود التحقق
        DB::table('password_resets_email')->where('email', $email)->delete();
        session()->forget('reset_email');
        return redirect()->route('login')->with('status', 'تم تعيين كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.');
    }
}
