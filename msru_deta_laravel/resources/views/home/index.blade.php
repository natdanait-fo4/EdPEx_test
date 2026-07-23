@extends('layouts.app')

@section('title', 'หน้าแรก | NSRU-MS DETA SHIP')

@section('skeleton_content')
    <!-- Welcome Banner Skeleton -->
    <div style="height: 100px; border-radius: 16px; padding: 24px; display: flex; align-items: center; gap: 16px; flex-shrink: 0;" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
        <div style="width: 48px; height: 48px; border-radius: 50%; flex-shrink: 0;" class="skeleton-shimmer"></div>
        <div style="display: flex; flex-direction: column; gap: 8px; flex: 1;">
            <div style="width: 250px; height: 20px; border-radius: 4px;" class="skeleton-shimmer"></div>
            <div style="width: 400px; height: 14px; border-radius: 4px;" class="skeleton-shimmer"></div>
        </div>
    </div>

    <!-- Cards Grid Skeleton -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; width: 100%; flex-shrink: 0;">
        <!-- Card 1 -->
        <div style="border-radius: 16px; padding: 24px; display: flex; flex-direction: column; align-items: center; gap: 16px;" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div style="width: 64px; height: 64px; border-radius: 16px;" class="skeleton-shimmer"></div>
            <div style="width: 120px; height: 18px; border-radius: 4px;" class="skeleton-shimmer"></div>
            <div style="width: 180px; height: 12px; border-radius: 4px;" class="skeleton-shimmer"></div>
            <div style="width: 100px; height: 32px; border-radius: 8px; margin-top: 8px;" class="skeleton-shimmer"></div>
        </div>
        <!-- Card 2 -->
        <div style="border-radius: 16px; padding: 24px; display: flex; flex-direction: column; align-items: center; gap: 16px;" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div style="width: 64px; height: 64px; border-radius: 16px;" class="skeleton-shimmer"></div>
            <div style="width: 120px; height: 18px; border-radius: 4px;" class="skeleton-shimmer"></div>
            <div style="width: 180px; height: 12px; border-radius: 4px;" class="skeleton-shimmer"></div>
            <div style="width: 100px; height: 32px; border-radius: 8px; margin-top: 8px;" class="skeleton-shimmer"></div>
        </div>
        <!-- Card 3 -->
        <div style="border-radius: 16px; padding: 24px; display: flex; flex-direction: column; align-items: center; gap: 16px;" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div style="width: 64px; height: 64px; border-radius: 16px;" class="skeleton-shimmer"></div>
            <div style="width: 120px; height: 18px; border-radius: 4px;" class="skeleton-shimmer"></div>
            <div style="width: 180px; height: 12px; border-radius: 4px;" class="skeleton-shimmer"></div>
            <div style="width: 100px; height: 32px; border-radius: 8px; margin-top: 8px;" class="skeleton-shimmer"></div>
        </div>
        <!-- Card 4 -->
        <div style="border-radius: 16px; padding: 24px; display: flex; flex-direction: column; align-items: center; gap: 16px;" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div style="width: 64px; height: 64px; border-radius: 16px;" class="skeleton-shimmer"></div>
            <div style="width: 120px; height: 18px; border-radius: 4px;" class="skeleton-shimmer"></div>
            <div style="width: 180px; height: 12px; border-radius: 4px;" class="skeleton-shimmer"></div>
            <div style="width: 100px; height: 32px; border-radius: 8px; margin-top: 8px;" class="skeleton-shimmer"></div>
        </div>
    </div>

    <!-- Banner Skeleton -->
    <div style="height: 250px; border-radius: 16px; flex-shrink: 0;" class="skeleton-shimmer"></div>
@endsection

@section('content')
@php
    $isLoggedIn = request('login') == '1' || auth()->check();
@endphp
<main class="container main-content">
    @push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .plo-carousel-container {
            width: 100%;
            padding-top: 10px;
            padding-bottom: 30px;
        }
        .swiper-plo {
            width: 100%;
            padding-top: 20px;
            padding-bottom: 40px;
        }
        .swiper-plo .swiper-slide {
            background-position: center;
            background-size: cover;
            width: 300px;
            height: 450px;
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0;
            overflow: hidden;
            border: 1px solid #f3e8ff;
            transition: all 0.3s ease;
        }
        .swiper-plo .swiper-slide-active {
            border: 2px solid #7e059c;
            box-shadow: 0 15px 30px rgba(126, 5, 156, 0.15);
        }
        .swiper-plo .swiper-slide h2 {
            color: #111827;
            margin-bottom: 12px;
            font-size: 1.3rem;
            font-weight: 700;
        }
        .swiper-plo .swiper-slide p {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        /* Dark Mode Overrides */
        .dark .swiper-plo .swiper-slide {
            background-color: #1f2937;
            border-color: #374151;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        .dark .swiper-plo .swiper-slide h2 {
            color: #f9fafb;
        }
        .dark .swiper-plo .swiper-slide p {
            color: #9ca3af;
        }
        .swiper-plo .swiper-button-next,
        .swiper-plo .swiper-button-prev {
            color: #7e059c;
            background: rgba(255, 255, 255, 0.9);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .swiper-plo .swiper-button-next:after,
        .swiper-plo .swiper-button-prev:after {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .swiper-plo .swiper-button-next {
            right: 20px;
        }
        .swiper-plo .swiper-button-prev {
            left: 20px;
        }
        .swiper-plo .swiper-pagination-bullet {
            background: rgba(0, 0, 0, 0.2);
            opacity: 1;
        }
        .dark .swiper-plo .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.3);
        }
        .swiper-plo .swiper-pagination-bullet-active {
            background: #7e059c !important;
            opacity: 1;
        }
        @media (max-width: 640px) {
            .swiper-plo .swiper-slide {
                width: 270px;
                height: 405px;
            }
            .swiper-plo .swiper-button-next,
            .swiper-plo .swiper-button-prev {
                display: none;
            }
        }
        /* Tab Styles */
        .degree-tabs {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .degree-tab {
            padding: 10px 20px;
            border-radius: 30px;
            background: white;
            color: #4b5563;
            font-weight: 600;
            border: 2px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            font-size: 1rem;
            white-space: nowrap;
        }
        @media (max-width: 640px) {
            .degree-tab {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
            .degree-tabs {
                gap: 8px;
            }
        }
        .dark .degree-tab {
            background: #1f2937;
            border-color: #374151;
            color: #d1d5db;
        }
        .degree-tab:hover {
            border-color: #7e059c;
            color: #7e059c;
        }
        .degree-tab.active {
            background: #7e059c;
            color: white;
            border-color: #7e059c;
            box-shadow: 0 4px 15px rgba(126, 5, 156, 0.3);
        }
        .plo-carousel-wrapper {
            display: none;
            animation: fadeIn 0.4s ease;
        }
        .plo-carousel-wrapper.active {
            display: block;
        }
    </style>
    @endpush

    <!-- แถบข้อความต้อนรับ PLO Carousel -->
    <div class="plo-carousel-container">
        <!-- Degree Tabs -->
        <div class="degree-tabs">
            <button class="degree-tab active" onclick="switchDegreeTab('bachelor')">ปริญญาตรี</button>
            <button class="degree-tab" onclick="switchDegreeTab('master')">ปริญญาโท</button>
            <button class="degree-tab" onclick="switchDegreeTab('doctoral')">ปริญญาเอก</button>
        </div>

        <!-- Bachelor Carousel -->
        <div id="carousel-bachelor" class="plo-carousel-wrapper active">
            @if($plosBachelor->count() > 0)
            <div class="swiper swiper-plo">
                <div class="swiper-wrapper">
                    @foreach($plosBachelor as $plo)
                    <div class="swiper-slide">
                        @if($plo->image_path)
                            <img src="{{ asset($plo->image_path) }}" alt="{{ $plo->title }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openSingleLightbox('{{ asset($plo->image_path) }}', '{{ $plo->title }}')">
                        @else
                            <div style="padding: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%;">
                                <div class="welcome-icon" style="background: rgba(126, 5, 156, 0.1); color: #7e059c; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 15px;">
                                    <i class="{{ $plo->icon_class }}"></i>
                                </div>
                                <h2>{{ $plo->title }}</h2>
                                <p>{{ $plo->description }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                    
                    @foreach($plosBachelor as $plo)
                    <div class="swiper-slide">
                        @if($plo->image_path)
                            <img src="{{ asset($plo->image_path) }}" alt="{{ $plo->title }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openSingleLightbox('{{ asset($plo->image_path) }}', '{{ $plo->title }}')">
                        @else
                            <div style="padding: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%;">
                                <div class="welcome-icon" style="background: rgba(126, 5, 156, 0.1); color: #7e059c; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 15px;">
                                    <i class="{{ $plo->icon_class }}"></i>
                                </div>
                                <h2>{{ $plo->title }}</h2>
                                <p>{{ $plo->description }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            @else
            <div style="text-align: center; padding: 40px; color: #6b7280;">ยังไม่มีข้อมูล PLO ระดับปริญญาตรี</div>
            @endif
        </div>

        <!-- Master Carousel -->
        <div id="carousel-master" class="plo-carousel-wrapper">
            @if($plosMaster->count() > 0)
            <div class="swiper swiper-plo">
                <div class="swiper-wrapper">
                    @foreach($plosMaster as $plo)
                    <div class="swiper-slide">
                        @if($plo->image_path)
                            <img src="{{ asset($plo->image_path) }}" alt="{{ $plo->title }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openSingleLightbox('{{ asset($plo->image_path) }}', '{{ $plo->title }}')">
                        @else
                            <div style="padding: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%;">
                                <div class="welcome-icon" style="background: rgba(126, 5, 156, 0.1); color: #7e059c; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 15px;">
                                    <i class="{{ $plo->icon_class }}"></i>
                                </div>
                                <h2>{{ $plo->title }}</h2>
                                <p>{{ $plo->description }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                    
                    @foreach($plosMaster as $plo)
                    <div class="swiper-slide">
                        @if($plo->image_path)
                            <img src="{{ asset($plo->image_path) }}" alt="{{ $plo->title }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openSingleLightbox('{{ asset($plo->image_path) }}', '{{ $plo->title }}')">
                        @else
                            <div style="padding: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%;">
                                <div class="welcome-icon" style="background: rgba(126, 5, 156, 0.1); color: #7e059c; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 15px;">
                                    <i class="{{ $plo->icon_class }}"></i>
                                </div>
                                <h2>{{ $plo->title }}</h2>
                                <p>{{ $plo->description }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            @else
            <div style="text-align: center; padding: 40px; color: #6b7280;">ยังไม่มีข้อมูล PLO ระดับปริญญาโท</div>
            @endif
        </div>

        <!-- Doctoral Carousel -->
        <div id="carousel-doctoral" class="plo-carousel-wrapper">
            @if($plosDoctoral->count() > 0)
            <div class="swiper swiper-plo">
                <div class="swiper-wrapper">
                    @foreach($plosDoctoral as $plo)
                    <div class="swiper-slide">
                        @if($plo->image_path)
                            <img src="{{ asset($plo->image_path) }}" alt="{{ $plo->title }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openSingleLightbox('{{ asset($plo->image_path) }}', '{{ $plo->title }}')">
                        @else
                            <div style="padding: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%;">
                                <div class="welcome-icon" style="background: rgba(126, 5, 156, 0.1); color: #7e059c; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 15px;">
                                    <i class="{{ $plo->icon_class }}"></i>
                                </div>
                                <h2>{{ $plo->title }}</h2>
                                <p>{{ $plo->description }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                    
                    @foreach($plosDoctoral as $plo)
                    <div class="swiper-slide">
                        @if($plo->image_path)
                            <img src="{{ asset($plo->image_path) }}" alt="{{ $plo->title }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openSingleLightbox('{{ asset($plo->image_path) }}', '{{ $plo->title }}')">
                        @else
                            <div style="padding: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%;">
                                <div class="welcome-icon" style="background: rgba(126, 5, 156, 0.1); color: #7e059c; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 15px;">
                                    <i class="{{ $plo->icon_class }}"></i>
                                </div>
                                <h2>{{ $plo->title }}</h2>
                                <p>{{ $plo->description }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            @else
            <div style="text-align: center; padding: 40px; color: #6b7280;">ยังไม่มีข้อมูล PLO ระดับปริญญาเอก</div>
            @endif
        </div>
    </div>

    <!-- กริดสำหรับแสดงการ์ดเมนู -->
    <div class="card-grid">
        <!-- การ์ด 1: แจ้งความต้องการ -->
        <div class="card">
            <div class="card-icon icon-blue">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <h3>แจ้งความต้องการ</h3>
            <p>เสนอแนะสิ่งที่อยากให้มีในหลักสูตร/บริการ</p>
            <a href="{{ route('request.index') }}{{ $isLoggedIn ? '?login=1' : '' }}" class="btn btn-outline-blue">กรอกข้อมูล</a>
        </div>

        <!-- การ์ด 2: ประเมินความพึงพอใจ -->
        <div class="card">
            <div class="card-icon icon-yellow">
                <i class="fa-solid fa-star"></i>
            </div>
            <h3>ประเมินความพึงพอใจ</h3>
            <p>ให้คะแนนการบริการและหลักสูตรเพื่อการพัฒนา</p>
            <a href="{{ route('assessment.index') }}{{ $isLoggedIn ? '?login=1' : '' }}" class="btn btn-outline-yellow">ทำแบบประเมิน</a>
        </div>

        <!-- การ์ด 3: ข้อร้องเรียน -->
        <div class="card">
            <div class="card-icon icon-red">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <h3>ข้อร้องเรียน</h3>
            <p>แจ้งปัญหาและติดตามสถานะดำเนินการ</p>
            <a href="{{ route('complaint.index') }}{{ $isLoggedIn ? '?login=1' : '' }}" class="btn btn-outline-red">ส่งเรื่องร้องเรียน</a>
        </div>

        <!-- การ์ด 4: ถาม - ตอบ (Q&A) -->
        <div class="card">
            <div class="card-icon icon-cyan">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <h3>ถาม - ตอบ (Q&A)</h3>
            <p>ค้นหาคำตอบหรือตั้งคำถามใหม่</p>
            <a href="{{ route('qa.index') }}{{ $isLoggedIn ? '?login=1' : '' }}" class="btn btn-outline-cyan">ดูคำถามที่พบบ่อย</a>
        </div>
    </div>

    <!-- แถบแบนเนอร์ประชาสัมพันธ์ (Carousel) -->
    @if(isset($banners) && $banners->count() > 0)
    <div class="banner-carousel-container" style="margin-top: 40px; margin-bottom: 40px; position: relative; max-width: 900px; margin-left: auto; margin-right: auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        
        <div class="banner-carousel-track" id="bannerTrack" style="display: flex; transition: transform 0.5s ease-in-out; height: 100%;">
            @foreach($banners as $banner)
                <div class="banner-slide" style="min-width: 100%; height: 100%; position: relative;">
                    <img src="{{ asset($banner->image_path) }}" 
                         alt="{{ $banner->title ?: 'Banner' }}" 
                         onclick="openBannerLightbox('{{ asset($banner->image_path) }}', '{{ $banner->title }}', '{{ $banner->link }}')"
                         style="width: 100%; height: 100%; max-height: 500px; object-fit: cover; display: block; cursor: pointer;"
                         class="banner-img-hover">
                    @if($banner->title || $banner->description)
                    <!-- Gradient Overlay & Title/Description -->
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 160px; background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0) 100%); pointer-events: none; padding: 24px; display: flex; flex-direction: column; justify-content: flex-end; box-sizing: border-box; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; gap: 4px;">
                        @if($banner->title)
                        <h3 style="color: white; margin: 0; font-size: 1.3rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.6); font-family: 'Prompt', sans-serif; text-align: left; width: 100%; line-height: 1.3;">{{ $banner->title }}</h3>
                        @endif
                        @if($banner->description)
                        <p style="color: rgba(255, 255, 255, 0.85); margin: 0; font-size: 0.95rem; font-weight: 400; text-shadow: 0 1px 3px rgba(0,0,0,0.6); font-family: 'Prompt', sans-serif; text-align: left; width: 100%; line-height: 1.4;">{{ $banner->description }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($banners->count() > 1)
            <!-- Navigation Buttons -->
            <button aria-label="รูปภาพแบนเนอร์ก่อนหน้า" onclick="moveBanner(-1)" style="position: absolute; top: 50%; left: 15px; transform: translateY(-50%); background: rgba(255,255,255,0.8); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #7e059c; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: 0.3s; z-index: 10;" onmouseover="this.style.background='rgba(255,255,255,1)'" onmouseout="this.style.background='rgba(255,255,255,0.8)'">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button aria-label="รูปภาพแบนเนอร์ถัดไป" onclick="moveBanner(1)" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); background: rgba(255,255,255,0.8); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #7e059c; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: 0.3s; z-index: 10;" onmouseover="this.style.background='rgba(255,255,255,1)'" onmouseout="this.style.background='rgba(255,255,255,0.8)'">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            
            <!-- Indicators -->
            <div style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 10;">
                @foreach($banners as $index => $banner)
                    <button aria-label="เลื่อนไปยังแบนเนอร์ที่ {{ $index + 1 }}" onclick="goToBanner({{ $index }})" class="banner-dot" data-index="{{ $index }}" style="width: 10px; height: 10px; border-radius: 50%; border: none; background: {{ $index == 0 ? '#7e059c' : 'rgba(255,255,255,0.8)' }}; cursor: pointer; transition: 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.3);"></button>
                @endforeach
            </div>
        @endif
    </div>
    
    @if($banners->count() > 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentBannerIndex = 0;
            const totalBanners = {{ $banners->count() }};
            const bannerTrack = document.getElementById('bannerTrack');
            const bannerDots = document.querySelectorAll('.banner-dot');
            let bannerInterval;

            // Store banners data for lightbox
            window.allBanners = @json($banners->map(function($b) {
                return [
                    'image_path' => asset($b->image_path),
                    'title' => $b->title,
                    'link' => $b->link
                ];
            }));

            window.updateBannerDisplay = function() {
                bannerTrack.style.transform = `translateX(-${currentBannerIndex * 100}%)`;
                bannerDots.forEach((dot, index) => {
                    dot.style.background = index === currentBannerIndex ? '#7e059c' : 'rgba(255,255,255,0.8)';
                });
            }

            window.moveBanner = function(direction) {
                currentBannerIndex = (currentBannerIndex + direction + totalBanners) % totalBanners;
                updateBannerDisplay();
                resetBannerInterval();
            }

            window.goToBanner = function(index) {
                currentBannerIndex = index;
                updateBannerDisplay();
                resetBannerInterval();
            }

            function startBannerInterval() {
                bannerInterval = setInterval(() => {
                    moveBanner(1);
                }, 5000); // 5 seconds autoplay
            }

            function resetBannerInterval() {
                clearInterval(bannerInterval);
                startBannerInterval();
            }

            // Start autoplay
            startBannerInterval();
        });
    </script>
    @else
    <script>
        window.allBanners = @json($banners->map(function($b) {
            return [
                'image_path' => asset($b->image_path),
                'title' => $b->title,
                'link' => $b->link
            ];
        }));
    </script>
    @endif
    @endif

    <!-- Banner Lightbox Modal -->
    <div id="bannerLightbox" class="banner-lightbox" onclick="if(event.target === this) closeBannerLightbox()">
        <span class="close-lightbox" onclick="closeBannerLightbox()">&times;</span>
        
        @if($banners->count() > 1)
        <button class="lightbox-nav-btn prev" aria-label="รูปภาพแบนเนอร์ก่อนหน้า" onclick="changeLightboxBanner(-1)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button class="lightbox-nav-btn next" aria-label="รูปภาพแบนเนอร์ถัดไป" onclick="changeLightboxBanner(1)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
        @endif

        <div class="lightbox-container">
            <img class="lightbox-content" id="lightboxImage">
            <div id="lightboxCaption" class="lightbox-caption"></div>
            <div id="lightboxLinkContainer" style="margin-top: 15px; text-align: center;"></div>
        </div>
    </div>


    <script>
    let currentLightboxIndex = 0;
    let isSingleImageMode = false;

    function openSingleLightbox(src, title) {
        isSingleImageMode = true;
        const modalImg = document.getElementById('lightboxImage');
        const captionText = document.getElementById('lightboxCaption');
        const linkContainer = document.getElementById('lightboxLinkContainer');
        
        document.querySelectorAll('.lightbox-nav-btn').forEach(btn => btn.style.display = 'none');
        modalImg.classList.remove('zoomed');
        
        modalImg.src = src;
        captionText.innerHTML = title || "";
        linkContainer.innerHTML = "";
        
        document.getElementById('bannerLightbox').style.display = "block";
        document.body.style.overflow = 'hidden'; 
    }

    function openBannerLightbox(src, title, link) {
        isSingleImageMode = false;
        document.querySelectorAll('.lightbox-nav-btn').forEach(btn => btn.style.display = '');
        
        const modalImg = document.getElementById('lightboxImage');
        modalImg.classList.remove('zoomed');
        
        // Find index by src
        currentLightboxIndex = window.allBanners.findIndex(b => b.image_path === src);
        updateLightboxDisplay();
        
        document.getElementById('bannerLightbox').style.display = "block";
        document.body.style.overflow = 'hidden'; 
    }

    function changeLightboxBanner(direction) {
        if (isSingleImageMode) return;
        currentLightboxIndex = (currentLightboxIndex + direction + window.allBanners.length) % window.allBanners.length;
        updateLightboxDisplay();
    }

    function updateLightboxDisplay() {
        const banner = window.allBanners[currentLightboxIndex];
        const modalImg = document.getElementById('lightboxImage');
        const captionText = document.getElementById('lightboxCaption');
        const linkContainer = document.getElementById('lightboxLinkContainer');
        
        // Add fade effect when changing image
        modalImg.style.opacity = '0';
        modalImg.classList.remove('zoomed');
        setTimeout(() => {
            modalImg.src = banner.image_path;
            captionText.innerHTML = banner.title || "";
            
            if (banner.link && banner.link !== "") {
                linkContainer.innerHTML = `<a href="${banner.link}" target="_blank" class="btn-lightbox-link"><i class="fa-solid fa-arrow-up-right-from-square mr-2"></i> ดูรายละเอียดเพิ่มเติม</a>`;
            } else {
                linkContainer.innerHTML = "";
            }
            modalImg.style.opacity = '1';
        }, 150);
    }

    function closeBannerLightbox() {
        document.getElementById('bannerLightbox').style.display = "none";
        document.body.style.overflow = 'auto'; 
    }

    document.addEventListener('keydown', function(event) {
        if (document.getElementById('bannerLightbox').style.display === "block") {
            if (event.key === 'Escape') closeBannerLightbox();
            if (!isSingleImageMode && event.key === 'ArrowLeft') changeLightboxBanner(-1);
            if (!isSingleImageMode && event.key === 'ArrowRight') changeLightboxBanner(1);
        }
    });

    // Add click to zoom feature
    document.getElementById('lightboxImage').addEventListener('click', function(e) {
        e.stopPropagation(); // ป้องกันการส่ง event ไปยัง parent ที่จะทำให้ปิด lightbox
        this.classList.toggle('zoomed');
    });
    </script>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all swiper instances
            var swipers = document.querySelectorAll('.swiper-plo');
            swipers.forEach(function(el) {
                var slideCount = el.querySelectorAll('.swiper-slide').length;
                var isLoop = slideCount > 1;
                
                new Swiper(el, {
                    effect: "coverflow",
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: "auto",
                    loopedSlides: isLoop ? 5 : null,
                    coverflowEffect: {
                        rotate: 0,
                        stretch: -20,
                        depth: 150,
                        modifier: 2,
                        slideShadows: false,
                    },
                    loop: isLoop,
                    observer: true,
                    observeParents: true,
                    autoplay: isLoop ? {
                        delay: 4000,
                        disableOnInteraction: false,
                    } : false,
                    pagination: {
                        el: el.querySelector('.swiper-pagination'),
                        clickable: true,
                        dynamicBullets: true,
                    },
                    breakpoints: {
                        320: {
                            coverflowEffect: {
                                stretch: 10,
                                depth: 120,
                                modifier: 1.5,
                            }
                        },
                        640: {
                            coverflowEffect: {
                                stretch: -20,
                                depth: 150,
                                modifier: 2,
                            }
                        }
                    },
                    navigation: {
                        nextEl: el.querySelector('.swiper-button-next'),
                        prevEl: el.querySelector('.swiper-button-prev'),
                    },
                });
            });
        });

        function switchDegreeTab(level) {
            // Remove active from all tabs
            document.querySelectorAll('.degree-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            // Add active to clicked tab
            event.target.classList.add('active');

            // Hide all carousels
            document.querySelectorAll('.plo-carousel-wrapper').forEach(wrapper => {
                wrapper.classList.remove('active');
            });
            // Show target carousel
            document.getElementById('carousel-' + level).classList.add('active');
        }
    </script>
    @endpush
</main>
@endsection
