<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Mail\OTPMail;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotForm()
    {
        return view('auth.passwords.forgot');
    }

    /**
     * Send OTP to student email for password reset
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'ends_with:@nsru.ac.th'],
        ], [
            'email.required' => 'กรุณากรอกอีเมลนักศึกษา',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.ends_with' => 'กรุณาใช้เมลของมหาวิทยาลัย (@nsru.ac.th) เท่านั้น',
        ]);

        // Check if email exists
        if (!User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'ไม่พบอีเมลนี้ในระบบ'], 404);
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in session (valid for 5 minutes)
        Session::put('forgot_password_otp', [
            'code' => $otp,
            'email' => $request->email,
            'expires_at' => now()->addMinutes(5),
        ]);

        try {
            // Send Email using existing OTPMail
            Mail::to($request->email)->send(new OTPMail($otp));

            return response()->json([
                'message' => 'ส่งรหัส OTP เรียบร้อยแล้ว โปรดตรวจสอบที่อีเมลของคุณ',
                'expires_in' => 300 // 5 minutes in seconds
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ไม่สามารถส่งอีเมลได้ในขณะนี้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP and reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'otp_code.required' => 'กรุณากรอกรหัส OTP',
            'otp_code.size' => 'รหัส OTP ต้องมี 6 หลัก',
            'password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'password.min' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'การยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        $otpData = session('forgot_password_otp');

        if (!$otpData || $otpData['email'] !== $request->email || $otpData['code'] != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'รหัส OTP ไม่ถูกต้อง'])->withInput();
        }

        if (now()->isAfter($otpData['expires_at'])) {
            return back()->withErrors(['otp_code' => 'รหัส OTP หมดอายุแล้ว กรุณากดส่งใหม่'])->withInput();
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'ไม่พบผู้ใช้งานนี้ในระบบ'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session
        session()->forget('forgot_password_otp');

        return redirect()->route('login')->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่');
    }
}
