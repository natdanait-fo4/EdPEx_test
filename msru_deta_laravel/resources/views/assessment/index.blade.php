@extends('layouts.app')

@section('title', 'แบบประเมินความพึงพอใจ | NSRU-MS DETA SHIP')

@section('content')
<main class="container main-content">
    
    <!-- Standard Page Header -->
    <header class="page-header" style="padding-bottom: 20px;">
        <div class="container">
            <h2>แบบประเมินความพึงพอใจ</h2>
            <p>คณะวิทยาการจัดการ ขอความอนุเคราะห์ท่านตอบแบบประเมินตามความเป็นจริง คะแนนและข้อเสนอแนะของท่านจะถูกนำไปวิเคราะห์เพื่อวางแผนกลยุทธ์ พัฒนาคุณภาพการศึกษา (EdPEx) และปรับปรุงระบบเว็บไซต์ให้มีประสิทธิภาพสูงสุด</p>
        </div>
    </header>

    <!-- Overall Score Banner (Unique to Assessment Page) -->
    <div class="header-card" style="margin: 0 auto 30px; max-width: 1100px; border-top: 6px solid var(--yellow-color); display: flex; align-items: center; justify-content: center; gap: 30px; padding: 30px 40px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); flex-wrap: wrap;">
        <div style="text-align: center;">
            <div style="font-size: 4rem; font-weight: 800; color: var(--text-main); line-height: 1;">{{ number_format($overallScore, 1) }}</div>
            <div style="color: var(--text-muted); font-size: 1.05rem; font-weight: 500; margin-top: 8px;">คะแนนภาพรวม</div>
        </div>
        <div style="border-left: 2px solid #f3f4f6; padding-left: 30px; display: flex; flex-direction: column; gap: 12px; min-width: 250px;">
            <div style="display: flex; gap: 6px; font-size: 1.8rem; color: var(--yellow-color);">
                @for($i=1; $i<=5; $i++)
                    @if($i <= round($overallScore))
                        <i class="fa-solid fa-star"></i>
                    @else
                        <i class="fa-regular fa-star"></i>
                    @endif
                @endfor
            </div>
            <div style="color: var(--text-muted); font-size: 1rem; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-users opacity-70"></i> อ้างอิงจากผู้ประเมินทั้งหมด <span style="font-weight: 700; color: var(--text-main); font-size: 1.1rem;">{{ $totalUsers }}</span> คน
            </div>
        </div>
    </div>

    <div class="assessment-card">
        <!-- assessment-header removed and moved to top -->
        
        @if(session('has_assessed'))
            <div id="assessedMessage" style="text-align: center; padding: 50px 20px 60px 20px;">
                <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 25px; letter-spacing: -0.025em;">คุณทำแบบประเมินแล้ว</h1>
                <button type="button" onclick="document.getElementById('assessmentForm').style.display='block'; document.getElementById('assessedMessage').style.display='none';" style="background-color: transparent; color: var(--text-main); border: 2px solid var(--text-muted); padding: 8px 40px; border-radius: 8px; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='var(--text-muted)'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-main)';">
                    แก้ไข
                </button>
            </div>
        @endif

        <form id="assessmentForm" action="{{ route('assessment.store') }}" method="POST" style="display: {{ session('has_assessed') ? 'none' : 'block' }};">
            @csrf
            
            <div class="assessment-body">
            @php $sectionIndex = 1; @endphp
            @foreach($groupedQuestions as $category => $questions)
            <!-- Section {{ $sectionIndex }} -->
            <div class="section-title" style="justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div class="circle-number">{{ $sectionIndex }}</div>
                    {{ $category ?: 'คำถามทั่วไป' }}
                </div>
                <div class="section-score" style="display: none; align-items: center; font-size: 1.8rem; font-weight: 700; color: var(--text-main);">
                    <span class="score-value">0</span><span style="font-size: 1.2rem; color: var(--text-muted); margin: 0 5px;">/ 5</span>
                    <i class="fa-solid fa-star" style="color: var(--yellow-color);"></i>
                </div>
            </div>
            <div class="rating-box">
                @foreach($questions as $question)
                <div class="rating-row">
                    <div class="rating-text">
                        {{ $loop->iteration }}. {{ $question->question_text }}
                        @if($question->answers_avg_score > 0)
                            <span style="font-size: 0.85rem; color: var(--yellow-color); margin-left: 10px; font-weight: bold;" title="คะแนนเฉลี่ยปัจจุบัน">
                                <i class="fa-solid fa-star"></i> {{ number_format($question->answers_avg_score, 1) }}
                            </span>
                        @endif
                    </div>
                    <div class="rating-stars" data-input="answers_{{ $question->id }}">
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
            @php $sectionIndex++; @endphp
            @endforeach

            <!-- Suggestion Section -->
            <div class="section-title">
                <div class="circle-number">{{ $sectionIndex }}</div>
                ข้อเสนอแนะเพิ่มเติม
            </div>
            <div class="suggestion-box">
                <textarea name="suggestion" rows="4" placeholder="พิมพ์ข้อเสนอแนะ หรือรายละเอียดเชิงลึกที่คุณต้องการให้เราปรับปรุง..."></textarea>
            </div>

            <div class="assessment-footer">
                <button type="submit" class="btn-submit"><i class="fa-regular fa-paper-plane"></i> ส่งผลการประเมิน</button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
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
                star.classList.remove('fa-regular');
                star.classList.add('fa-solid', 'star-active');
            } else {
                star.classList.remove('fa-solid', 'star-active');
                star.classList.add('fa-regular');
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
});
</script>
@endpush
