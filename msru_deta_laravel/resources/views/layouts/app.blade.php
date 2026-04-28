<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NSRU-MS DETA SHIP')</title>
    <!-- เรียกใช้งาน Font Awesome สำหรับไอคอน -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- เรียกใช้งานฟอนต์ Prompt จาก Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="{{ asset('theme.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    @php
        $isLoggedIn = request('login') == '1' || auth()->check();
    @endphp
    <!-- แถบนำทางด้านบน -->
    <nav class="navbar">
        <div class="container navbar-container">
            <div class="flex items-center">
                <a class="logo" href="{{ url('/') }}{{ $isLoggedIn ? '?login=1' : '' }}" style="display: flex; align-items: center; gap: 12px; height: 100%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px; width: auto; object-fit: contain;">
                    <span style="font-weight: 700; line-height: 1;">NSRU-MS DETA SHIP</span>
                </a>
            </div>
            <div class="nav-right">
                <button onclick="toggleDarkMode()" class="theme-btn" title="สลับโหมดสว่าง/มืด">
                    <i class="fa-solid fa-moon" id="theme-icon"></i>
                </button>
                @if($isLoggedIn)
                    <a href="{{ route('logout') }}" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> ออกจากระบบ</a>
                @else
                    <a href="{{ route('register') }}" class="login-btn" style="background-color: transparent; border: 1px solid white; color: white; margin-right: 10px; padding: 5px 15px; border-radius: 20px; transition: 0.3s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='transparent'"><i class="fa-solid fa-user-plus"></i> สมัครสมาชิก</a>
                    <a href="{{ route('login') }}" class="login-btn"><i class="fa-solid fa-arrow-right-to-bracket"></i> เข้าสู่ระบบ</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- เนื้อหาหลัก -->
    @yield('content')

    <!-- ส่วนท้ายสุดของหน้า -->
    @include('layouts.footer')

    <!-- Scroll to Top Button -->
    <button id="scrollTopBtn" title="กลับขึ้นบนสุด" 
            style="display: none; position: fixed; bottom: 30px; right: 30px; z-index: 99; font-size: 22px; border: none; outline: none; background-color: #7e059c; color: white; cursor: pointer; width: 50px; height: 50px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.25); transition: all 0.3s;" 
            onmouseover="this.style.backgroundColor='#680482'; this.style.transform='scale(1.1)'" 
            onmouseout="this.style.backgroundColor='#7e059c'; this.style.transform='scale(1)'" 
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <script>
        // ควบคุมการแสดงผลปุ่มเลื่อนกลับขึ้นบนสุด
        window.addEventListener('scroll', function() {
            let topBtn = document.getElementById("scrollTopBtn");
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                topBtn.style.display = "block";
            } else {
                topBtn.style.display = "none";
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
