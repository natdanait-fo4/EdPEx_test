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
    <style>
        @media (max-width: 640px) {
            .hide-on-mobile { display: none !important; }
            .mobile-px-2 { padding-left: 8px !important; padding-right: 8px !important; }
        }
    </style>
    <!-- แถบนำทางด้านบน -->
    <nav class="navbar">
        <div class="container navbar-container" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="flex items-center" style="min-width: 0;">
                <a class="logo" href="{{ url('/') }}{{ $isLoggedIn ? '?login=1' : '' }}" style="display: flex; align-items: center; gap: 12px; height: 100%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px; width: auto; object-fit: contain;">
                    <span style="font-weight: 700; line-height: 1;" class="hide-on-mobile">NSRU-MS DETA SHIP</span>
                </a>
            </div>
            <div class="nav-right" style="display: flex; align-items: center; flex-shrink: 0;">
                <button onclick="toggleDarkMode()" class="theme-btn" title="สลับโหมดสว่าง/มืด" style="margin-right: 10px;">
                    <i class="fa-solid fa-moon" id="theme-icon"></i>
                </button>
                @if($isLoggedIn)
                    @if(auth()->check())
                        <div style="position: relative; display: inline-block; margin-right: 15px;">
                            <button onclick="toggleProfileDropdown(event)" style="background: transparent; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; display: flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid white;">
                                @else
                                    <i class="fa-solid fa-user-circle"></i>
                                @endif
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="profileDropdown" style="display: none; position: absolute; top: 45px; right: -20px; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); width: 280px; z-index: 1000; overflow: hidden; text-align: left; cursor: default;">
                                <!-- Banner Header -->
                                <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); height: 75px; position: relative;">
                                    <!-- Profile Avatar -->
                                    <div style="position: absolute; bottom: -30px; left: 20px; width: 70px; height: 70px; background: white; border-radius: 50%; padding: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                        <div style="width: 100%; height: 100%; background: #7e059c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; overflow: hidden;">
                                            @if(auth()->user()->avatar)
                                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fa-solid fa-user"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- Profile Info -->
                                <div style="padding: 40px 20px 20px 20px;">
                                    <div style="font-size: 1.25rem; font-weight: 700; margin-bottom: 2px; color: #111827;">{{ auth()->user()->fullname ?? 'ผู้ใช้งาน' }}</div>
                                    <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 15px;">{{ auth()->user()->username ?? auth()->user()->email }}</div>
                                    <hr style="border: none; border-top: 1px solid #e5e7eb; margin-bottom: 15px;">
                                    
                                    <!-- Action Button -->
                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.index') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px; background: #f3f4f6; color: #374151; border-radius: 8px; text-decoration: none; font-weight: 600; transition: 0.2s;" onmouseover="this.style.background='#e5e7eb'; this.style.color='#111827'" onmouseout="this.style.background='#f3f4f6'; this.style.color='#374151'">
                                        <i class="fa-solid fa-gauge"></i> แดชบอร์ดแอดมิน
                                    </a>
                                    <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px; background: #f3f4f6; color: #374151; border-radius: 8px; text-decoration: none; font-weight: 600; transition: 0.2s; margin-top: 8px;" onmouseover="this.style.background='#e5e7eb'; this.style.color='#111827'" onmouseout="this.style.background='#f3f4f6'; this.style.color='#374151'">
                                        <i class="fa-solid fa-pen"></i> แก้ไขโปรไฟล์แอดมิน
                                    </a>
                                    @else
                                    <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px; background: #f3f4f6; color: #374151; border-radius: 8px; text-decoration: none; font-weight: 600; transition: 0.2s;" onmouseover="this.style.background='#e5e7eb'; this.style.color='#111827'" onmouseout="this.style.background='#f3f4f6'; this.style.color='#374151'">
                                        <i class="fa-solid fa-pen"></i> แก้ไขโปรไฟล์
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="#" class="login-btn" style="background-color: transparent; border: 1px solid white; color: white; margin-right: 10px; padding: 5px 12px; border-radius: 20px; transition: 0.3s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='transparent'" title="โปรไฟล์"><i class="fa-solid fa-user"></i></a>
                    @endif
                    <a href="{{ route('logout') }}" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> <span class="hide-on-mobile">ออกจากระบบ</span></a>
                @else
                    <a href="{{ route('register') }}" class="login-btn mobile-px-2" style="background-color: transparent; border: 1px solid white; color: white; margin-right: 10px; padding: 5px 15px; border-radius: 20px; transition: 0.3s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='transparent'"><i class="fa-solid fa-user-plus"></i> <span class="hide-on-mobile">สมัครสมาชิก</span></a>
                    <a href="{{ route('login') }}" class="login-btn mobile-px-2"><i class="fa-solid fa-arrow-right-to-bracket"></i> <span class="hide-on-mobile">เข้าสู่ระบบ</span></a>
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
        // Profile Dropdown Toggle Logic
        function toggleProfileDropdown(event) {
            if(event) event.stopPropagation();
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            }
        }

        // Close dropdown when clicking anywhere else
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && dropdown.style.display === 'block') {
                // If the click is inside the dropdown menu, don't close it
                if (!dropdown.contains(event.target)) {
                    dropdown.style.display = 'none';
                }
            }
        });

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
