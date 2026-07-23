@extends('layouts.admin')

@section('title', 'จัดการความต้องการ (Admin) | NSRU-MS')
@section('header_title', 'จัดการความต้องการ')

 

@section('content')
<div class="admin-container">

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
    <div class="admin-table-wrapper">
        <div class="admin-table-header">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">รายการความต้องการทั้งหมด</h2>
            <form action="{{ route('admin.requests.index') }}" method="GET" class="flex items-center space-x-2">
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
                        <th class="admin-table-th">หมวดหมู่</th>
                        <th class="admin-table-th">ความต้องการ / ข้อเสนอแนะ</th>
                        <th class="admin-table-th text-center">สถานะ</th>
                        <th class="admin-table-th text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($requests as $req)
                    <tr class="admin-table-tr">
                        <td class="admin-table-td">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $req->id }}</div>
                            <div class="text-[10px] text-gray-400 dark:text-gray-500 uppercase">{{ $req->created_at->format('d M Y | H:i') }}</div>
                        </td>
                        <td class="admin-table-td">
                            @php
                                $cat_map = ['course' => 'หลักสูตร', 'facility' => 'สิ่งอำนวยความสะดวก', 'service' => 'บริการ'];
                                $cat_name = $cat_map[$req->category] ?? $req->category;
                            @endphp
                            <span class="text-xs font-bold text-[#7e059c] dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 px-2.5 py-1 rounded-md border border-purple-100 dark:border-purple-900/30">
                                {{ $cat_name }}
                            </span>
                        </td>
                        <td class="admin-table-td">
                            <div class="text-sm text-gray-800 dark:text-gray-200 line-clamp-2 max-w-sm">{{ $req->details }}</div>
                        </td>
                        <td class="admin-table-td text-center">
                            @if($req->status == 'pending')
                                <span class="badge-status badge-pending"><i class="fa-solid fa-circle-exclamation mr-1.5 text-[8px]"></i> รอดำเนินการ</span>
                            @elseif($req->status == 'processing')
                                <span class="badge-status badge-processing"><i class="fa-solid fa-clock mr-1.5 text-[8px]"></i> กำลังดำเนินการ</span>
                            @else
                                <span class="badge-status badge-completed"><i class="fa-solid fa-circle-check mr-1.5 text-[8px]"></i> เสร็จสิ้น</span>
                            @endif
                        </td>
                        <td class="admin-table-td text-center">
                            <div class="flex justify-center items-center space-x-2">
                                <button onclick="openEditModal({{ json_encode($req->load('user')) }})" class="btn-reply">
                                    <i class="fa-solid fa-comment-dots mr-1.5"></i> ตอบกลับ
                                </button>
                                <button onclick="openDeleteRequestModal({{ $req->id }})" class="text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 px-3 py-1.5 rounded-lg transition-colors text-sm font-medium flex items-center" title="ลบ">
                                    <i class="fa-regular fa-trash-can mr-1.5"></i> ลบ
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">ไม่พบรายการความต้องการ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
        <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal -->
<div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[1000] hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700">
        <div class="bg-[#7e059c] text-white px-8 py-5 flex justify-between items-center">
            <h3 class="text-xl font-bold">จัดการความต้องการ รหัส <span id="modal_id"></span></h3>
            <button onclick="closeModal()" class="text-white/80 hover:text-white transition-colors bg-white/10 p-2 rounded-full"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="updateForm" method="POST" class="p-8">
            @csrf
            <div class="space-y-6">
                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl border border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase font-bold mb-2 tracking-widest">ข้อมูลผู้แจ้ง: <span id="modal_user" class="text-gray-700 dark:text-gray-300"></span></p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed italic" id="modal_details"></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">ปรับเปลี่ยนสถานะ</label>
                        <select name="status" id="modal_status" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-medium">
                            <option value="pending">⏳ รอดำเนินการ (Pending)</option>
                            <option value="processing">⚙️ กำลังดำเนินการ (In Progress)</option>
                            <option value="completed">✅ เสร็จสิ้น (Completed)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">ข้อความชี้แจง / ตอบกลับผู้แจ้ง</label>
                    <textarea name="reply" id="modal_reply" rows="4" class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm" placeholder="พิมพ์รายละเอียดการดำเนินงาน..."></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-6 py-2.5 text-gray-500 font-bold text-sm">ยกเลิก</button>
                <button type="submit" class="px-8 py-2.5 bg-[#7e059c] text-white rounded-xl hover:bg-[#6a0485] shadow-lg transition-all font-bold text-sm">อัปเดตข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteRequestModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[1000] hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700 p-6 text-center">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">ยืนยันการลบรายการความต้องการ?</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6 font-medium text-sm">รายการนี้จะถูกลบออกจากระบบอย่างถาวร<br>การกระทำนี้ไม่สามารถย้อนกลับได้</p>
        
        <form id="deleteRequestForm" method="POST" class="flex justify-center space-x-3">
            @csrf
            <button type="button" onclick="closeDeleteRequestModal()" class="w-full px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
            <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors shadow-sm">ยืนยันลบ</button>
        </form>
    </div>
</div>

<script>
    function openEditModal(request) {
        document.getElementById('modal_id').innerText = request.id;
        document.getElementById('modal_user').innerText = request.user.username;
        document.getElementById('modal_details').innerText = request.details;
        document.getElementById('modal_status').value = request.status;
        document.getElementById('modal_reply').value = request.reply || '';
        
        document.getElementById('updateForm').action = "{{ route('admin.requests.update', ':id') }}".replace(':id', request.id);
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    function openDeleteRequestModal(id) {
        let form = document.getElementById('deleteRequestForm');
        form.action = "{{ route('admin.requests.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        document.getElementById('deleteRequestModal').classList.remove('hidden');
        document.getElementById('deleteRequestModal').classList.add('flex');
    }

    function closeDeleteRequestModal() {
        document.getElementById('deleteRequestModal').classList.add('hidden');
        document.getElementById('deleteRequestModal').classList.remove('flex');
    }
</script>
@endsection
