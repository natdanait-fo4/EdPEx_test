<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ตรวจสอบว่าผู้ใช้ล็อกอินแล้วและมีสิทธิ์เป็น admin หรือไม่
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // หากไม่มีสิทธิ์ ให้ส่งกลับหน้าแรกพร้อมข้อความเตือน
        return redirect('/')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
    }
}
