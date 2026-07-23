@extends('layouts.app')

@section('title', 'แบบฟอร์มแจ้งความต้องการ | NSRU-MS DETA SHIP')

@section('content')
<main class="container main-content">
    <!-- Standard Page Header -->
    <header class="page-header">
        <div class="container">
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
                        <div class="history-item" style="cursor: pointer;" 
                            onclick="showRequestDetails(this)"
                            data-details="{{ htmlspecialchars($item->details) }}" 
                            data-reply="{{ htmlspecialchars($item->reply ?? '') }}" 
                            data-status="{{ $item->status }}">
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

@push('scripts')
<script>
    function showRequestDetails(element) {
        const details = element.getAttribute('data-details');
        const reply = element.getAttribute('data-reply');
        const status = element.getAttribute('data-status');
        
        let statusHtml = '';
        if (status === 'pending') {
            statusHtml = '<span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:12px; background:#f3f4f6; color:#4b5563; font-weight:600;"><i class="fa-solid fa-circle-exclamation text-xs mr-1"></i> รอดำเนินการ</span>';
        } else if (status === 'processing') {
            statusHtml = '<span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:12px; background:#fffbeb; color:#b45309; font-weight:600;"><i class="fa-solid fa-clock text-xs mr-1"></i> กำลังดำเนินการ</span>';
        } else {
            statusHtml = '<span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:12px; background:#f0fdf4; color:#15803d; font-weight:600;"><i class="fa-solid fa-circle-check text-xs mr-1"></i> พิจารณาแล้ว</span>';
        }

        const replyHtml = reply 
            ? `<div style="background:#f9fafb; padding:15px; border-radius:10px; border-left:4px solid #7e059c; text-align:left; margin-top:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05);"><p style="font-weight:bold; font-size:13px; color:#7e059c; margin-bottom:8px;"><i class="fa-solid fa-comment-dots"></i> ผลการพิจารณาจากเจ้าหน้าที่:</p><p style="margin:0; font-size:14px; white-space:pre-wrap; line-height:1.5; color:#374151;">${reply}</p></div>` 
            : `<div style="background:#f9fafb; padding:15px; border-radius:10px; border-left:4px solid #9ca3af; text-align:left; margin-top:20px;"><p style="color:#6b7280; font-size:14px; margin:0; font-style:italic;"><i class="fa-solid fa-hourglass-half"></i> อยู่ระหว่างดำเนินการ...</p></div>`;

        Swal.fire({
            title: '<div style="font-size:18px; font-weight:700; color:#111827; text-align:left; line-height:1.4;"><i class="fa-solid fa-file-lines text-[#7e059c] mr-2"></i>รายละเอียดความต้องการ</div>',
            html: `
                <div style="text-align:left; margin-bottom:15px; margin-top:10px;">
                    ${statusHtml}
                </div>
                <div style="text-align:left; margin-bottom:15px; background:#ffffff; border:1px solid #e5e7eb; padding:15px; border-radius:10px;">
                    <p style="margin:0; font-size:14px; color:#374151; white-space:pre-wrap; line-height:1.6;">${details}</p>
                </div>
                ${replyHtml}
            `,
            confirmButtonText: 'ปิดหน้าต่าง',
            confirmButtonColor: '#7e059c',
            width: 550,
            padding: '2rem',
            customClass: {
                title: 'pt-0',
                htmlContainer: 'text-left'
            }
        });
    }
</script>
@endpush
@endsection
