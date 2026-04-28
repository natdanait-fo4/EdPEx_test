@extends('layouts.admin')

@section('title', 'เปลี่ยนรหัสผ่าน | Admin Panel')
@section('header_title', 'ข้อมูลส่วนตัว (Profile)')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                <i class="fa-solid fa-key mr-2 text-[#7e059c]"></i> เปลี่ยนรหัสผ่านใหม่
            </h2>
        </div>
        
        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 p-4 rounded-xl border border-green-100 dark:border-green-800 flex items-center">
                    <i class="fa-solid fa-circle-check mr-3"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-4 rounded-xl border border-red-100 dark:border-red-800">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">รหัสผ่านปัจจุบัน</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-transparent outline-none transition-all dark:text-gray-200"
                               placeholder="กรอกรหัสผ่านปัจจุบัน">
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-700">

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">รหัสผ่านใหม่</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-shield-halved text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-transparent outline-none transition-all dark:text-gray-200"
                               placeholder="อย่างน้อย 8 ตัวอักษร (A-z, 0-9)">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">* ต้องมีตัวพิมพ์ใหญ่ (A-Z) ตัวพิมพ์เล็ก (a-z) และตัวเลข (0-9)</p>
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ยืนยันรหัสผ่านใหม่</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-check-double text-gray-400"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-transparent outline-none transition-all dark:text-gray-200"
                               placeholder="กรอกรหัสผ่านใหม่อีกครั้ง">
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.index') }}" class="px-6 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit" class="bg-[#7e059c] hover:bg-[#680482] text-white px-8 py-3 rounded-xl font-medium transition-all shadow-lg shadow-purple-100 dark:shadow-none hover:-translate-y-0.5">
                        ยืนยันการเปลี่ยนรหัสผ่าน
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
