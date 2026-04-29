@extends('layouts.app')

@section('title', 'หน้าแรก | NSRU-MS DETA SHIP')

@section('content')
@php
    $isLoggedIn = request('login') == '1' || auth()->check();
@endphp
<main class="container main-content">
    <!-- แถบข้อความต้อนรับ -->
    <div class="welcome-banner">
        <div class="welcome-icon">
            <i class="fa-solid fa-bullhorn"></i>
        </div>
        <div class="welcome-text">
            <h2>ยินดีต้อนรับสู่ระบบรับฟังเสียงลูกค้า</h2>
            <p>ข้อมูลของท่านจะถูกนำไปพัฒนาคณะวิทยาการจัดการสู่ความเป็นเลิศ</p>
        </div>
    </div>

    <!-- กริดสำหรับแสดงการ์ดเมนู -->
    <div class="card-grid">
        <!-- การ์ด 1: แจ้งความต้องการ -->
        @if($isLoggedIn)
        <div class="card">
            <div class="card-icon icon-blue">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <h3>แจ้งความต้องการ</h3>
            <p>เสนอแนะสิ่งที่อยากให้มีในหลักสูตร/บริการ</p>
            <a href="{{ route('request.index') }}{{ $isLoggedIn ? '?login=1' : '' }}" class="btn btn-outline-blue">กรอกข้อมูล</a>
        </div>
        @endif

        <!-- การ์ด 2: ประเมินความพึงพอใจ -->
        <div class="card">
            <div class="card-icon icon-yellow">
                <i class="fa-solid fa-star"></i>
            </div>
            <h3>ประเมินความพึงพอใจ</h3>
            <div style="margin-bottom: 20px; display: flex; flex-direction: column; align-items: center; gap: 5px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 1.5rem; font-weight: 700; color: var(--text-main);">{{ number_format($overallScore ?? 0, 1) }}</span>
                    <div style="color: var(--yellow-color); font-size: 0.9rem; display: flex; gap: 2px;">
                        @for($i=1; $i<=5; $i++)
                            <i class="fa-{{ $i <= round($overallScore ?? 0) ? 'solid' : 'regular' }} fa-star"></i>
                        @endfor
                    </div>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">
                    <i class="fa-solid fa-users opacity-70"></i> อ้างอิงจากผู้ประเมิน {{ $totalUsers ?? 0 }} คน
                </div>
            </div>
            <p>ให้คะแนนการบริการและหลักสูตรเพื่อการพัฒนา</p>
            <a href="{{ route('assessment.index') }}{{ $isLoggedIn ? '?login=1' : '' }}" class="btn btn-outline-yellow">ทำแบบประเมิน</a>
        </div>

        <!-- การ์ด 3: ข้อร้องเรียน -->
        @if($isLoggedIn)
        <div class="card">
            <div class="card-icon icon-red">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <h3>ข้อร้องเรียน</h3>
            <p>แจ้งปัญหาและติดตามสถานะดำเนินการ</p>
            <a href="{{ route('complaint.index') }}{{ $isLoggedIn ? '?login=1' : '' }}" class="btn btn-outline-red">ส่งเรื่องร้องเรียน</a>
        </div>
        @endif

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
                <div class="banner-slide" style="min-width: 100%; height: 100%;">
                    <img src="{{ asset($banner->image_path) }}" 
                         alt="{{ $banner->title ?: 'Banner' }}" 
                         onclick="openBannerLightbox('{{ asset($banner->image_path) }}', '{{ $banner->title }}', '{{ $banner->link }}')"
                         style="width: 100%; height: 100%; max-height: 400px; object-fit: cover; display: block; cursor: pointer;"
                         class="banner-img-hover">
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

    function openBannerLightbox(src, title, link) {
        // Find index by src
        currentLightboxIndex = window.allBanners.findIndex(b => b.image_path === src);
        updateLightboxDisplay();
        
        document.getElementById('bannerLightbox').style.display = "block";
        document.body.style.overflow = 'hidden'; 
    }

    function changeLightboxBanner(direction) {
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
            if (event.key === 'ArrowLeft') changeLightboxBanner(-1);
            if (event.key === 'ArrowRight') changeLightboxBanner(1);
        }
    });
    </script>
</main>
@endsection
