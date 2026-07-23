<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard | NSRU-MS DETA SHIP')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Prompt', sans-serif; }
    </style>
    <script src="{{ asset('theme.js') }}"></script>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <!-- หน้าจอโหลดแบบ Skeleton สำหรับหน้า Admin (Admin Skeleton Loading Screen) -->
    <div id="skeleton-loader" style="position: fixed; inset: 0; z-index: 99999; display: flex; transition: opacity 0.25s ease-in-out;" class="bg-gray-50 dark:bg-gray-900">
        <!-- Sidebar Skeleton -->
        <div style="width: 256px; display: flex; flex-direction: column; gap: 24px; padding: 24px; border-right: 1px solid rgba(0,0,0,0.05); flex-shrink: 0;" class="hidden lg:flex bg-white dark:bg-gray-800 dark:border-gray-700">
            <div style="height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center;" class="bg-[#7e059c]">
                <div style="width: 120px; height: 24px; border-radius: 4px;" class="skeleton-shimmer bg-white/20"></div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 16px; flex: 1; margin-top: 16px;">
                <div style="height: 40px; border-radius: 8px;" class="skeleton-shimmer"></div>
                <div style="height: 40px; border-radius: 8px;" class="skeleton-shimmer"></div>
                <div style="height: 40px; border-radius: 8px;" class="skeleton-shimmer"></div>
                <div style="height: 40px; border-radius: 8px;" class="skeleton-shimmer"></div>
                <div style="height: 40px; border-radius: 8px;" class="skeleton-shimmer"></div>
            </div>
        </div>

        <!-- Main Workspace Skeleton -->
        <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
            <!-- Top Navbar Skeleton -->
            <div style="height: 64px; display: flex; justify-content: space-between; align-items: center; padding: 0 24px; border-bottom: 1px solid rgba(0,0,0,0.05); flex-shrink: 0;" class="bg-[#7e059c]">
                <div style="width: 150px; height: 20px; border-radius: 4px;" class="skeleton-shimmer bg-white/20"></div>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%;" class="skeleton-shimmer bg-white/20"></div>
                    <div style="width: 100px; height: 20px; border-radius: 4px;" class="skeleton-shimmer bg-white/20"></div>
                </div>
            </div>

            <!-- Content Area Skeleton -->
            <div style="flex: 1; padding: 32px; box-sizing: border-box; display: flex; flex-direction: column; gap: 32px; overflow-y: auto;">
                @hasSection('admin_skeleton_content')
                    @yield('admin_skeleton_content')
                @else
                    <!-- Default Generic Admin Skeleton (Fallback - Table) -->
                    <!-- Header Title Skeleton -->
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                        <div style="width: 200px; height: 28px; border-radius: 4px;" class="skeleton-shimmer"></div>
                        <div style="width: 120px; height: 40px; border-radius: 8px;" class="skeleton-shimmer"></div>
                    </div>

                    <!-- Table Content Skeleton -->
                    <div style="flex: 1; min-height: 350px; border-radius: 12px; padding: 24px; box-sizing: border-box;" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 skeleton-shimmer"></div>
                @endif
            </div>
        </div>
    </div>


    <!-- Sidebar Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-10 hidden lg:hidden opacity-0 transition-opacity duration-300" onclick="document.getElementById('sidebar-toggle').click()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo-area">
            <div style="background-color: white; border-radius: 50%; width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 46px; width: auto; object-fit: contain;">
            </div>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.index') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-solid fa-house w-6 text-center mr-2"></i> หน้าแรก
            </a>
            <!-- เมนูจัดการผู้ใช้งาน -->
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-solid fa-users-gear w-6 text-center mr-2"></i> จัดการผู้ใช้
            </a>
            <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') || request()->routeIs('admin.plos.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-regular fa-images w-6 text-center mr-2"></i> จัดการข้อมูลหน้าแรก
            </a>
            <a href="{{ route('admin.qa.index') }}" class="{{ request()->routeIs('admin.qa.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-solid fa-comments w-6 text-center mr-2"></i> จัดการ Q&A
            </a>
            <a href="{{ route('admin.complaints.index') }}" class="{{ request()->routeIs('admin.complaints.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-solid fa-envelope-open-text w-6 text-center mr-2"></i> จัดการข้อร้องเรียน
            </a>
            <a href="{{ route('admin.requests.index') }}" class="{{ request()->routeIs('admin.requests.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-solid fa-lightbulb w-6 text-center mr-2"></i> จัดการความต้องการ
            </a>
            <a href="{{ route('admin.assessment.index') }}" class="{{ request()->routeIs('admin.assessment.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-solid fa-square-plol-vertical w-6 text-center mr-2"></i> จัดการการประเมิน
            </a>

            <div class="my-2 border-t border-gray-200 dark:border-gray-700 mx-4"></div>
            
            <a href="{{ route('home.index') }}" class="sidebar-link !text-[#7e059c] dark:!text-purple-400 hover:!bg-purple-50 dark:hover:!bg-purple-900/30">
                <i class="fa-solid fa-arrow-up-right-from-square w-6 text-center mr-2"></i> ดูหน้าเว็บไซต์หลัก
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100 text-xs text-center text-gray-400">
            NSRU-MS Admin v1.0
        </div>
    </aside>

    <!-- Top Navbar -->
    <header class="top-navbar" id="top-navbar">
        <div class="flex items-center text-white font-medium text-lg">
            <button id="sidebar-toggle" class="mr-4 hover:text-yellow-300 transition-colors focus:outline-none">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <span>@yield('header_title', 'Dashboard')</span>
        </div>
        <div class="flex items-center space-x-4">
            <button onclick="toggleDarkMode()" class="nav-btn" title="สลับโหมดสว่าง/มืด">
                <i class="fa-solid fa-moon text-lg" id="theme-icon"></i>
            </button>
            <div class="h-8 w-[1px] bg-white/20 mx-2"></div>
            <div class="flex items-center text-white text-sm font-medium pr-2">
                <i class="fa-solid fa-user-shield mr-2 text-lg text-white"></i>
                <span class="hidden md:inline text-white">{{ auth()->user()->username }}</span>
            </div>
            <a href="{{ route('logout') }}" class="nav-btn text-sm font-medium border border-transparent hover:border-white/30 px-3 py-1.5 rounded-lg" title="ออกจากระบบ">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> <span class="hidden md:inline" style="margin-left: 8px;">ออกจากระบบ</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-wrapper" id="main-wrapper">
        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const topNavbar = document.getElementById('top-navbar');
            const mainWrapper = document.getElementById('main-wrapper');
            
            // Toggle sidebar on button click
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                topNavbar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('collapsed');
                
                // Toggle overlay on mobile
                const overlay = document.getElementById('sidebar-overlay');
                if (window.innerWidth < 1024) {
                    if (!sidebar.classList.contains('collapsed')) {
                        overlay.classList.remove('hidden');
                        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                    } else {
                        overlay.classList.add('opacity-0');
                        setTimeout(() => overlay.classList.add('hidden'), 300);
                    }
                }
            });
            
            function checkScreenSize() {
                const overlay = document.getElementById('sidebar-overlay');
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('collapsed');
                    topNavbar.classList.add('collapsed');
                    mainWrapper.classList.add('collapsed');
                    if (overlay) {
                        overlay.classList.add('opacity-0');
                        overlay.classList.add('hidden');
                    }
                } else {
                    sidebar.classList.remove('collapsed');
                    topNavbar.classList.remove('collapsed');
                    mainWrapper.classList.remove('collapsed');
                    if (overlay) {
                        overlay.classList.add('opacity-0');
                        overlay.classList.add('hidden');
                    }
                }
            }
            
            checkScreenSize();
            window.addEventListener('resize', checkScreenSize);
        });
    </script>

    <!-- Scroll to Top Button (Admin) -->
    <button id="scrollTopBtnAdmin" title="กลับขึ้นบนสุด" 
            style="display: none; position: fixed; bottom: 30px; right: 30px; z-index: 99; font-size: 22px; border: 1px solid rgba(255, 255, 255, 0.25); outline: none; background: linear-gradient(135deg, rgba(126, 5, 156, 0.7) 0%, rgba(157, 7, 194, 0.45) 100%); color: white; cursor: pointer; width: 50px; height: 50px; border-radius: 50%; box-shadow: 0 8px 32px 0 rgba(126, 5, 156, 0.3), inset 0 2px 4px rgba(255, 255, 255, 0.3); backdrop-filter: blur(8px) saturate(140%); -webkit-backdrop-filter: blur(8px) saturate(140%); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);" 
            onmouseover="this.style.background='linear-gradient(135deg, rgba(126, 5, 156, 0.85) 0%, rgba(157, 7, 194, 0.65) 100%)'; this.style.transform='scale(1.15) translateY(-3px)'; this.style.boxShadow='0 12px 28px -2px rgba(126, 5, 156, 0.5), inset 0 2px 6px rgba(255, 255, 255, 0.5)'; this.style.borderColor='rgba(255, 255, 255, 0.4)';" 
            onmouseout="this.style.background='linear-gradient(135deg, rgba(126, 5, 156, 0.7) 0%, rgba(157, 7, 194, 0.45) 100%)'; this.style.transform='scale(1) translateY(0)'; this.style.boxShadow='0 8px 32px 0 rgba(126, 5, 156, 0.3), inset 0 2px 4px rgba(255, 255, 255, 0.3)'; this.style.borderColor='rgba(255, 255, 255, 0.25)';" 
            onclick="document.getElementById('main-wrapper').scrollTo({top: 0, behavior: 'smooth'}); window.scrollTo({top: 0, behavior: 'smooth'});">
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

        // ควบคุมการแสดงผลปุ่มเลื่อนกลับขึ้นบนสุดสำหรับฝั่ง Admin
        document.getElementById('main-wrapper').addEventListener('scroll', function() {
            let topBtn = document.getElementById("scrollTopBtnAdmin");
            if (this.scrollTop > 300) {
                topBtn.style.display = "block";
            } else {
                topBtn.style.display = "none";
            }
        });
        window.addEventListener('scroll', function() {
            let topBtn = document.getElementById("scrollTopBtnAdmin");
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
