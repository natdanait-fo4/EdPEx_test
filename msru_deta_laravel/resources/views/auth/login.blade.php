<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ (Login) | NSRU-MS DETA SHIP</title>
    <!-- Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Prompt Font & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <!-- Login Card -->
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-[440px] p-8 md:p-10 border border-gray-100 relative overflow-hidden">
        
        <!-- ของตกแต่งลวดลายสีม่วงบางๆ ให้อารมณ์ทันสมัย -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-bl-full -z-10"></div>
        
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">NSRU-MS DETA SHIP</h1>
            <p class="text-[13px] text-gray-500 mt-2">ระบบจัดเก็บข้อมูลเพื่อความต้องการอันเป็นเลิศสู่ความสำเร็จ</p>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            
            @if(session('error'))
                <div class="mb-4 bg-red-50 text-red-600 text-[14px] p-3 rounded-lg border border-red-100 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 text-red-600 text-[14px] p-3 rounded-lg border border-red-100 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <!-- Student Email (Username) -->
            <div class="mb-5">
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">อีเมลนักศึกษา (@nsru.ac.th)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-gray-400"></i>
                    </div>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                        placeholder="example.t@nsru.ac.th">
                </div>
            </div>

            <!-- Password Input -->
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <label for="password" class="block text-[15px] font-medium text-gray-700">รหัสผ่าน</label>
                    <a href="#" class="text-[13px] text-[#7e059c] hover:underline font-medium">ลืมรหัสผ่าน?</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" required 
                           class="w-full pl-11 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700"
                           placeholder="••••••••">
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#7e059c] transition-colors">
                        <i id="eye_icon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <script>
                function togglePassword() {
                    const input = document.getElementById('password');
                    const icon = document.getElementById('eye_icon');
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
            </script>

            <!-- Login Button -->
            <button type="submit" class="w-full bg-[#7e059c] hover:bg-[#680482] text-white font-medium py-3.5 rounded-xl transition-all shadow-md shadow-purple-200 hover:shadow-lg hover:-translate-y-0.5 mb-4 flex justify-center items-center text-[15px]">
                <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> เข้าสู่ระบบ
            </button>

            <!-- Cancel Button -->
            <a href="{{ route('home.index') }}" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3.5 border border-gray-300 rounded-xl transition-all flex justify-center items-center mb-6 text-[15px]">
                <i class="fa-solid fa-xmark mr-2 text-gray-400"></i> ยกเลิก
            </a>
        </form>

        <!-- Guest & Register Links -->
        <div class="text-center pt-2 flex flex-col space-y-3">
            <a href="{{ route('register') }}" class="text-sm font-semibold text-[#7e059c] hover:text-[#680482] transition-colors">
                ยังไม่มีบัญชี? สมัครสมาชิกใหม่ที่นี่
            </a>
            <a href="{{ route('home.index') }}" class="text-xs text-gray-500 hover:text-[#7e059c] transition-colors underline decoration-gray-300 hover:decoration-[#7e059c] underline-offset-4">
                เข้าสู่ระบบโดยไม่ลงชื่อเข้าใช้
            </a>
        </div>

    </div>


</body>
</html>
