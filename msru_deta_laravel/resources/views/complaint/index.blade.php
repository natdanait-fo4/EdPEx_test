@extends('layouts.app')

@section('title', 'ระบบแจ้งเรื่องร้องเรียน คณะวิทยาการจัดการ')



@section('content')
    <!-- Standard Page Header -->
    <header class="page-header">
        <div class="container text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">ระบบแจ้งเรื่องร้องเรียน <span class="whitespace-nowrap">และติดตามสถานะ</span></h1>
            <p class="text-gray-500 dark:text-gray-400">คุณสามารถแจ้งปัญหาหรือเสนอแนะ เพื่อนำไปปรุงปรุงคุณภาพการบริการของคณะ
            </p>
        </div>
    </header>

    <!-- Main Content Section (QA Style) -->
    <div class="qa-main-wrapper">
        <div class="container qa-layout">

            <!-- Left Column: Form -->
            <div class="qa-left-col">
                <div class="form-card">

                    <div class="form-header">
                        <i class="fa-solid fa-file-pen"></i> แจ้งเรื่องร้องเรียน/เสนอแนะ
                    </div>

                    <form action="{{ route('complaint.store') }}" method="POST" enctype="multipart/form-data"
                        class="needs-form">
                        @csrf
                        <div class="form-group">
                            <label>หัวข้อเรื่อง (Topic) <span class="required">*</span></label>
                            <input type="text" name="topic" value="{{ old('topic') }}"
                                placeholder="เช่น แอร์ไม่เย็น, เสนอแนะที่จอดรถ" required />
                        </div>

                        <div class="form-group">
                            <label>รายละเอียด (Description) <span class="required">*</span></label>
                            <textarea name="description" rows="6"
                                placeholder="อธิบายปัญหาที่พบ เช่น แอร์ไม่เย็นที่ห้อง 1042"
                                required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>แนบรูปภาพ/ไฟล์หลักฐาน (ทางเลือก)</label>
                            <div class="relative group">
                                <input type="file" name="file" id="fileInput" class="hidden" accept=".jpg,.jpeg,.png,.pdf"
                                    onchange="updateFileName(this)" />
                                <label for="fileInput" id="fileLabel"
                                    style="min-height: 180px; padding-top: 2rem; padding-bottom: 2rem;"
                                    class="flex flex-col items-center justify-center w-full border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                                    
                                    <!-- Default State -->
                                    <div id="defaultUploadState" class="flex flex-col items-center justify-center w-full h-full">
                                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 group-hover:text-[#7e059c] transition-colors mb-2"></i>
                                        <p id="fileName" class="text-xs text-center px-4 text-gray-500">คลิกเพื่ออัปโหลดไฟล์ (JPG, PNG, PDF สูงสุด 5MB)</p>
                                    </div>
                                    
                                    <!-- Image Preview State -->
                                    <div id="imagePreviewContainer" class="hidden flex-col items-center w-full px-4">
                                        <div class="relative w-full max-w-xs rounded-lg overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
                                            <img id="imagePreview" src="#" alt="Preview" class="w-full h-40 object-cover bg-gray-100 dark:bg-gray-800" />
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                <span class="text-white text-sm font-medium"><i class="fa-solid fa-pen mr-1"></i> เปลี่ยนรูปภาพ</span>
                                            </div>
                                        </div>
                                        <p id="previewFileName" class="text-xs text-[#7e059c] font-bold mt-3 text-center truncate w-full"></p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="form-submit-row">
                            <button type="submit" class="btn-submit">
                                <i class="fa-solid fa-paper-plane"></i> ส่งเรื่องร้องเรียน
                            </button>
                        </div>
                        <p class="text-center text-[10px] text-gray-400 font-medium mt-4">*
                            ข้อมูลส่วนตัวของคุณจะถูกเก็บเป็นความลับ</p>
                    </form>
                </div>
            </div>

            <!-- Right Column: Sidebar (History) -->
            <div class="qa-right-col">
                <div class="history-card">
                    <div class="history-header">
                        <i class="fa-solid fa-clock-rotate-left"></i> ประวัติการแจ้งเรื่องของคุณ
                    </div>

                    <div class="history-list" style="margin-top: 20px;">
                        @forelse($complaints as $complaint)
                            <div class="history-item" style="cursor: pointer;"
                                onclick="showComplaintDetails(this)" 
                                data-topic="{{ htmlspecialchars($complaint->topic) }}" 
                                data-desc="{{ htmlspecialchars($complaint->description) }}" 
                                data-reply="{{ htmlspecialchars($complaint->reply ?? '') }}" 
                                data-status="{{ $complaint->status }}">
                                <div class="h-title">{{ Str::limit($complaint->topic, 60) }}</div>
                                <div class="h-meta">
                                    <div class="h-status {{ $complaint->status == 'pending' ? 'status-waiting' : ($complaint->status == 'processing' ? 'status-waiting' : 'status-answered') }}"
                                        style="{{ $complaint->status == 'processing' ? 'background-color: #fffbeb; color: #b45309;' : '' }}">
                                        <i class="fa-solid fa-circle" style="font-size: 8px;"></i>
                                        @if($complaint->status == 'pending') รอดำเนินการ
                                        @elseif($complaint->status == 'processing') กำลังดำเนินการ
                                        @else ดำเนินการเสร็จสิ้น
                                        @endif
                                    </div>
                                    <span class="h-date">{{ $complaint->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #888; padding: 30px 0;">
                                <i class="fa-solid fa-inbox fa-2x mb-2" style="opacity: 0.3;"></i>
                                <p style="font-size: 0.9rem;">ยังไม่มีประวัติการแจ้งเรื่อง</p>
                            </div>
                        @endforelse
                    </div>

                    @if($complaints && $complaints->count() > 5)
                        <a href="#" class="view-all-history">ดูประวัติทั้งหมด</a>
                    @endif
                </div>
            </div>

        </div>
    </div>
    </main>

    <script>
        function updateFileName(input) {
            const fileNameEl = document.getElementById('fileName');
            const defaultState = document.getElementById('defaultUploadState');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const imagePreview = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileName = file.name;
                
                // ตรวจสอบว่าเป็นไฟล์รูปภาพหรือไม่
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        document.getElementById('previewFileName').innerText = 'เลือกไฟล์แล้ว: ' + fileName;
                        defaultState.classList.add('hidden');
                        previewContainer.classList.remove('hidden');
                        previewContainer.classList.add('flex');
                    }
                    reader.readAsDataURL(file);
                } else {
                    // ถ้าไม่ใช่รูปภาพ (เช่น PDF)
                    defaultState.classList.remove('hidden');
                    previewContainer.classList.add('hidden');
                    previewContainer.classList.remove('flex');
                    fileNameEl.innerText = 'เลือกไฟล์แล้ว: ' + fileName;
                    fileNameEl.classList.remove('text-gray-500');
                    fileNameEl.classList.add('text-[#7e059c]', 'font-bold');
                }
            } else {
                // กรณีที่ยกเลิกการเลือกไฟล์
                defaultState.classList.remove('hidden');
                previewContainer.classList.add('hidden');
                previewContainer.classList.remove('flex');
                fileNameEl.innerText = 'คลิกเพื่ออัปโหลดไฟล์ (JPG, PNG, PDF สูงสุด 5MB)';
                fileNameEl.classList.remove('text-[#7e059c]', 'font-bold');
                fileNameEl.classList.add('text-gray-500');
            }
        }
    </script>
    @push('scripts')
    <script>
        function showComplaintDetails(element) {
            const topic = element.getAttribute('data-topic');
            const desc = element.getAttribute('data-desc');
            const reply = element.getAttribute('data-reply');
            const status = element.getAttribute('data-status');
            
            let statusHtml = '';
            if (status === 'pending') {
                statusHtml = '<span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:12px; background:#f3f4f6; color:#4b5563; font-weight:600;"><i class="fa-solid fa-circle-exclamation text-xs mr-1"></i> รอดำเนินการ</span>';
            } else if (status === 'processing') {
                statusHtml = '<span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:12px; background:#fffbeb; color:#b45309; font-weight:600;"><i class="fa-solid fa-clock text-xs mr-1"></i> กำลังดำเนินการ</span>';
            } else {
                statusHtml = '<span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:12px; background:#f0fdf4; color:#15803d; font-weight:600;"><i class="fa-solid fa-circle-check text-xs mr-1"></i> ดำเนินการเสร็จสิ้น</span>';
            }

            const replyHtml = reply 
                ? `<div style="background:#f9fafb; padding:15px; border-radius:10px; border-left:4px solid #7e059c; text-align:left; margin-top:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05);"><p style="font-weight:bold; font-size:13px; color:#7e059c; margin-bottom:8px;"><i class="fa-solid fa-comment-dots"></i> การดำเนินการจากเจ้าหน้าที่:</p><p style="margin:0; font-size:14px; white-space:pre-wrap; line-height:1.5; color:#374151;">${reply}</p></div>` 
                : `<div style="background:#f9fafb; padding:15px; border-radius:10px; border-left:4px solid #9ca3af; text-align:left; margin-top:20px;"><p style="color:#6b7280; font-size:14px; margin:0; font-style:italic;"><i class="fa-solid fa-hourglass-half"></i> อยู่ระหว่างดำเนินการ...</p></div>`;

            Swal.fire({
                title: '<div style="font-size:18px; font-weight:700; color:#111827; text-align:left; line-height:1.4;">' + topic + '</div>',
                html: `
                    <div style="text-align:left; margin-bottom:15px;">
                        ${statusHtml}
                    </div>
                    <div style="text-align:left; margin-bottom:15px; background:#ffffff; border:1px solid #e5e7eb; padding:15px; border-radius:10px;">
                        <p style="font-weight:bold; font-size:13px; color:#6b7280; margin-bottom:8px;"><i class="fa-solid fa-align-left"></i> รายละเอียด:</p>
                        <p style="margin:0; font-size:14px; color:#374151; white-space:pre-wrap; line-height:1.6;">${desc}</p>
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