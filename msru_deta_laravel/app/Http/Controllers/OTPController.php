<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\OTPMail;
use App\Models\User;

class OTPController extends Controller
{
    /**
     * Send OTP to student email
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

        // Check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'อีเมลนี้ถูกใช้งานไปแล้ว'], 422);
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in session (valid for 5 minutes)
        Session::put('register_otp', [
            'code' => $otp,
            'email' => $request->email,
            'expires_at' => now()->addMinutes(5),
        ]);

        try {
            // Send Email
            Mail::to($request->email)->send(new OTPMail($otp));

            return response()->json([
                'message' => 'ส่งรหัส OTP เรียบร้อยแล้ว โปรดตรวจสอบที่เมลของคุณ (หรือโฟลเดอร์ Junk/Spam)',
                'expires_in' => 300 // 5 minutes in seconds
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ไม่สามารถส่งอีเมลได้ในขณะนี้: ' . $e->getMessage()
            ], 500);
        }
    }
}
