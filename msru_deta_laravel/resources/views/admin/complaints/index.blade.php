@extends('layouts.admin')

@section('title', 'จัดการข้อร้องเรียน (Admin) | NSRU-MS')
@section('header_title', 'จัดการข้อร้องเรียน')

 

@section('content')
<div class="admin-container">
    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/40 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded shadow-sm" role="alert">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check mr-2"></i>
                <p class="font-bold">สำเร็จ! {{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Dashboard Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stat-card">
            <div class="stat-icon bg-red-100 text-red-600">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <div>
                <p class="stat-label">รอดำเนินการ</p>
                <p class="stat-value">{{ $stats['pending'] }} <span class="text-sm font-normal text-gray-500">เรื่อง</span></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-yellow-100 text-yellow-600">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="stat-label">กำลังดำเนินการ</p>
                <p class="stat-value">{{ $stats['processing'] }} <span class="text-sm font-normal text-gray-500">เรื่อง</span></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-green-100 text-green-600">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="stat-label">เสร็จสิ้น</p>
                <p class="stat-value">{{ $stats['completed'] }} <span class="text-sm font-normal text-gray-500">เรื่อง</span></p>
            </div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="admin-table-card">
        <div class="admin-table-header">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">รายการข้อร้องเรียนทั้งหมด</h2>
            <form action="{{ route('admin.complaints.index') }}" method="GET" class="flex items-center space-x-2">
                <i class="fa-solid fa-filter text-gray-400 text-sm"></i>
                <select name="status" onchange="this.form.submit()" class="text-sm border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#7e059c] focus:border-[#7e059c] py-1.5 px-3 border dark:bg-gray-700 dark:text-white">
                    <option value="">ทั้งหมด</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                    <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>เสร็จสิ้น</option>
                </select>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="admin-table-thead-tr">
                        <th class="admin-table-th">ลำดับ / รหัส</th>
                        <th class="admin-table-th">ผู้แจ้ง</th>
                        <th class="admin-table-th">หัวข้อเรื่อง</th>
                        <th class="admin-table-th text-center">สถานะ</th>
                        <th class="admin-table-th text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @if(count($complaints) > 0)
                        @foreach($complaints as $complaint)
                        <tr class="admin-table-tr">
                            <td class="admin-table-td">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $complaint->id }}</div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 uppercase">{{ $complaint->created_at->format('d M Y | H:i') }}</div>
                            </td>
                            <td class="admin-table-td">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $complaint->user->username }}</div>
                            </td>
                            <td class="admin-table-td">
                                <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $complaint->topic }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs mt-0.5">{{ Str::limit($complaint->description, 60) }}</div>
                            </td>
                            <td class="admin-table-td text-center">
                                @if($complaint->status == 'pending')
                                    <span class="badge-status badge-pending"><i class="fa-solid fa-circle-exclamation mr-1.5 text-[8px]"></i> รอดำเนินการ</span>
                                @elseif($complaint->status == 'processing')
                                    <span class="badge-status badge-processing"><i class="fa-solid fa-clock mr-1.5 text-[8px]"></i> กำลังดำเนินการ</span>
                                @else
                                    <span class="badge-status badge-completed"><i class="fa-solid fa-circle-check mr-1.5 text-[8px]"></i> เสร็จสิ้น</span>
                                @endif
                            </td>
                            <td class="admin-table-td text-center">
                                <button onclick="openEditModal({!! htmlspecialchars(json_encode($complaint->load('user'))) !!})" class="btn-reply">
                                    <i class="fa-solid fa-comment-dots mr-1.5"></i> ตอบกลับ
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-inbox text-4xl text-gray-200 dark:text-gray-700 mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">ไม่พบรายการข้อร้องเรียนในขณะนี้</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if($complaints->hasPages())
        <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            {{ $complaints->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit/Reply Modal -->
<div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[1000] hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700 animate-in zoom-in duration-200">
        <div class="bg-[#7e059c] text-white px-8 py-5 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold">จัดการข้อร้องเรียน #<span id="modal_id"></span></h3>
                <p class="text-purple-100 text-xs mt-0.5">ส่วนการดำเนินงานของเจ้าหน้าที่</p>
            </div>
            <button onclick="closeModal()" class="text-white/80 hover:text-white transition-colors bg-white/10 p-2 rounded-full"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="updateForm" method="POST" class="p-8">
            @csrf
            <div class="space-y-6">
                <!-- ข้อมูลผู้แจ้ง -->
                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-lg" id="modal_topic"></h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="modal_user"></p>
                        </div>
                        <div id="modal_status_badge"></div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap" id="modal_desc"></p>
                    </div>
                    <div id="modal_file" class="mt-4"></div>
                </div>

                <!-- ส่วนแอดมินแก้ไข -->
                <div class="space-y-5 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">ปรับเปลี่ยนสถานะ</label>
                            <select name="status" id="modal_status" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-transparent outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-medium transition-all">
                                <option value="pending">⏳ รอดำเนินการ (Pending)</option>
                                <option value="processing">⚙️ กำลังดำเนินการ (In Progress)</option>
                                <option value="completed">✅ เสร็จสิ้น (Completed)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">ข้อความชี้แจง / ตอบกลับผู้แจ้ง</label>
                        <textarea name="reply" id="modal_reply" rows="5" class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-transparent outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white italic text-sm leading-relaxed transition-all" placeholder="พิมพ์รายละเอียดการดำเนินงานเพื่อให้ผู้แจ้งรับทราบ..."></textarea>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-6 py-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white transition-colors font-bold text-sm">ยกเลิก</button>
                <button type="submit" class="px-8 py-2.5 bg-[#7e059c] text-white rounded-xl hover:bg-[#6a0485] shadow-lg shadow-purple-200 dark:shadow-none transition-all font-bold text-sm">อัปเดตสถานะและตอบกลับ</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(complaint) {
        document.getElementById('modal_id').innerText = complaint.id;
        document.getElementById('modal_topic').innerText = complaint.topic;
        document.getElementById('modal_user').innerText = '👤 โดย: ' + complaint.user.username + ' | 🗓️ เมื่อ: ' + new Date(complaint.created_at).toLocaleString('th-TH');
        document.getElementById('modal_desc').innerText = complaint.description;
        document.getElementById('modal_status').value = complaint.status;
        document.getElementById('modal_reply').value = complaint.reply || '';
        
        let badge = '';
        if(complaint.status === 'pending') badge = '<span class="badge-status badge-pending"><i class="fa-solid fa-circle-exclamation mr-1.5 text-[8px]"></i> รอดำเนินการ</span>';
        else if(complaint.status === 'processing') badge = '<span class="badge-status badge-processing"><i class="fa-solid fa-clock mr-1.5 text-[8px]"></i> กำลังดำเนินการ</span>';
        else badge = '<span class="badge-status badge-completed"><i class="fa-solid fa-circle-check mr-1.5 text-[8px]"></i> เสร็จสิ้น</span>';
        document.getElementById('modal_status_badge').innerHTML = badge;

        const modalFile = document.getElementById('modal_file');
        if(complaint.file_path) {
            modalFile.innerHTML = `
                <div class="text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">หลักฐานประกอบ / ไฟล์แนบ</div>
                <a href="/${complaint.file_path}" target="_blank" class="inline-flex items-center px-4 py-2 bg-purple-50 dark:bg-purple-900/20 text-[#7e059c] dark:text-purple-300 rounded-lg hover:bg-purple-100 transition-colors text-xs font-bold border border-purple-100 dark:border-purple-900/30">
                    <i class="fa-solid fa-paperclip mr-2 text-sm"></i> เปิดดูไฟล์แนบ
                </a>
            `;
        } else {
            modalFile.innerHTML = '';
        }

        document.getElementById('updateForm').action = "{{ route('admin.complaints.update', ':id') }}".replace(':id', complaint.id);
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('editModal').classList.remove('flex');
        document.getElementById('editModal').classList.add('hidden');
    }

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') closeModal();
    });
</script>
@endsection
