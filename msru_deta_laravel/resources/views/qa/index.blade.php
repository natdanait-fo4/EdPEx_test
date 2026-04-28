@extends('layouts.app')

@section('title', 'ศูนย์ช่วยเหลือและถามตอบ (Q&A) | NSRU-MS DETA SHIP')

@section('content')

    <!-- Header Section (White) -->
    <div class="qa-hero">
        <div class="container">
            <h2>ศูนย์ช่วยเหลือและถามตอบ (Q&A)</h2>
            <p>ค้นหาคำตอบที่คุณต้องการ หรือตั้งคำถามใหม่เพื่อให้เจ้าหน้าที่ช่วยเหลือ</p>
            
            <div class="qa-search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" class="qa-search-input" placeholder="พิมพ์คำค้น เช่น ค่าเทอม, ทุนการศึกษา...">
            </div>
            
            <div class="qa-tags">
                <span class="qa-tag active" data-target="all">ทั้งหมด</span>
                <span class="qa-tag" data-target="course">หลักสูตร</span>
                <span class="qa-tag" data-target="admission">การรับสมัคร</span>
                <span class="qa-tag" data-target="scholarship">ทุนกู้ยืม</span>
                <span class="qa-tag" data-target="activity">กิจกรรม</span>
            </div>
        </div>
    </div>

    <!-- Main Content Section (Gray) -->
    <div class="qa-main-wrapper">
        <div class="container qa-layout">
            
            <!-- Left Column: FAQ -->
            <div class="qa-left-col">
                <div class="qa-section-header">
                    <i class="fa-regular fa-comment-dots" style="color: var(--primary-color);"></i> คำถามที่พบบ่อย (FAQ)
                </div>
                
                <div class="faq-list">
                    @if ($faqs->isEmpty())
                        <div style="padding: 30px; text-align: center; color: #888; background: #fff; border-radius: 8px;">
                            <i class="fa-solid fa-circle-info fa-2x mb-3" style="color: var(--primary-color);"></i>
                            <br>ยังไม่มีคำถามในระบบ
                        </div>
                    @else
                        @foreach($faqs as $index => $faq)
                            <div class="faq-item {{ $index === 0 ? 'active' : '' }}" data-category="{{ $faq->category }}">
                                <div class="faq-question">
                                    <span class="faq-q-text"><span class="faq-icon-text">{{ $index === 0 ? '[-]' : '[+]' }}</span> {{ $faq->question }}</span>
                                    <i class="fa-solid {{ $index === 0 ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                                </div>
                                <div class="faq-answer" style="{{ $index === 0 ? '' : 'display: none;' }}">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- User Public Inquiries Section -->
                @if($user_questions->isNotEmpty())
                <div class="public-inquiries-section" style="margin-top: 50px;">
                    <h2 class="qa-section-header">
                        <i class="fa-solid fa-comments text-primary-color"></i> คำถามที่น่าสนใจจากผู้ใช้จริง
                    </h2>
                    <div class="faq-list">
                        @foreach($user_questions as $q)
                        <div class="faq-item">
                            <div class="faq-question">
                                <span class="faq-q-text">
                                    <span class="faq-icon-text">[+]</span> {{ $q->title }}
                                </span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div style="margin-bottom: 12px; font-size: 0.9rem; color: #666;">
                                    <strong>รายละเอียด:</strong> {!! nl2br(e($q->details)) !!}
                                </div>
                                <div style="background: #fdf2ff; border-left: 3px solid var(--primary-color); padding: 15px; border-radius: 4px; border: 1px solid #f5e6ff; background-color: #fdf8ff;">
                                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--primary-color); text-transform: uppercase; margin-bottom: 5px;">คำตอบจากทางมหาวิทยาลัย:</div>
                                    <div style="color: #333; font-weight: 500;">{!! nl2br(e($q->answer)) !!}</div>
                                </div>
                                <div style="margin-top: 10px; font-size: 0.75rem; color: #999; text-align: right;">
                                    <i class="fa-regular fa-clock"></i> เมื่อวันที่ {{ date('d/m/Y', strtotime($q->created_at)) }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Column: Sidebar -->
            <div class="qa-right-col">
                <div class="ask-card">
                    <h3>ไม่พบคำตอบที่ต้องการ?</h3>
                    <p>คุณสามารถตั้งคำถามใหม่เพื่อให้เจ้าหน้าที่ของเราช่วยตอบได้</p>
                    <button class="btn-ask"><i class="fa-solid fa-plus"></i> ตั้งกระทู้ถาม</button>
                </div>
                <div class="history-card">
                    <div class="history-header">
                        <i class="fa-regular fa-clock"></i> ประวัติคำถามของฉัน
                    </div>
                    
                    @if(auth()->check() && $my_questions->count() > 0)
                        <div class="history-list" style="margin-top: 15px;">
                            @foreach($my_questions as $q)
                                <div class="mb-3 rounded-lg history-item-click" 
                                     data-title="{{ $q->title }}"
                                     data-details="{{ $q->details }}"
                                     data-answer="{{ $q->answer }}"
                                     data-status="{{ $q->status }}"
                                     data-date="{{ \Carbon\Carbon::parse($q->created_at)->translatedFormat('d/m/Y H:i') }}"
                                     style="background: rgba(255,255,255,0.03); border-left: 4px solid {{ $q->status == 'answered' ? '#10b981' : '#f59e0b' }}; padding: 12px 16px; cursor: pointer; transition: background 0.2s;"
                                     onmouseover="this.style.background='rgba(255,255,255,0.08)'"
                                     onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                                    <div style="font-size: 0.95rem; font-weight: 500; margin-bottom: 8px;">{{ $q->title }}</div>
                                    <div style="font-size: 0.8rem; color: #888; display: flex; justify-content: space-between; align-items: center;">
                                        <span>
                                            <i class="fa-solid fa-circle text-[8px] mr-1" style="color: {{ $q->status == 'answered' ? '#10b981' : '#f59e0b' }}"></i>
                                            {{ $q->status == 'answered' ? 'ตอบแล้ว' : 'รอการตอบกลับ' }}
                                        </span>
                                        <div style="display: flex; gap: 12px; align-items: center;">
                                            <span>{{ \Carbon\Carbon::parse($q->created_at)->diffForHumans() }}</span>
                                            @if($q->status == 'waiting')
                                                <form action="{{ route('qa.user_destroy', $q->id) }}" method="POST" onsubmit="event.stopPropagation(); return confirm('คุณต้องการลบคำถามนี้ใช่หรือไม่?');" style="margin: 0; padding: 0;">
                                                    @csrf
                                                    <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.85rem; cursor: pointer; padding: 0; opacity: 0.8; transition: 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'" title="ยกเลิกคำถาม">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; color: #888; padding: 20px 0; font-size: 0.9rem;">
                            @if(!auth()->check())
                                กรุณาเข้าสู่ระบบเพื่อดูประวัติ
                            @else
                                ยังไม่มีประวัติการตั้งคำถาม
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- New Question Modal -->
    <div id="askModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>ตั้งกระทู้ถามใหม่</h3>
                <span class="close-modal">&times;</span>
            </div>
            <form action="{{ route('qa.store') }}" method="POST" id="qaForm">
                @csrf
                <input type="hidden" name="privacy" id="qaPrivacy" value="public">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>หัวข้อเรื่อง</label>
                        <input type="text" name="title" id="qaTitle" class="form-control" placeholder="เช่น สอบถามเรื่องค่าเทอมสาขาคอมธุรกิจ">
                        <div id="qaTitleError" class="error-text"><i class="fa-solid fa-asterisk"></i> กรุณากรอกหัวข้อเรื่อง</div>
                    </div>
                    
                    <div class="form-group">
                        <label>รายละเอียดคำถาม</label>
                        <textarea name="details" id="qaDesc" class="form-control" rows="4" placeholder="อธิบายรายละเอียดหรือข้อสงสัยของคุณเพิ่มเติม..."></textarea>
                        <div id="qaDescError" class="error-text"><i class="fa-solid fa-asterisk"></i> กรุณากรอกรายละเอียดคำถาม</div>
                    </div>
                    
                    <div class="form-group">
                        <label>ความเป็นส่วนตัวของคำถาม</label>
                        <div class="privacy-options">
                            <div class="privacy-btn active" data-privacy="public">
                                <i class="fa-solid fa-earth-americas"></i> ถามแบบสาธารณะ
                            </div>
                            <div class="privacy-btn" data-privacy="private">
                                <i class="fa-solid fa-lock"></i> ถามส่วนตัว
                            </div>
                        </div>
                        <p class="privacy-note" id="privacyNote">* คำถามแบบสาธารณะจะแสดงในหน้า FAQ เพื่อเป็นประโยชน์ต่อผู้อื่น</p>
                    </div>
                    
                    <div class="form-group checkbox-group" style="margin-bottom: 0;">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="notify_email" checked>
                            <span class="checkmark"></span>
                            แจ้งเตือนทางอีเมลเมื่อได้รับคำตอบ
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel">ยกเลิก</button>
                    <button type="button" class="btn btn-submit-qa">ส่งคำถาม</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Reply Modal -->
    <div id="viewReplyModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>รายละเอียดคำถามของฉัน</h3>
                <span class="close-view-modal" style="cursor:pointer; font-size:1.5rem; font-weight:bold; color:#888;">&times;</span>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">หัวข้อเรื่อง</div>
                    <div id="viewReplyTitle" style="font-size: 1.1rem; font-weight: 600; color: #333;"></div>
                    <div style="font-size: 0.8rem; color: #999; margin-top: 5px;"><i class="fa-regular fa-clock"></i> ถามเมื่อ: <span id="viewReplyDate"></span></div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">รายละเอียดคำถาม</div>
                    <div id="viewReplyDetails" style="background: #f9f9f9; padding: 12px; border-radius: 6px; font-size: 0.95rem; color: #444; white-space: pre-wrap; font-family: inherit; line-height: 1.5;"></div>
                </div>

                <div id="viewReplyAnswerSection" style="display: none; margin-top:20px;">
                    <div style="font-size: 0.85rem; color: var(--primary-color); font-weight: 600; margin-bottom: 5px;">คำตอบจากทางมหาวิทยาลัย</div>
                    <div id="viewReplyAnswer" style="background: #fdf8ff; border-left: 3px solid var(--primary-color); padding: 15px; border-radius: 4px; border: 1px solid #f5e6ff; font-size: 0.95rem; color: #333; white-space: pre-wrap; font-family: inherit; line-height: 1.5;"></div>
                </div>

                <div id="viewReplyWaitingSection" style="display: none; margin-top:20px; text-align: center; padding: 20px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 6px; color: #b45309;">
                    <i class="fa-regular fa-clock fa-2x mb-2"></i>
                    <div>กำลังรอการตอบกลับจากเจ้าหน้าที่</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel close-view-modal-btn">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- ลอจิก JavaScript หลัก -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple Accordion Logic for FAQ
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                const answer = item.querySelector('.faq-answer');
                const icon = item.querySelector('.faq-question i');
                
                faqItems.forEach(otherItem => {
                    otherItem.classList.remove('active');
                    if (otherItem.querySelector('.faq-answer')) otherItem.querySelector('.faq-answer').style.display = 'none';
                    if (otherItem.querySelector('.faq-question i')) otherItem.querySelector('.faq-question i').className = 'fa-solid fa-chevron-down';
                    if (otherItem.querySelector('.faq-icon-text')) otherItem.querySelector('.faq-icon-text').textContent = '[+]';
                });
                
                if (!isActive) {
                    item.classList.add('active');
                    if (answer) answer.style.display = 'block';
                    if (icon) icon.className = 'fa-solid fa-chevron-up';
                    const iconText = item.querySelector('.faq-icon-text');
                    if (iconText) iconText.textContent = '[-]';
                }
            });
        });
        
        // Category Tags Logic
        const qaTags = document.querySelectorAll('.qa-tag');
        qaTags.forEach(tag => {
            tag.addEventListener('click', function() {
                qaTags.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const target = this.getAttribute('data-target');
                faqItems.forEach(item => {
                    if (target === 'all' || item.getAttribute('data-category') === target) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                    item.classList.remove('active');
                    item.querySelector('.faq-answer').style.display = 'none';
                    item.querySelector('.faq-question i').className = 'fa-solid fa-chevron-down';
                    item.querySelector('.faq-icon-text').textContent = '[+]';
                });
            });
        });

        // Modal Logic
        const modal = document.getElementById('askModal');
        const btnAsk = document.querySelector('.btn-ask');
        const spanClose = document.querySelector('.close-modal');
        const btnCancel = document.querySelector('.btn-cancel');

        btnAsk.addEventListener('click', () => modal.style.display = 'flex');
        const closeModal = () => modal.style.display = 'none';
        spanClose.addEventListener('click', closeModal);
        btnCancel.addEventListener('click', closeModal);

        window.addEventListener('click', (event) => {
            if (event.target === modal) closeModal();
        });

        // Privacy Options Logic
        const privacyBtns = document.querySelectorAll('.privacy-btn');
        const privacyNote = document.getElementById('privacyNote');
        privacyBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                privacyBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const privacyValue = this.getAttribute('data-privacy');
                document.getElementById('qaPrivacy').value = privacyValue;
                if (privacyValue === 'public') {
                    privacyNote.textContent = '* คำถามแบบสาธารณะจะแสดงในหน้า FAQ เพื่อเป็นประโยชน์ต่อผู้อื่น';
                } else {
                    privacyNote.textContent = '* คำถามส่วนตัวจะมองเห็นได้เฉพาะคุณและเจ้าหน้าที่เท่านั้น';
                }
            });
        });

        // Form Validation Logic
        const btnSubmitQa = document.querySelector('.btn-submit-qa');
        const qaTitle = document.getElementById('qaTitle');
        const qaDesc = document.getElementById('qaDesc');
        const qaTitleError = document.getElementById('qaTitleError');
        const qaDescError = document.getElementById('qaDescError');
        btnSubmitQa.addEventListener('click', function() {
            let isValid = true;
            if (qaTitle.value.trim() === '') {
                qaTitle.classList.add('input-error');
                qaTitleError.style.display = 'block';
                isValid = false;
            } else {
                qaTitle.classList.remove('input-error');
                qaTitleError.style.display = 'none';
            }
            if (qaDesc.value.trim() === '') {
                qaDesc.classList.add('input-error');
                qaDescError.style.display = 'block';
                isValid = false;
            } else {
                qaDesc.classList.remove('input-error');
                qaDescError.style.display = 'none';
            }
            if (isValid) {
                document.getElementById('qaForm').submit();
            }
        });

        qaTitle.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('input-error');
                qaTitleError.style.display = 'none';
            }
        });
        qaDesc.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('input-error');
                qaDescError.style.display = 'none';
            }
        });

        @if(session('success'))
            alert('{{ session('success') }}');
        @endif

        // View Reply Modal Logic
        const viewModal = document.getElementById('viewReplyModal');
        const spanCloseView = document.querySelector('.close-view-modal');
        const btnCloseView = document.querySelector('.close-view-modal-btn');
        const historyItems = document.querySelectorAll('.history-item-click');

        const closeViewModal = () => viewModal.style.display = 'none';
        
        if (spanCloseView) spanCloseView.addEventListener('click', closeViewModal);
        if (btnCloseView) btnCloseView.addEventListener('click', closeViewModal);

        window.addEventListener('click', (event) => {
            if (event.target === viewModal) closeViewModal();
        });

        historyItems.forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target.closest('form') || e.target.tagName.toLowerCase() === 'button' || e.target.closest('.fa-trash-can')) {
                    return; 
                }

                const title = this.getAttribute('data-title');
                const details = this.getAttribute('data-details');
                const answer = this.getAttribute('data-answer');
                const status = this.getAttribute('data-status');
                const date = this.getAttribute('data-date');

                document.getElementById('viewReplyTitle').textContent = title || '-';
                document.getElementById('viewReplyDate').textContent = date || '-';
                document.getElementById('viewReplyDetails').textContent = details || '-';

                if (status === 'answered') {
                    document.getElementById('viewReplyAnswerSection').style.display = 'block';
                    document.getElementById('viewReplyWaitingSection').style.display = 'none';
                    document.getElementById('viewReplyAnswer').textContent = answer || '-';
                } else {
                    document.getElementById('viewReplyAnswerSection').style.display = 'none';
                    document.getElementById('viewReplyWaitingSection').style.display = 'block';
                }

                viewModal.style.display = 'flex';
            });
        });
    });
    </script>
@endpush
