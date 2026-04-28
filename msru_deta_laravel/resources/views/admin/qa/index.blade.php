@extends('layouts.admin')

@section('title', 'จัดการคำถาม Q&A (Admin) | NSRU-MS')
@section('header_title', 'จัดการคำถาม Q&A')

 

@section('content')
    <div class="admin-container">
        
        @if(session('success'))
            <div class="alert-success" role="alert">
                <p class="font-bold">สำเร็จ!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Form Add FAQ -->
        <div class="admin-card">
            <h2 class="form-heading"><i class="fa-solid fa-plus-circle text-[#7e059c]"></i> เพิ่มคำถามใหม่</h2>
            <form action="{{ route('admin.qa.faq.store') }}" method="POST">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group-3">
                        <label class="form-label">คำถาม <span class="text-xs font-normal text-gray-400 italic">(เช่น ต้องใช้เอกสารอะไรบ้างตอนมอบตัว?)</span></label>
                        <input type="text" name="question" required class="form-input" placeholder="เช่น ต้องใช้เอกสารอะไรบ้างตอนมอบตัว?">
                    </div>
                    <div>
                        <label class="form-label">หมวดหมู่</label>
                        <select name="category" class="form-input cursor-pointer">
                            <option value="หลักสูตร">หลักสูตร</option>
                            <option value="การรับสมัคร">การรับสมัคร</option>
                            <option value="ทุนกู้ยืม">ทุนกู้ยืม</option>
                            <option value="กิจกรรม">กิจกรรม</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group-full mt-4">
                    <label class="form-label">คำตอบ <span class="text-xs font-normal text-gray-400 italic">(ใช้ &lt;br&gt; สำหรับขึ้นบรรทัดใหม่)</span></label>
                    <textarea name="answer" required rows="4" class="form-input" placeholder="พิมพ์คำตอบที่นี่..."></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>

        <!-- General FAQ List -->
        <div class="admin-table-card">
            <div class="admin-table-header">
                <h2 class="card-title"><i class="fa-solid fa-list-ul text-[#7e059c]"></i> รายการ FAQ ทั้งหมด ({{ count($faqs) }} รายการ)</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="admin-table-thead-tr">
                        <tr>
                            <th scope="col" class="admin-table-th w-[80px]">ลำดับ</th>
                            <th scope="col" class="admin-table-th w-[120px]">หมวดหมู่</th>
                            <th scope="col" class="admin-table-th">คำถาม / คำตอบ</th>
                            <th scope="col" class="admin-table-th-right w-[120px]">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @if ($faqs->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">ยังไม่มีข้อมูล FAQ ในขณะนี้</td>
                        </tr>
                        @else
                            @foreach ($faqs as $f)
                            <tr class="admin-table-tr">
                                <td class="admin-table-td text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="admin-table-td">
                                    <span class="badge-purple">
                                        @php 
                                            $cat_map = ['course'=>'หลักสูตร', 'admission'=>'การรับสมัคร', 'scholarship'=>'ทุนกู้ยืม', 'activity'=>'กิจกรรม', 'other'=>'อื่นๆ'];
                                            echo $cat_map[$f->category] ?? $f->category;
                                        @endphp
                                    </span>
                                </td>
                                <td class="admin-table-td">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white mb-1">{{ $f->question }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{!! strip_tags($f->answer) !!}</div>
                                </td>
                                <td class="admin-table-td text-right text-sm font-medium whitespace-nowrap">
                                    <button onclick='editFaq(@json($f))' class="action-btn action-btn-indigo mr-1" title="แก้ไข">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    
                                    <button type="button" onclick="openDeleteModal('{{ route('admin.qa.faq.destroy') }}', {{ $f->id }})" class="action-btn action-btn-red" title="ลบ">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-table-card">
            <div class="admin-table-header">
                <h2 class="card-title"><i class="fa-solid fa-envelope-open-text text-[#7e059c]"></i> คำถามจากผู้ใช้ ({{ count($user_questions) }} รายการ)</h2>
                @if(!$status)
                    <span class="text-xs text-gray-500 italic">* แสดง 20 รายการล่าสุด</span>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-4 p-6 pb-2">
                <div class="filter-tabs-container">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <a href="{{ route('admin.qa.index', array_merge(request()->query(), ['status' => ''])) }}" 
                       class="filter-tab {{ !$status ? 'filter-tab-active' : 'filter-tab-inactive' }}">
                       <i class="fa-solid fa-clock-rotate-left"></i> 20 อันล่าสุด
                    </a>
                    <a href="{{ route('admin.qa.index', array_merge(request()->query(), ['status' => 'all'])) }}" 
                       class="filter-tab {{ $status === 'all' ? 'filter-tab-active' : 'filter-tab-inactive' }}">
                       <i class="fa-solid fa-list-ul"></i> ทั้งหมด
                    </a>
                    <a href="{{ route('admin.qa.index', array_merge(request()->query(), ['status' => 'waiting'])) }}" 
                       class="filter-tab {{ $status === 'waiting' ? 'bg-orange-500 text-white border-orange-500 shadow-lg shadow-orange-500/20 transform scale-105' : 'filter-tab-inactive' }}">
                       <i class="fa-solid fa-hourglass-half"></i> รอการตอบ
                    </a>
                    <a href="{{ route('admin.qa.index', array_merge(request()->query(), ['status' => 'answered'])) }}" 
                       class="filter-tab {{ $status === 'answered' ? 'bg-green-600 text-white border-green-600 shadow-lg shadow-green-600/20 transform scale-105' : 'filter-tab-inactive' }}">
                       <i class="fa-solid fa-check-double"></i> ตอบแล้ว
                    </a>
                    <a href="{{ route('admin.qa.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" 
                       class="filter-tab {{ $status === 'rejected' ? 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-600/20 transform scale-105' : 'filter-tab-inactive' }}">
                       <i class="fa-solid fa-ban"></i> ปฏิเสธ
                    </a>
                </div>

                <div class="md:ml-auto date-picker-container">
                    <label class="date-picker-label flex items-center">
                        <i class="fa-solid fa-calendar-day mr-2 text-[#7e059c]"></i> วันที่ถาม
                    </label>
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="date-picker-input">
                    @if($date)
                        <a href="{{ route('admin.qa.index', request()->except('date')) }}" class="text-red-400 hover:text-red-600 transition-colors ml-1" title="ล้างวันที่">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="admin-table-thead-tr">
                        <tr>
                            <th scope="col" class="admin-table-th w-[100px]">วันที่</th>
                            <th scope="col" class="admin-table-th w-[120px]">สถานะ</th>
                            <th scope="col" class="admin-table-th">เรื่อง / รายละเอียด</th>
                            <th scope="col" class="admin-table-th-right w-[150px]">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @if ($user_questions->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                ยังไม่มีคำถามจากผู้ใช้ในขณะนี้
                            </td>
                        </tr>
                        @else
                            @foreach ($user_questions as $q)
                            <tr class="admin-table-tr">
                                <td class="admin-table-td text-xs text-gray-500">
                                    {{ date('d/m/Y H:i', strtotime($q->created_at)) }}
                                </td>
                                <td class="admin-table-td">
                                    @php
                                        $badge_class = 'badge-waiting';
                                        $status_text = 'รอการตอบ';
                                        if($q->status == 'answered') { $badge_class = 'badge-answered'; $status_text = 'ตอบแล้ว'; }
                                        elseif($q->status == 'rejected') { $badge_class = 'badge-rejected'; $status_text = 'ปฏิเสธ'; }
                                    @endphp
                                    <span class="badge-status {{ $badge_class }}">
                                        {{ $status_text }}
                                    </span>
                                    <br>
                                    <span class="text-micro-muted">
                                        <i class="fa-solid {{ $q->privacy == 'public' ? 'fa-globe' : 'fa-lock' }}"></i> 
                                        {{ $q->privacy == 'public' ? 'สาธารณะ' : 'ส่วนตัว' }}
                                    </span>
                                </td>
                                <td class="admin-table-td">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white mb-1">{{ $q->title }}</div>
                                    <div class="question-desc">{!! nl2br(e($q->details)) !!}</div>
                                    @if ($q->answer)
                                        <div class="admin-reply-box">
                                            <div class="admin-reply-label">คำตอบจากแอดมิน:</div>
                                            <div class="admin-reply-text">{!! nl2br(e($q->answer)) !!}</div>
                                        </div>
                                    @endif
                                </td>
                                <td class="admin-table-td text-right text-sm font-medium whitespace-nowrap">
                                    <button onclick='openReplyModal(@json($q))' class="action-btn action-btn-purple mr-1" title="ตอบกลับ">
                                        <i class="fa-solid fa-reply"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.qa.question.status') }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="q_id" value="{{ $q->id }}">
                                        @if ($q->status == 'waiting')
                                            <input type="hidden" name="status" value="answered">
                                            <button type="submit" class="action-btn action-btn-green" title="ทำเครื่องหมายว่าตอบแล้ว">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        @elseif ($q->status == 'rejected')
                                            <input type="hidden" name="status" value="waiting">
                                            <button type="submit" class="action-btn action-btn-blue" title="ดึงกลับมารอการตอบ">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </button>
                                        @else
                                            <input type="hidden" name="status" value="waiting">
                                            <button type="submit" class="action-btn action-btn-orange" title="เปลี่ยนสถานะเป็นรอการตอบ">
                                                <i class="fa-solid fa-undo"></i>
                                            </button>
                                        @endif
                                    </form>

                                    @if($q->status !== 'rejected' && $q->status !== 'answered')
                                    <form action="{{ route('admin.qa.question.status') }}" method="POST" class="inline-block ml-1">
                                        @csrf
                                        <input type="hidden" name="q_id" value="{{ $q->id }}">
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="action-btn action-btn-orange" title="ปฏิเสธคำถามนี้">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <button type="button" onclick="openDeleteModal('{{ route('admin.qa.question.destroy') }}', {{ $q->id }})" class="action-btn action-btn-red ml-1" title="ลบคำถามนี้">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold">แก้ไขคำถาม</h3>
                <button onclick="closeEditModal()" class="modal-close"><i class="fa-solid fa-xmark fa-lg"></i></button>
            </div>
            <form action="{{ route('admin.qa.faq.update') }}" method="POST" class="p-8">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-grid">
                    <div class="form-group-3">
                        <label class="form-label">คำถาม</label>
                        <input type="text" name="question" id="edit_question" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">หมวดหมู่</label>
                        <select name="category" id="edit_category" class="form-input cursor-pointer">
                            <option value="หลักสูตร">หลักสูตร</option>
                            <option value="การรับสมัคร">การรับสมัคร</option>
                            <option value="ทุนกู้ยืม">ทุนกู้ยืม</option>
                            <option value="กิจกรรม">กิจกรรม</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group-full mt-4">
                    <label class="form-label">คำตอบ <span class="text-xs font-normal text-gray-400 italic">(ใช้ &lt;br&gt; สำหรับขึ้นบรรทัดใหม่)</span></label>
                    <textarea name="answer" id="edit_answer" required rows="6" class="form-input"></textarea>
                </div>
                
                <div class="modal-footer pt-6">
                    <button type="button" onclick="closeEditModal()" class="btn-cancel px-6 py-2.5">ยกเลิก</button>
                    <button type="submit" class="btn-save bg-[#7e059c] text-white px-8 py-2.5 rounded-lg hover:bg-[#6a0485] transition-colors font-medium ml-2 shadow-md">
                        <i class="fa-solid fa-save mr-2"></i>บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editFaq(faq) {
            document.getElementById('edit_id').value = faq.id;
            document.getElementById('edit_question').value = faq.question;
            document.getElementById('edit_answer').value = faq.answer;
            document.getElementById('edit_category').value = faq.category;
            
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }
    </script>

    <div id="replyModal" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fa-solid fa-reply mr-2"></i> ตอบคำถามผู้ใช้</h3>
                <button onclick="closeReplyModal()" class="modal-close">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.qa.question.reply') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="q_id" id="reply_q_id">
                
                <div class="mb-4">
                    <label class="form-label-sm">คำถามจากผู้ใช้:</label>
                    <div id="reply_q_title" class="question-title"></div>
                    <div id="reply_q_details" class="question-details"></div>
                </div>

                <div class="mb-6">
                    <label for="reply_answer" class="form-label-sm">คำตอบของคุณ:</label>
                    <textarea name="answer" id="reply_answer" rows="5" class="form-textarea" placeholder="พิมพ์คำตอบที่นี่..." required></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeReplyModal()" class="btn-cancel-lg">ยกเลิก</button>
                    <button type="submit" class="btn-submit-lg">ส่งคำตอบ</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReplyModal(q) {
            document.getElementById('reply_q_id').value = q.id;
            document.getElementById('reply_q_title').textContent = q.title;
            document.getElementById('reply_q_details').textContent = q.details;
            document.getElementById('reply_answer').value = q.answer || '';
            document.getElementById('replyModal').classList.remove('hidden');
            document.getElementById('replyModal').classList.add('flex');
        }

        function closeReplyModal() {
            document.getElementById('replyModal').classList.add('hidden');
            document.getElementById('replyModal').classList.remove('flex');
        }
    </script>
    
    <div id="deleteModal" class="modal-overlay hidden">
        <div class="modal-content-sm">
            <div class="modal-header-danger">
                <h3 class="modal-title-danger"><i class="fa-solid fa-triangle-exclamation icon-margin"></i> ยืนยันการลบข้อมูล</h3>
                <button type="button" onclick="closeDeleteModal()" class="modal-close-danger">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="" method="POST" id="deleteModalForm" class="p-6">
                @csrf
                <input type="hidden" name="id" id="delete_modal_id">
                <div class="modal-body-center">
                    <i class="fa-regular fa-trash-can icon-trash-lg"></i>
                    <p class="text-muted-sm">คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?<br><span class="text-danger">หากลบแล้วข้อมูลจะหายไปอย่างถาวร</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeDeleteModal()" class="btn-cancel">ยกเลิก</button>
                    <button type="submit" class="btn-danger">ลบข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(actionUrl, itemId) {
            document.getElementById('deleteModalForm').action = actionUrl;
            document.getElementById('delete_modal_id').value = itemId;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
@endsection
