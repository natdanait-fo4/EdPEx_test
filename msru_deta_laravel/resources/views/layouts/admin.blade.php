<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard | NSRU-MS DETA SHIP')</title>
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

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo-area">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 60px; width: auto;">
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.index') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-solid fa-chart-line w-6 text-center mr-2"></i> หน้าแรก
            </a>
            <!-- เมนูจัดการผู้ใช้งาน -->
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-solid fa-users-gear w-6 text-center mr-2"></i> จัดการผู้ใช้
            </a>
            <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-regular fa-images w-6 text-center mr-2"></i> จัดการรูปภาพเหน้าแรก
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
                <i class="fa-solid fa-square-poll-vertical w-6 text-center mr-2"></i> จัดการการประเมิน
            </a>
            <div class="my-4 border-t border-gray-100/10 mx-4"></div>
            <a href="{{ route('admin.profile.password.index') }}" class="{{ request()->routeIs('admin.profile.password.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fa-solid fa-key w-6 text-center mr-2"></i> เปลี่ยนรหัสผ่าน
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
            <a href="{{ route('logout') }}" class="nav-btn text-sm font-medium border border-transparent hover:border-white/30 px-3 py-1.5 rounded-lg">
                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> ออกจากระบบ
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
            });
            
            // Automatically collapse on small screens
            function checkScreenSize() {
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('collapsed');
                    topNavbar.classList.add('collapsed');
                    mainWrapper.classList.add('collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                    topNavbar.classList.remove('collapsed');
                    mainWrapper.classList.remove('collapsed');
                }
            }
            
            checkScreenSize();
            window.addEventListener('resize', checkScreenSize);
        });
    </script>

    <!-- Scroll to Top Button (Admin) -->
    <button id="scrollTopBtnAdmin" title="กลับขึ้นบนสุด" 
            style="display: none; position: fixed; bottom: 30px; right: 30px; z-index: 99; font-size: 22px; border: none; outline: none; background-color: #7e059c; color: white; cursor: pointer; width: 50px; height: 50px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.25); transition: all 0.3s;" 
            onmouseover="this.style.backgroundColor='#680482'; this.style.transform='scale(1.1)'" 
            onmouseout="this.style.backgroundColor='#7e059c'; this.style.transform='scale(1)'" 
            onclick="document.getElementById('main-wrapper').scrollTo({top: 0, behavior: 'smooth'}); window.scrollTo({top: 0, behavior: 'smooth'});">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <script>
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
