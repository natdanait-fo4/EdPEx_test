<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NSRU-MS DETA SHIP')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
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
    <!-- หน้าจอโหลดแบบ Skeleton (Skeleton Loading Screen) -->
    <div id="skeleton-loader" style="position: fixed; inset: 0; z-index: 99999; display: flex; flex-direction: column; transition: opacity 0.25s ease-in-out;" class="bg-gray-50 dark:bg-gray-900">
        <!-- Navbar Skeleton -->
        <div style="height: 64px; display: flex; justify-content: space-between; align-items: center; padding: 0 24px; border-bottom: 1px solid rgba(0,0,0,0.05); flex-shrink: 0;" class="bg-white dark:bg-gray-800 dark:border-gray-700">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 50%;" class="skeleton-shimmer"></div>
                <div style="width: 150px; height: 20px; border-radius: 4px;" class="skeleton-shimmer"></div>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 50%;" class="skeleton-shimmer"></div>
                <div style="width: 100px; height: 32px; border-radius: 20px;" class="skeleton-shimmer"></div>
            </div>
        </div>

        <!-- Main Content Skeleton -->
        <div style="flex: 1; max-width: 1200px; width: 100%; margin: 0 auto; padding: 24px; box-sizing: border-box; display: flex; flex-direction: column; gap: 32px; overflow: hidden;">
            @hasSection('skeleton_content')
                @yield('skeleton_content')
            @else
                <!-- Default Generic Skeleton (Fallback) -->
                <div style="display: flex; flex-direction: column; gap: 24px; width: 100%;">
                    <!-- Title Area -->
                    <div style="width: 300px; height: 32px; border-radius: 8px;" class="skeleton-shimmer"></div>
                    <!-- Content Box -->
                    <div style="flex: 1; min-height: 400px; border-radius: 16px; padding: 24px; box-sizing: border-box;" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 skeleton-shimmer"></div>
                </div>
            @endif
        </div>
    </div>

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
                <a class="logo" href="{{ route('home.index') }}{{ $isLoggedIn ? '?login=1' : '' }}" style="display: flex; align-items: center; gap: 12px; height: 100%;">
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
                                <i class="fa-solid fa-user-circle"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="profileDropdown" style="display: none; position: absolute; top: 45px; right: -20px; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); width: 280px; z-index: 1000; overflow: hidden; text-align: left; cursor: default;">
                                <!-- Banner Header -->
                                <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); height: 75px; position: relative;">
                                    <!-- Profile Avatar -->
                                    <div style="position: absolute; bottom: -30px; left: 20px; width: 70px; height: 70px; background: white; border-radius: 50%; padding: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                        <div style="width: 100%; height: 100%; background: #7e059c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; overflow: hidden;">
                                            <i class="fa-solid fa-user"></i>
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
                    <a href="{{ route('register') }}" class="login-btn mobile-px-2" style="background-color: white; border: none; color: #7e059c; margin-right: 10px; padding: 7px 18px; border-radius: 20px; transition: 0.3s; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.2);" onmouseover="this.style.backgroundColor='#f3e8ff'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.3)'; this.style.transform='translateY(-1px)';" onmouseout="this.style.backgroundColor='white'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.2)'; this.style.transform='translateY(0)';"><i class="fa-solid fa-user-plus"></i> <span class="hide-on-mobile">สมัครสมาชิก</span></a>
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
            style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 99; font-size: 18px; border: none; outline: none; background: #7e059c; color: white; cursor: pointer; width: 45px; height: 45px; border-radius: 50%; box-shadow: 0 4px 12px rgba(126, 5, 156, 0.3); transition: all 0.3s ease;" 
            onmouseover="this.style.background='#5a0273'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 16px rgba(126, 5, 156, 0.4)';" 
            onmouseout="this.style.background='#7e059c'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(126, 5, 156, 0.3)';" 
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <script>
        // ลบหน้าจอ Skeleton Loading ออกเมื่อหน้าเว็บโหลดเสร็จสมบูรณ์
        window.addEventListener('load', function() {
            const loader = document.getElementById('skeleton-loader');
            if (loader) {
                loader.style.opacity = '0';
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 250);
            }
        });

        // Function to exit website (close or redirect to home page for general users)
        function exitWebsite(event) {
            if (event) event.preventDefault();
            window.close();
            setTimeout(function() {
                window.location.href = '{{ url("/") }}';
            }, 100);
        }

        // Function to close logout assessment modal
        function closeLogoutModal() {
            const logoutModal = document.getElementById('logoutAssessmentModal');
            if (logoutModal) {
                logoutModal.style.display = 'none';
                logoutModal.classList.add('hidden');
            }
        }

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

        @php
            $hasAssessed = session('has_assessed') || (auth()->check() && (auth()->user()->has_assessed || \App\Models\AssessmentResponse::where('user_id', auth()->id())->exists()));
        @endphp

        // Logout Assessment Modal triggers
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtns = document.querySelectorAll('.logout-btn');
            const logoutModal = document.getElementById('logoutAssessmentModal');
            const hasAssessed = {{ $hasAssessed ? 'true' : 'false' }};
            
            if (logoutBtns.length > 0) {
                logoutBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        if (!hasAssessed && logoutModal) {
                            e.preventDefault();
                            logoutModal.style.display = 'flex';
                            logoutModal.classList.remove('hidden');
                        }
                    });
                });
                
                // Modal cannot be closed by clicking outside (forced assessment)
            }
        });

        // Intercept back button to show assessment modal (only if not assessed yet)
        @if(!$hasAssessed)
        document.addEventListener('DOMContentLoaded', function() {
            if (sessionStorage.getItem('has_assessed') === 'true') {
                return;
            }

            const path = window.location.pathname;
            const isHomePage = path === '/home' || path.endsWith('/home') || path.endsWith('/index.php') || path.endsWith('/public') || path.endsWith('/public/');
            
            if (isHomePage) {
                setTimeout(function() {
                    history.pushState({page: 'exit_prompt'}, "", "");
                }, 500);

                window.addEventListener('popstate', function(event) {
                    const logoutModal = document.getElementById('logoutAssessmentModal');
                    if (logoutModal && !sessionStorage.getItem('prompt_shown')) {
                        history.pushState({page: 'exit_prompt'}, "", "");
                        logoutModal.style.display = 'flex';
                        logoutModal.classList.remove('hidden');
                        sessionStorage.setItem('prompt_shown', 'true');
                    } else {
                        history.back();
                    }
                });
            }
        });
        @endif
    </script>

    <!-- Logout Assessment Prompt Modal (Forced Assessment) -->
    <div id="logoutAssessmentModal" class="modal-overlay hidden" style="position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: white; max-width: 450px; width: 100%; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; text-align: center; border: 1px solid #f0f0f0; position: relative;">
            <!-- ปุ่มปิด X -->
            <button onclick="closeLogoutModal()" title="ปิด" style="position: absolute; top: 12px; right: 12px; z-index: 10; width: 32px; height: 32px; border-radius: 50%; border: none; background: rgba(255,255,255,0.85); color: #6b7280; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.15); transition: 0.2s;" onmouseover="this.style.background='white'; this.style.color='#111827'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.2)';" onmouseout="this.style.background='rgba(255,255,255,0.85)'; this.style.color='#6b7280'; this.style.boxShadow='0 1px 4px rgba(0,0,0,0.15)';">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <!-- Banner/Header Icon -->
            <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); padding: 35px 20px; position: relative;">
                <div style="width: 70px; height: 70px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <i class="fa-regular fa-star text-3xl" style="color: #f5b011; animation: pulse 2s infinite;"></i>
                </div>
            </div>
            <!-- Body Content -->
            <div style="padding: 30px 25px;">
                <h3 style="font-size: 1.4rem; font-weight: 700; color: #111827; margin-bottom: 12px;">ทำแบบประเมินความพึงพอใจ?</h3>
                <p style="color: #6b7280; font-size: 0.95rem; line-height: 1.6; margin-bottom: 25px;">
                    @if($isLoggedIn)
                        ก่อนออกจากระบบ ขอความกรุณาช่วยสละเวลาทำแบบประเมินความพึงพอใจเพื่อนำไปพัฒนาและปรับปรุงระบบให้ดียิ่งขึ้นได้ไหมคะ? 😊
                    @else
                        ก่อนออกจากเว็บไซต์ ขอความกรุณาช่วยสละเวลาทำแบบประเมินความพึงพอใจเพื่อนำไปพัฒนาและปรับปรุงระบบให้ดียิ่งขึ้นได้ไหมคะ? 😊
                    @endif
                </p>
                
                <!-- Actions -->
                <div style="display: flex; justify-content: center;">
                    <!-- Do assessment -->
                    <a href="{{ route('assessment.index') }}?from_logout=1" style="width: 100%; max-width: 250px; padding: 12px 24px; border-radius: 8px; background: #7e059c; color: white; font-weight: 600; text-decoration: none; font-size: 0.95rem; text-align: center; transition: background 0.2s; box-shadow: 0 4px 6px -1px rgba(126, 5, 156, 0.2);" onmouseover="this.style.background='#680482'" onmouseout="this.style.background='#7e059c'">
                        ทำแบบประเมิน
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
