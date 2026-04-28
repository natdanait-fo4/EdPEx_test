@extends('layouts.app')

@section('title', 'ระบบแจ้งเรื่องร้องเรียน คณะวิทยาการจัดการ')

 

@section('content')
    <!-- Standard Page Header -->
    <header class="page-header">
        <div class="container text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">ระบบแจ้งเรื่องร้องเรียน และติดตามสถานะ</h1>
            <p class="text-gray-500 dark:text-gray-400">คุณสามารถแจ้งปัญหาหรือเสนอแนะ เพื่อนำไปปรุงปรุงคุณภาพการบริการของคณะ</p>
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
                    
                    <form action="{{ route('complaint.store') }}" method="POST" enctype="multipart/form-data" class="needs-form">
                        @csrf
                        <div class="form-group">
                            <label>หัวข้อเรื่อง (Topic) <span class="required">*</span></label>
                            <input type="text" name="topic" value="{{ old('topic') }}" placeholder="เช่น แอร์ไม่เย็น, เสนอแนะที่จอดรถ" required />
                        </div>
                        
                        <div class="form-group">
                            <label>รายละเอียด (Description) <span class="required">*</span></label>
                            <textarea name="description" rows="6" placeholder="อธิบายปัญหาที่พบ หรือข้อเสนอแนะ..." required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>แนบรูปภาพ/ไฟล์หลักฐาน (ทางเลือก)</label>
                            <div class="relative group">
                                <input type="file" name="file" id="fileInput" class="hidden" onchange="updateFileName(this)" />
                                <label for="fileInput" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 group-hover:text-[#7e059c] transition-colors mb-2"></i>
                                    <p id="fileName" class="text-xs text-center px-4 text-gray-500">คลิกเพื่ออัปโหลดไฟล์ (JPG, PNG, PDF สูงสุด 5MB)</p>
                                </label>
                            </div>
                        </div>

                        <div class="form-submit-row">
                            <button type="submit" class="btn-submit">
                                <i class="fa-solid fa-paper-plane"></i> ส่งเรื่องร้องเรียน
                            </button>
                        </div>
                        <p class="text-center text-[10px] text-gray-400 font-medium mt-4">* ข้อมูลส่วนตัวของคุณจะถูกเก็บเป็นความลับ</p>
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
                        <div class="history-item" style="cursor: pointer;" onclick="alert('หัวข้อ: {{ addslashes($complaint->topic) }}\n\nรายละเอียด: {{ addslashes($complaint->description) }}\n\nผลการดำเนินการ: {{ $complaint->reply ? addslashes($complaint->reply) : 'อยู่ระหว่างดำเนินการ' }}')">
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
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById('fileName').innerText = 'เลือกไฟล์แล้ว: ' + fileName;
            document.getElementById('fileName').classList.remove('text-gray-500');
            document.getElementById('fileName').classList.add('text-[#7e059c]', 'font-bold');
        } else {
            document.getElementById('fileName').innerText = 'คลิกเพื่ออัปโหลดไฟล์ (JPG, PNG, PDF สูงสุด 5MB)';
            document.getElementById('fileName').classList.remove('text-[#7e059c]', 'font-bold');
            document.getElementById('fileName').classList.add('text-gray-500');
        }
    }
</script>
@endsection
