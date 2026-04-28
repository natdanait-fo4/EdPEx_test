@extends('layouts.app')

@section('title', 'แบบฟอร์มแจ้งความต้องการ | NSRU-MS DETA SHIP')

@section('content')
<main class="container main-content">
    <!-- Standard Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="lightbulb-icon" style="background-color: #fef3c7; color: #d97706;">
                <i class="fa-regular fa-lightbulb"></i>
            </div>
            <h2>เสียงของท่านสำคัญต่อการพัฒนาคณะวิทยาการจัดการ</h2>
            <p>ทุกความต้องการและข้อเสนอแนะของท่าน จะถูกนำไปใช้วิเคราะห์เพื่อปรับปรุงหลักสูตร สิ่งอำนวยความสะดวก และบริการของเราให้ดียิ่งขึ้น เพื่อตอบโจทย์อนาคตของทุกคน</p>
        </div>
    </header>

    <!-- Main Content Section (QA Style) -->
    <div class="qa-main-wrapper">
        <div class="container qa-layout">
            
            <!-- Left Column: Form -->
            <div class="qa-left-col">
                <div class="form-card">
                    
                    <div class="form-header">
                        <i class="fa-regular fa-message"></i> แบบฟอร์มแจ้งความต้องการใหม่
                    </div>
                    
                    <form action="{{ route('request.store') }}" method="POST" class="needs-form">
                        @csrf
                        <div class="form-group">
                            <label>รายละเอียดความต้องการ / ข้อเสนอแนะ <span class="required">*</span></label>
                            <textarea name="details" rows="6" placeholder="โปรดอธิบายสิ่งที่ท่านต้องการให้คณะฯ ปรับปรุง หรือเพิ่มเติมอย่างละเอียด..." required>{{ old('details') }}</textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group half">
                                <label>หมวดหมู่ความต้องการ <span class="required">*</span></label>
                                <select name="category" required>
                                    <option value="">-- โปรดเลือกหมวดหมู่ --</option>
                                    <option value="course" {{ old('category') == 'course' ? 'selected' : '' }}>หลักสูตรและการเรียนการสอน</option>
                                    <option value="facility" {{ old('category') == 'facility' ? 'selected' : '' }}>สิ่งอำนวยความสะดวก</option>
                                    <option value="service" {{ old('category') == 'service' ? 'selected' : '' }}>บริการและอื่นๆ</option>
                                </select>
                            </div>
                            <div class="form-group half" style="justify-content: center; padding-top: 25px;">
                                <div class="privacy-note">
                                    <i class="fa-solid fa-circle-info"></i> ข้อมูลจะถูกเก็บเป็นความลับ
                                </div>
                            </div>
                        </div>

                        <div class="form-submit-row">
                            <button type="submit" class="btn-submit">
                                <i class="fa-regular fa-paper-plane"></i> ส่งข้อมูลความต้องการ
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Sidebar (History) -->
            <div class="qa-right-col">
                <div class="history-card">
                    <div class="history-header">
                        <i class="fa-solid fa-clock-rotate-left"></i> ประวัติการส่งของท่าน
                    </div>
                    
                    <div class="history-list" style="margin-top: 20px;">
                        @forelse ($history as $item)
                        <div class="history-item" style="cursor: pointer;" onclick="alert('รายละเอียด: {{ addslashes($item->details) }}\n\nผลการพิจารณา: {{ $item->reply ? addslashes($item->reply) : 'อยู่ระหว่างดำเนินการ' }}')">
                            <div class="h-title">{{ Str::limit($item->details, 60) }}</div>
                            <div class="h-meta">
                                <div class="h-status {{ $item->status == 'pending' ? 'status-waiting' : 'status-answered' }}">
                                    <i class="fa-solid fa-circle" style="font-size: 8px;"></i>
                                    {{ $item->status == 'pending' ? 'รอดำเนินการ' : 'พิจารณาแล้ว' }}
                                </div>
                                <span class="h-date">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @empty
                        <div style="text-align: center; color: #888; padding: 30px 0;">
                            <i class="fa-solid fa-inbox fa-2x mb-2" style="opacity: 0.3;"></i>
                            <p style="font-size: 0.9rem;">ยังไม่มีประวัติการส่งข้อมูล</p>
                        </div>
                        @endforelse
                    </div>

                    @if($history && $history->count() > 5)
                        <a href="#" class="view-all-history">ดูประวัติทั้งหมด</a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
