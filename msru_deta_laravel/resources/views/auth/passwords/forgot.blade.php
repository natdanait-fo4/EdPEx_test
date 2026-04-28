<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กู้คืนรหัสผ่าน | NSRU-MS DETA SHIP</title>
    <!-- Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Prompt Font & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 py-12">

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-[440px] p-8 md:p-10 border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-bl-full -z-10"></div>
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">NSRU-MS DETA SHIP</h1>
            <p class="text-[13px] text-gray-500 mt-2">ระบบกู้คืนรหัสผ่านด้วยอีเมลมหาวิทยาลัย</p>
            <div class="mt-4 inline-block px-4 py-1 bg-orange-100 text-orange-600 text-[11px] font-bold rounded-full uppercase tracking-wider">
                ลืมรหัสผ่าน
            </div>
        </div>

        <form action="{{ route('password.reset.submit') }}" method="POST" class="space-y-5">
            @csrf

            @if(session('error'))
                <div class="mb-4 bg-red-50 text-red-600 text-[13px] p-3 rounded-lg border border-red-100 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 text-red-600 text-[13px] p-3 rounded-lg border border-red-100 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <!-- Email Input -->
            <div>
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">อีเมลนักศึกษา (@nsru.ac.th)</label>
                <div class="flex space-x-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                            placeholder="example.t@nsru.ac.th">
                    </div>
                    <button type="button" id="send_otp_btn" onclick="sendOTP()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-[#7e059c] font-semibold rounded-xl transition-all text-[12px] border border-gray-200 whitespace-nowrap min-w-[100px]">
                        ส่งรหัส OTP
                    </button>
                </div>
                <p id="otp_timer" class="text-[11px] text-gray-500 mt-1 hidden">ส่งอีกครั้งได้ใน <span id="timer_count">60</span> วินาที</p>
            </div>

            <!-- OTP & Password Section (Hidden by Default) -->
            <div id="reset_section" class="{{ old('otp_code') ? '' : 'hidden' }} animate-fade-in space-y-5">
                <div class="border-t border-dashed border-gray-200 pt-5"></div>

                <!-- OTP Code -->
                <div>
                    <label class="block text-[14px] font-medium text-gray-700 mb-1.5">รหัสยืนยัน OTP (6 หลัก) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-shield-check text-purple-400"></i>
                        </div>
                        <input type="text" name="otp_code" id="otp_code" maxlength="6" value="{{ old('otp_code') }}" {{ old('otp_code') ? 'required' : '' }}
                            class="w-full pl-11 pr-4 py-2.5 border-2 border-purple-100 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-center tracking-[0.5em] font-bold text-gray-800 text-[18px]"
                            placeholder="000000">
                    </div>
                    <p class="text-[11px] text-purple-600 mt-1"><i class="fa-solid fa-circle-info mr-1"></i> รหัสผ่านชั่วคราวถูกส่งไปที่อีเมลของคุณแล้ว (มีอายุ 5 นาที)</p>
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-[14px] font-medium text-gray-700 mb-1.5">รหัสผ่านใหม่ <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" {{ old('otp_code') ? 'required' : '' }}
                            class="w-full pl-11 pr-12 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                            placeholder="ตั้งรหัสผ่านใหม่อย่างน้อย 8 ตัวอักษร">
                        <button type="button" onclick="togglePassword('password', 'eye_icon_1')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#7e059c] transition-colors">
                            <i id="eye_icon_1" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-[14px] font-medium text-gray-700 mb-1.5">ยืนยันรหัสผ่านใหม่ <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-shield-check text-gray-400"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" {{ old('otp_code') ? 'required' : '' }}
                            class="w-full pl-11 pr-12 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                            placeholder="ยืนยันรหัสผ่านใหม่อีกครั้ง">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye_icon_2')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#7e059c] transition-colors">
                            <i id="eye_icon_2" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-[#7e059c] hover:bg-[#680482] text-white font-medium py-3 rounded-xl transition-all shadow-md shadow-purple-100 text-[15px]">
                    <i class="fa-solid fa-key mr-2"></i> เปลี่ยนรหัสผ่าน
                </button>
            </div>

            <!-- Cancel Button -->
            <div class="pt-2">
                <a href="{{ route('login') }}"
                    class="w-full bg-white hover:bg-gray-50 text-gray-600 font-medium py-3 border border-gray-300 rounded-xl transition-all flex justify-center items-center text-[14px]">
                    <i class="fa-solid fa-arrow-left mr-2 text-gray-400"></i> กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function sendOTP() {
            const email = document.getElementById('email').value;
            const btn = document.getElementById('send_otp_btn');
            const timerText = document.getElementById('otp_timer');
            const timerCount = document.getElementById('timer_count');
            const resetSection = document.getElementById('reset_section');
            const passwordInputs = resetSection.querySelectorAll('input');

            if (!email || !email.endsWith('@nsru.ac.th')) {
                alert('กรุณากรอกอีเมลนักศึกษา @nsru.ac.th ให้ถูกต้อง');
                return;
            }

            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังส่ง...';

            // Call API
            fetch("{{ route('password.send_otp') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json().then(data => ({status: response.status, body: data})))
            .then(res => {
                if (res.status === 200) {
                    // Show Success
                    alert(res.body.message);
                    resetSection.classList.remove('hidden');
                    
                    // Set required for inputs
                    passwordInputs.forEach(input => input.required = true);

                    // Start Timer
                    let seconds = 60;
                    timerText.classList.remove('hidden');
                    btn.classList.add('bg-gray-50', 'text-gray-400');

                    const interval = setInterval(() => {
                        seconds--;
                        timerCount.textContent = seconds;
                        if (seconds <= 0) {
                            clearInterval(interval);
                            btn.disabled = false;
                            btn.innerHTML = 'ส่งรหัสอีกครั้ง';
                            btn.classList.remove('bg-gray-50', 'text-gray-400');
                            timerText.classList.add('hidden');
                        }
                    }, 1000);
                } else {
                    alert(res.body.message || 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                    btn.disabled = false;
                    btn.innerHTML = 'ส่งรหัส OTP';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                btn.disabled = false;
                btn.innerHTML = 'ส่งรหัส OTP';
            });
        }
    </script>
</body>
</html>
