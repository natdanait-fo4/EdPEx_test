@extends('layouts.admin')

@section('title', 'Admin Dashboard | NSRU-MS')
@section('header_title', 'ภาพรวมระบบ (Dashboard)')

@section('content')
<div class="dash-welcome-card">
    <div class="dash-welcome-icon">
        <i class="fa-solid fa-gauge-high text-4xl text-[#7e059c] dark:text-purple-400"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">ยินดีต้อนรับสู่ระบบจัดการ NSRU-MS</h2>
    <p class="text-gray-500 dark:text-gray-400 mb-8">คุณอยู่ที่หน้าแรกของส่วนผู้ดูแลระบบ (Admin) สามารถใช้แถบเมนูด้านซ้ายเพื่อไปยังส่วนปฏิบัติการต่างๆ ได้ทันที</p>
    
    <div class="dash-grid">
        <a href="{{ route('admin.qa.index') }}" class="dash-card card-purple">
            <div class="flex items-center justify-between mb-4">
                <div class="dash-card-icon-box bg-purple-100 dark:bg-purple-900/60 text-[#7e059c] dark:text-purple-300">
                    <i class="fa-solid fa-clipboard-question"></i>
                </div>
                <i class="fa-solid fa-arrow-right text-purple-300 dark:text-purple-600 group-hover:text-[#7e059c] transition-colors"></i>
            </div>
            <h3 class="dash-card-title">จัดการคำถาม Q&A</h3>
            <p class="dash-card-desc">ตอบคำถามผู้ใช้ทางหน้าบ้าน จัดการเพิ่ม/ลบ ข้อมูล FAQ ของระบบ</p>
        </a>

        <a href="{{ route('admin.users.index') }}" class="dash-card card-blue">
            <div class="flex items-center justify-between mb-4">
                <div class="dash-card-icon-box bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <i class="fa-solid fa-arrow-right text-blue-300 dark:text-blue-600 group-hover:text-blue-600 transition-colors"></i>
            </div>
            <h3 class="dash-card-title">จัดการผู้ใช้งาน</h3>
            <p class="dash-card-desc">กำหนดสิทธิ์ เพิ่มผู้ดูแลระบบใหม่และแจกแจงบทบาท</p>
        </a>

        <a href="{{ route('admin.complaints.index') }}" class="dash-card card-orange">
            <div class="flex items-center justify-between mb-4">
                <div class="dash-card-icon-box bg-orange-100 dark:bg-orange-900/60 text-orange-600 dark:text-orange-300">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <i class="fa-solid fa-arrow-right text-orange-300 dark:text-orange-600 group-hover:text-orange-600 transition-colors"></i>
            </div>
            <h3 class="dash-card-title">จัดการข้อร้องเรียน</h3>
            <p class="dash-card-desc">ตรวจสอบสถานะการแจ้งเรื่องร้องเรียน และตอบกลับผลการดำเนินงาน</p>
        </a>

        <a href="{{ route('admin.requests.index') }}" class="dash-card card-yellow">
            <div class="flex items-center justify-between mb-4">
                <div class="dash-card-icon-box bg-yellow-100 dark:bg-yellow-900/60 text-yellow-600 dark:text-yellow-300">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <i class="fa-solid fa-arrow-right text-yellow-300 dark:text-yellow-600 group-hover:text-yellow-600 transition-colors"></i>
            </div>
            <h3 class="dash-card-title">จัดการความต้องการ</h3>
            <p class="dash-card-desc">ดูหัวข้อความต้องการและข้อเสนอแนะต่างๆ เพื่อพัฒนาคณะฯ</p>
        </a>

        <div class="dash-card border-2 border-dashed border-green-200 dark:border-green-900/50 hover:border-green-500 transition-all group relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-excel text-8xl text-green-600"></i>
            </div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="dash-card-icon-box bg-green-100 dark:bg-green-900/60 text-green-600 dark:text-green-300">
                    <i class="fa-solid fa-file-excel"></i>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('admin.edpex.generate') }}" method="GET" class="inline">
                        <button type="submit" title="ปรับปรุงข้อมูลล่าสุด" class="p-2 text-gray-400 hover:text-green-600 transition-colors">
                            <i class="fa-solid fa-sync-alt"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.edpex.export') }}" download="EdPEx.xlsx" class="p-2 text-green-300 dark:text-green-600 hover:text-green-600 transition-colors" title="ดาวน์โหลดไฟล์">
                        <i class="fa-solid fa-download"></i>
                    </a>
                </div>
            </div>
            <a href="{{ route('admin.edpex.export') }}" download="EdPEx.xlsx" class="block group">
                <h3 class="dash-card-title relative z-10 group-hover:text-green-600 transition-colors">ดาวน์โหลดข้อมูล EdPEx</h3>
                <p class="dash-card-desc relative z-10 text-green-600/70 dark:text-green-400/70 font-medium">Excel (.xlsx)</p>
                <p class="dash-card-desc relative z-10 mt-1">ไฟล์รวมข้อมูลทั้งหมด (ประเมิน, ร้องเรียน, คำขอ, Q&A) เพื่อนำไปประเมิน EdPEx</p>
            </a>
        </div>
    </div>
</div>
@endsection
