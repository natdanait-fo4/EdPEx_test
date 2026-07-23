@extends('layouts.app')

@section('title', 'แบบประเมินความพึงพอใจ | NSRU-MS DETA SHIP')
@push('head')
<style>
    /* Dropdown Navigation styling */
    .assessment-dropdown-container {
        max-width: 1100px;
        margin: 0 auto 25px;
        text-align: left;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #e5e7eb;
    }
    
    .select-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .agency-select {
        width: 100%;
        padding: 14px 24px;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--text-main);
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: #ffffff;
        cursor: pointer;
        transition: all 0.25s ease;
        padding-right: 50px;
    }
    
    .agency-select:hover {
        background-color: #f9fafb;
        border-color: var(--primary-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(126, 5, 156, 0.05);
    }
    
    .agency-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(126, 5, 156, 0.1);
        background-color: #ffffff;
    }
    
    .select-arrow {
        position: absolute;
        right: 22px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        font-size: 1rem;
        color: var(--text-muted);
        transition: all 0.2s;
    }
    
    /* Overall Score Card styling */
    .score-banner-card {
        margin: 0 auto 30px;
        max-width: 1100px;
        border-top: 6px solid var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        padding: 25px 40px;
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        flex-wrap: wrap;
    }

    .score-banner-divider {
        border-left: 2px solid #f3f4f6;
    }
    
    /* Navigation buttons at the bottom */
    .navigation-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 35px;
        padding-top: 25px;
        border-top: 1px solid #f0f0f0;
    }
    
    .btn-prev {
        padding: 12px 25px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background-color: #ffffff;
        color: var(--text-main);
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    
    .btn-prev:hover {
        background-color: #f9fafb;
        border-color: #d1d5db;
        transform: translateX(-2px);
    }
    
    .btn-next {
        padding: 12px 30px;
        border-radius: 10px;
        border: none;
        background-color: var(--primary-color);
        color: #ffffff;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        margin-left: auto;
        box-shadow: 0 4px 10px rgba(126, 5, 156, 0.2);
    }
    
    .btn-next:hover {
        background-color: #6a0485;
        transform: translateX(2px);
        box-shadow: 0 6px 15px rgba(126, 5, 156, 0.3);
    }

    .btn-submit {
        padding: 12px 30px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, var(--primary-color) 0%, #9d09c1 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(126, 5, 156, 0.3);
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #6a0485 0%, #7e059c 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(126, 5, 156, 0.4);
    }
    
    /* Fade in animation for tab contents */
    .tab-pane {
        animation: fadeIn 0.4s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Tabs UI */
    .tab-btn {
        position: relative;
    }
    .tab-btn::after {
        display: none;
    }

    /* Sliding Indicator */
    .tab-indicator {
        position: absolute;
        bottom: 8px;
        left: 0;
        height: 3px;
        background-color: var(--primary-color);
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        pointer-events: none;
        z-index: 1;
        border-radius: 3px;
    }

    /* Rating Row Hover Effect */
    .rating-row {
        padding: 18px 15px;
        border-bottom: 1px solid #f9fafb;
        transition: all 0.25s ease;
    }
    .rating-row:hover {
        background-color: #fdf8ff;
        border-radius: 10px;
        border-bottom-color: transparent;
    }

    /* Rating Stars gold override */
    .rating-stars {
        color: #d1d5db !important;
        display: flex;
        gap: 12px;
        font-size: 1.6rem;
    }
    .rating-stars i {
        cursor: pointer;
        transition: transform 0.15s ease, color 0.15s ease;
    }
    .rating-stars i:hover {
        transform: scale(1.25);
    }
    .rating-stars i:hover,
    .rating-stars i.star-active {
        color: #eab308 !important;
    }

    /* Dark Mode overrides */
    html.dark .assessment-dropdown-container {
        background-color: var(--card-bg);
        border-color: var(--border-color);
    }
    html.dark .score-banner-card {
        background-color: var(--card-bg);
        border-color: var(--border-color);
    }
    html.dark .score-banner-divider {
        border-left-color: var(--border-color);
    }
    html.dark .agency-select {
        background-color: var(--card-bg);
        border-color: var(--border-color);
    }
    html.dark .agency-select:hover {
        background-color: #2d3748;
    }
    html.dark .tab-btn {
        color: var(--text-muted) !important;
    }
    html.dark .tab-btn.active-tab {
        color: #e9abff !important;
    }
    html.dark .tab-indicator {
        background-color: #e9abff;
    }
    html.dark .rating-row {
        border-bottom-color: var(--border-color) !important;
    }
    html.dark .rating-row:hover {
        background-color: #2d213f !important;
    }
    html.dark .btn-prev {
        background-color: var(--card-bg);
        border-color: var(--border-color);
    }
    html.dark .btn-prev:hover {
        background-color: #2d3748;
    }
    html.dark .rating-stars {
        color: #4b5563 !important;
    }
    html.dark .rating-stars i:hover,
    html.dark .rating-stars i.star-active {
        color: #eab308 !important;
    }

    /* Dropdown menu, rating box, alert, and avg score styles */
    .dropdown-menu {
        background-color: #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 12px;
        border: 1px solid #f0f0f0;
    }
    
    .rating-box {
        background-color: #ffffff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        padding: 10px 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.01);
    }
    
    .info-alert-box {
        background-color: #fdf8ff;
        border-left: 4px solid var(--primary-color);
        padding: 25px 30px;
        border-radius: 12px;
        text-align: left;
        max-width: 800px;
        margin: 20px auto;
        box-shadow: 0 4px 12px rgba(126,5,156,0.02);
    }

    .avg-score-badge {
        font-size: 0.85rem;
        color: #4b5563;
        margin-left: 10px;
        font-weight: bold;
        background-color: #fffdf5;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #fef3c7;
        display: inline-flex;
        align-items: center;
    }

    .assessment-card {
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
    }

    /* Dark Mode overrides for added elements */
    html.dark .dropdown-menu {
        background-color: var(--card-bg) !important;
        border-color: var(--border-color) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.35) !important;
    }
    html.dark .dropdown-menu a {
        color: var(--text-main) !important;
        border-bottom-color: var(--border-color) !important;
    }
    html.dark .dropdown-menu a:hover {
        background-color: #2d213f !important;
        color: #e9abff !important;
    }
    html.dark .rating-box {
        background-color: #374151 !important;
        border-color: var(--border-color) !important;
    }
    html.dark .info-alert-box {
        background-color: #2d213f !important;
        border-color: var(--primary-color) !important;
    }
    html.dark .avg-score-badge {
        background-color: #2d2615 !important;
        border-color: #6b4f10 !important;
        color: #fcd34d !important;
    }
    html.dark .assessment-card {
        background-color: var(--card-bg) !important;
        border-color: var(--border-color) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
    }
</style>
@endpush

@section('content')
<main class="container main-content">
    
    <!-- Standard Page Header -->
    <header class="page-header" style="padding-bottom: 20px; border-bottom: none; margin-bottom: 10px;">
        <div class="container">
            <h2 style="font-size: 2.2rem; color: var(--text-main); font-weight: 700; text-align: center; margin-bottom: 10px;">แบบประเมินความพึงพอใจ</h2>
        </div>
    </header>

    <!-- Select Major/Branch Dropdown -->
    <div class="assessment-dropdown-container">
        <label for="majorSelect" style="display: block; font-weight: 700; margin-bottom: 12px; font-size: 1.1rem; color: var(--text-main);">
            <i class="fa-solid fa-graduation-cap" style="margin-right: 6px; color: var(--primary-color);"></i> เลือกสาขา
        </label>
        <div class="select-wrapper">
            @php
                $userMajor = auth()->check() ? auth()->user()->major : '';
                $selectedMajor = '';
                if ($userMajor) {
                    if (str_contains($userMajor, 'บัญชี')) $selectedMajor = 'สาขาการบัญชี';
                    elseif (str_contains($userMajor, 'ตลาด')) $selectedMajor = 'สาขาการตลาดดิจิทัล';
                    elseif (str_contains($userMajor, 'นิเทศ')) $selectedMajor = 'สาขานิเทศศาสตร์';
                    elseif (str_contains($userMajor, 'เศรษฐ')) $selectedMajor = 'สาขาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่';
                    elseif (str_contains($userMajor, 'คอม') || str_contains($userMajor, 'เทคโน') || str_contains($userMajor, 'ธุรกิจดิจิทัล')) $selectedMajor = 'สาขาคอมพิวเตอร์ธุรกิจ / ธุรกิจดิจิทัลและเทคโนโลยี';
                    elseif (str_contains($userMajor, 'ท่อง') || str_contains($userMajor, 'โรงแรม')) $selectedMajor = 'สาขาการท่องเที่ยวและการโรงแรม';
                    elseif (str_contains($userMajor, 'ทรัพยากร') || str_contains($userMajor, 'องค์การ')) $selectedMajor = 'สาขาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ';
                }
            @endphp
            <select id="majorSelect" name="major" class="agency-select" required form="assessmentForm" style="text-align-last: left; padding-left: 20px;">
                <option value="" disabled {{ !old('major', $selectedMajor) ? 'selected' : '' }}>-- กรุณาเลือกสาขา --</option>
                <option value="สาขาการบัญชี" {{ old('major', $selectedMajor) === 'สาขาการบัญชี' ? 'selected' : '' }}>สาขาการบัญชี</option>
                <option value="สาขาการตลาดดิจิทัล" {{ old('major', $selectedMajor) === 'สาขาการตลาดดิจิทัล' ? 'selected' : '' }}>สาขาการตลาดดิจิทัล</option>
                <option value="สาขาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ" {{ old('major', $selectedMajor) === 'สาขาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ' ? 'selected' : '' }}>สาขาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ</option>
                <option value="สาขาคอมพิวเตอร์ธุรกิจ / ธุรกิจดิจิทัลและเทคโนโลยี" {{ old('major', $selectedMajor) === 'สาขาคอมพิวเตอร์ธุรกิจ / ธุรกิจดิจิทัลและเทคโนโลยี' ? 'selected' : '' }}>สาขาคอมพิวเตอร์ธุรกิจ / ธุรกิจดิจิทัลและเทคโนโลยี</option>
                <option value="สาขาการท่องเที่ยวและการโรงแรม" {{ old('major', $selectedMajor) === 'สาขาการท่องเที่ยวและการโรงแรม' ? 'selected' : '' }}>สาขาการท่องเที่ยวและการโรงแรม</option>
                <option value="สาขานิเทศศาสตร์" {{ old('major', $selectedMajor) === 'สาขานิเทศศาสตร์' ? 'selected' : '' }}>สาขานิเทศศาสตร์</option>
                <option value="สาขาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่" {{ old('major', $selectedMajor) === 'สาขาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่' ? 'selected' : '' }}>สาขาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่</option>
                <option value="บุคคลภายนอก / ผู้รับบริการทั่วไป" {{ old('major', $selectedMajor) === 'บุคคลภายนอก / ผู้รับบริการทั่วไป' ? 'selected' : '' }}>บุคคลภายนอก / ผู้รับบริการทั่วไป</option>
            </select>
            <i class="fa-solid fa-chevron-down select-arrow"></i>
        </div>
    </div>

    <!-- Overall Score Banner -->
    <div class="score-banner-card">
        <div style="text-align: center;">
            <div style="font-size: 4rem; font-weight: 800; color: var(--primary-color); line-height: 1;">{{ number_format($overallScore, 1) }}</div>
            <div style="color: var(--text-muted); font-size: 1.05rem; font-weight: 600; margin-top: 8px;">คะแนนภาพรวม</div>
        </div>
        <div class="score-banner-divider" style="padding-left: 30px; display: flex; flex-direction: column; gap: 10px; min-width: 250px;">
            <div style="display: flex; gap: 6px; font-size: 1.8rem; color: #f5b011;">
                @for($i=1; $i<=5; $i++)
                    @if($i <= round($overallScore))
                        <i class="fa-solid fa-star"></i>
                    @else
                        <i class="fa-regular fa-star"></i>
                    @endif
                @endfor
            </div>
            <div style="color: var(--text-muted); font-size: 0.95rem; display: flex; align-items: center; gap: 10px; font-weight: 500;">
                <i class="fa-solid fa-users text-purple-400"></i> อ้างอิงจากผู้ประเมินทั้งหมด <span style="font-weight: 700; color: var(--text-main); font-size: 1.1rem;">{{ $totalUsers }}</span> คน
            </div>
        </div>
    </div>


    <!-- Horizontal Tabs Navigation -->
    <div class="assessment-tabs-container" style="margin: 0 auto 25px; max-width: 1100px; position: relative; z-index: 10;">
        <div class="assessment-tabs" style="display: flex; gap: 10px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px; justify-content: center; flex-wrap: wrap; position: relative;">
            <!-- Tab คณะ -->
            <button type="button" id="tabBtnFaculty" class="tab-btn active-tab" onclick="selectCategoryTab('คณะ', 1)" style="padding: 12px 24px; border: none; background: transparent; font-weight: 700; font-size: 1.1rem; color: var(--primary-color); cursor: pointer; transition: all 0.25s;">
                <i class="fa-solid fa-hotel" style="margin-right: 6px;"></i> คณะ
            </button>
            
            <!-- Tab หลักสูตร -->
            <button type="button" id="tabBtnCurriculum" class="tab-btn" onclick="selectCategoryTab('หลักสูตร', 2)" style="padding: 12px 24px; border: none; background: transparent; font-weight: 700; font-size: 1.1rem; color: var(--text-muted); cursor: pointer; transition: all 0.25s;">
                <i class="fa-solid fa-graduation-cap" style="margin-right: 6px;"></i> หลักสูตร
            </button>
            
            <!-- Tab สำนักอื่นๆ (Dropdown) -->
            <div class="other-offices-dropdown-wrapper" style="position: relative; display: inline-block;">
                <button type="button" id="otherOfficesTabBtn" class="tab-btn" onclick="toggleOtherOfficesDropdown(event)" style="padding: 12px 24px; border: none; background: transparent; font-weight: 700; font-size: 1.1rem; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.25s;">
                    <i class="fa-solid fa-building" style="margin-right: 6px;"></i> <span id="otherOfficesLabel">สำนักอื่นๆ</span> <i class="fa-solid fa-chevron-down" style="font-size: 0.85rem;"></i>
                </button>
                <div id="otherOfficesDropdown" class="dropdown-menu" style="display: none; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); width: 320px; z-index: 1000; margin-top: 8px; text-align: left; overflow: hidden;">
                    @php $secIndex = 1; @endphp
                    @foreach($groupedQuestions as $category => $questions)
                        @if($category !== 'สำนักงานคณะวิทยาการจัดการ' && $category !== 'หลักสูตร')
                            <a href="javascript:void(0)" onclick="selectCategoryTab('สำนักอื่นๆ', {{ $secIndex }}, '{{ $category }}')" style="display: block; padding: 12px 20px; color: var(--text-main); font-weight: 600; text-decoration: none; border-bottom: 1px solid #f9fafb; font-size: 0.95rem; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#fdf8ff'; this.style.color='var(--primary-color)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-main)';">
                                {{ $category ?: 'คำถามทั่วไป' }}
                            </a>
                        @endif
                        @php $secIndex++; @endphp
                    @endforeach
                </div>
            </div>
            
            <!-- Sliding Tab Indicator -->
            <div id="tabIndicator" class="tab-indicator"></div>
        </div>
    </div>

    <!-- แบบประเมินที่ 1: แบบประเมินเดิม (Original) -->
    <div id="originalAssessmentSection" style="display: block;">
        <div class="assessment-card" style="margin: 0 auto 50px; max-width: 1100px;">
            @php
                $hasAssessed = session('has_assessed') || (auth()->check() && (auth()->user()->has_assessed || \App\Models\AssessmentResponse::where('user_id', auth()->id())->exists()));
            @endphp
            @if($hasAssessed)
                <div id="assessedMessage" style="text-align: center; padding: 50px 20px 60px 20px;">
                    <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 25px; letter-spacing: -0.025em;">คุณทำแบบประเมินแล้ว</h1>
                    <button type="button" onclick="document.getElementById('assessmentForm').style.display='block'; document.getElementById('assessedMessage').style.display='none';" style="background-color: transparent; color: var(--text-main); border: 2px solid var(--text-muted); padding: 8px 40px; border-radius: 8px; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='var(--text-muted)'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-main)';">
                        แก้ไข
                    </button>
                </div>
            @endif

            <form id="assessmentForm" action="{{ route('assessment.store') }}{{ request()->has('from_logout') ? '?from_logout=1' : '' }}" method="POST" style="display: {{ $hasAssessed ? 'none' : 'block' }};">
                @csrf
                <div class="assessment-body">
                @php 
                    $sectionIndex = 1; 
                    $totalSections = count($groupedQuestions);
                @endphp
                @foreach($groupedQuestions as $category => $questions)
                <!-- Section {{ $sectionIndex }} -->
                <div class="section-container {{ $sectionIndex == 1 ? 'tab-pane' : '' }}" id="section-{{ $sectionIndex }}" style="display: {{ $sectionIndex == 1 ? 'block' : 'none' }};">
                    <div class="section-title" style="justify-content: space-between; border-bottom: 2px solid #fdf8ff; padding-bottom: 10px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="font-weight: 700; font-size: 1.25rem; color: var(--text-main);">{{ $category ?: 'คำถามทั่วไป' }}</span>
                        </div>
                        <div class="section-score" style="display: none; align-items: center; font-size: 1.8rem; font-weight: 700; color: var(--text-main);">
                            <span class="score-value">0</span><span style="font-size: 1.2rem; color: var(--text-muted); margin: 0 5px;">/ 5</span>
                            <i class="fa-solid fa-star" style="color: #eab308; margin-left: 5px;"></i>
                        </div>
                    </div>
                    <div class="rating-box">
                        @foreach($questions as $question)
                        <div class="rating-row">
                            <div class="rating-text" style="font-size: 0.95rem; font-weight: 500; color: var(--text-main);">
                                {{ $question->question_text }}
                                @if($question->answers_avg_score > 0)
                                    <span class="avg-score-badge" title="คะแนนเฉลี่ยปัจจุบัน">
                                        <i class="fa-solid fa-star" style="color: #eab308; margin-right: 2px;"></i> {{ number_format($question->answers_avg_score, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div class="rating-stars" data-input="answers_{{ $question->id }}" style="font-size: 1.6rem; color: #d1d5db; display: flex; gap: 8px;">
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>
                            <input type="hidden" name="answers[{{ $question->id }}]" id="answers_{{ $question->id }}" value="0">
                        </div>
                        @endforeach
                    </div>

                   <div class="suggestion-box" style="margin-top: 25px;">
                        <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">ข้อเสนอแนะเพิ่มเติมสำหรับ {{ $category ?: 'คำถามทั่วไป' }} (ถ้ามี)</div>
                        <textarea name="suggestions[{{ $sectionIndex }}]" rows="3" placeholder="พิมพ์ข้อเสนอแนะ หรือรายละเอียดเพิ่มเติมสำหรับ {{ $category ?: 'คำถามทั่วไป' }}..."></textarea>
                    </div>
                    
                    <div class="navigation-row">
                        @if($sectionIndex > 1)
                            <button type="button" class="btn-prev" onclick="switchTab({{ $sectionIndex - 1 }})">
                                <i class="fa-solid fa-chevron-left"></i> ย้อนกลับ
                            </button>
                        @else
                            <div></div>
                        @endif
                        
                        @if($loop->last)
                            <button type="submit" class="btn-submit"><i class="fa-regular fa-paper-plane"></i> ส่งผลการประเมิน</button>
                        @else
                            <button type="button" class="btn-next" onclick="switchTab({{ $sectionIndex + 1 }})">
                                ถัดไป <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
                @php $sectionIndex++; @endphp
                @endforeach


            </div>
            </form>
        </div>
    </div>

</main>
@endsection

@push('scripts')
<script>
function selectCategoryTab(type, sectionIdx, categoryName) {
    
    // Hide/Show Form
    const formEl = document.getElementById('assessmentForm');
    
    // Reset active tab styles
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active-tab');
        btn.style.color = 'var(--text-muted)';
    });
    
    if (type === 'คณะ') {
        const tabFaculty = document.getElementById('tabBtnFaculty');
        if (tabFaculty) {
            tabFaculty.classList.add('active-tab');
            tabFaculty.style.color = 'var(--primary-color)';
        }
        
        document.getElementById('otherOfficesLabel').textContent = 'สำนักอื่นๆ';
        if (formEl) formEl.style.display = 'block';
        switchSection(1);
    } else if (type === 'หลักสูตร') {
        const tabCurriculum = document.getElementById('tabBtnCurriculum');
        if (tabCurriculum) {
            tabCurriculum.classList.add('active-tab');
            tabCurriculum.style.color = 'var(--primary-color)';
        }
        
        document.getElementById('otherOfficesLabel').textContent = 'สำนักอื่นๆ';
        if (formEl) formEl.style.display = 'block';
        switchSection(2);
    } else if (type === 'สำนักอื่นๆ') {
        const tabOther = document.getElementById('otherOfficesTabBtn');
        if (tabOther) {
            tabOther.classList.add('active-tab');
            tabOther.style.color = 'var(--primary-color)';
        }
        
        document.getElementById('otherOfficesLabel').textContent = categoryName;
        if (formEl) formEl.style.display = 'block';
        switchSection(sectionIdx);
    }
    
    // Update Sliding Underline Position
    updateTabIndicator();
    
    // Close other offices dropdown
    const dropdown = document.getElementById('otherOfficesDropdown');
    if (dropdown) dropdown.style.display = 'none';
}

function switchSection(sectionIdx) {
    // Hide all section containers
    document.querySelectorAll('.section-container').forEach(el => {
        el.style.display = 'none';
        el.classList.remove('tab-pane');
    });
    
    // Show active section container
    const activeSection = document.getElementById('section-' + sectionIdx);
    if (activeSection) {
        activeSection.style.display = 'block';
        activeSection.classList.add('tab-pane');
    }
}

function toggleOtherOfficesDropdown(event) {
    if (event) event.stopPropagation();
    const dropdown = document.getElementById('otherOfficesDropdown');
    if (dropdown) {
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }
}

function switchTab(tabIndex) {
    // Find category name from section-tabIndex
    const activeSection = document.getElementById('section-' + tabIndex);
    if (activeSection) {
        if (tabIndex === 1) {
            selectCategoryTab('คณะ', 1);
        } else if (tabIndex === 2) {
            selectCategoryTab('หลักสูตร', 2);
        } else {
            const titleEl = activeSection.querySelector('.section-title');
            let categoryName = '';
            if (titleEl) {
                const clone = titleEl.cloneNode(true);
                const numEl = clone.querySelector('.circle-number');
                if (numEl) numEl.remove();
                categoryName = clone.textContent.trim();
            }
            selectCategoryTab('สำนักอื่นๆ', tabIndex, categoryName);
        }
    }
    
    // Scroll to top of the assessment card smoothly
    document.querySelector('.assessment-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('otherOfficesDropdown');
    const button = document.getElementById('otherOfficesTabBtn');
    if (dropdown && button && dropdown.style.display === 'block') {
        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    }
});

function updateTabIndicator() {
    const activeTab = document.querySelector('.tab-btn.active-tab');
    const indicator = document.getElementById('tabIndicator');
    const container = document.querySelector('.assessment-tabs');
    
    if (activeTab && indicator && container) {
        const activeRect = activeTab.getBoundingClientRect();
        const containerRect = container.getBoundingClientRect();
        
        const left = activeRect.left - containerRect.left;
        const width = activeRect.width;
        
        indicator.style.left = `${left}px`;
        indicator.style.width = `${width}px`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const starGroups = document.querySelectorAll('.rating-stars');
    
    starGroups.forEach(group => {
        const stars = group.querySelectorAll('i');
        
        stars.forEach((star, index) => {
            star.addEventListener('mouseover', function() {
                updateStars(stars, index + 1);
            });
            
            star.addEventListener('mouseout', function() {
                const currentRating = group.getAttribute('data-rating') || 0;
                updateStars(stars, currentRating);
            });
            
            star.addEventListener('click', function() {
                group.setAttribute('data-rating', index + 1);
                updateStars(stars, index + 1);
                calculateSectionScores();
                
                // Update hidden input if it exists
                const inputId = group.getAttribute('data-input');
                if (inputId) {
                    const hiddenInput = document.getElementById(inputId);
                    if (hiddenInput) {
                        hiddenInput.value = index + 1;
                    }
                }
            });
        });
    });
    
    function updateStars(stars, rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('fa-regular', 'fa-star');
                star.classList.add('fa-solid', 'fa-star', 'star-active');
            } else {
                star.classList.remove('fa-solid', 'fa-star', 'star-active');
                star.classList.add('fa-regular', 'fa-star');
            }
        });
    }

    function calculateSectionScores() {
        const ratingBoxes = document.querySelectorAll('.rating-box');
        ratingBoxes.forEach(box => {
            const starGroups = box.querySelectorAll('.rating-stars');
            let totalScore = 0;
            let answeredCount = 0;
            
            starGroups.forEach(g => {
                const rating = parseInt(g.getAttribute('data-rating') || 0);
                if (rating > 0) {
                    totalScore += rating;
                    answeredCount++;
                }
            });
            
            const sectionTitle = box.previousElementSibling;
            if (sectionTitle && sectionTitle.classList.contains('section-title')) {
                const scoreDisplay = sectionTitle.querySelector('.section-score');
                if (scoreDisplay && answeredCount > 0) {
                    let avgScore = totalScore / answeredCount;
                    let formatted = Number.isInteger(avgScore) ? avgScore : avgScore.toFixed(1);
                    
                    scoreDisplay.style.display = 'flex';
                    scoreDisplay.querySelector('.score-value').textContent = formatted;
                }
            }
        });
    }

    // Initialize Sliding Indicator
    setTimeout(updateTabIndicator, 150);
});

// Update Indicator on Window Resize
window.addEventListener('resize', updateTabIndicator);
</script>
@endpush
