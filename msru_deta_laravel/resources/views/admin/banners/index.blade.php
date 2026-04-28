@extends('layouts.admin')

@section('title', 'จัดการรูปภาพหน้าแรก | Admin Dashboard')
@section('header_title', 'จัดการรูปภาพแบนเนอร์ (หน้าแรก)')

@section('content')
<div class="admin-card">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">แบนเนอร์ทั้งหมด</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">รูปภาพเหล่านี้จะแสดงผลบริเวณด้านล่างของหน้าแรก</p>
        </div>
        <button onclick="openAddModal()" class="bg-[#7e059c] hover:bg-[#680482] text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> เพิ่มรูปภาพใหม่
        </button>
    </div>

    
    
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex flex-col">
            <div class="flex items-center mb-1"><i class="fa-solid fa-circle-exclamation mr-2"></i> มีข้อผิดพลาด:</div>
            <ul class="list-disc pl-8 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-table-wrapper">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="admin-table-thead-tr">
                    <th class="admin-table-th" width="150">รูปภาพ</th>
                    <th class="admin-table-th">ชื่อ/รายการอ้างอิง</th>
                    <th class="admin-table-th">สถานะ</th>
                    <th class="admin-table-th text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($banners as $banner)
                <tr class="admin-table-tr">
                    <td class="admin-table-td">
                        <img src="{{ asset($banner->image_path) }}" alt="Banner" class="h-16 w-32 object-cover rounded shadow-sm border dark:border-gray-700">
                    </td>
                    <td class="admin-table-td">
                        <div class="font-medium text-gray-800 dark:text-gray-200">{{ $banner->title ?: '-' }}</div>
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" class="text-xs text-blue-500 hover:underline"><i class="fa-solid fa-link"></i> {{ $banner->link }}</a>
                        @else
                            <div class="text-xs text-gray-400">ไม่มีลิงก์</div>
                        @endif
                    </td>
                    <td class="admin-table-td">
                        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="badge-status {{ $banner->is_active ? 'badge-completed hover:bg-green-200 dark:hover:bg-green-800/60' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }} transition-colors">
                                <i class="fa-solid {{ $banner->is_active ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                {{ $banner->is_active ? 'แสดงผล' : 'ซ่อน' }}
                            </button>
                        </form>
                    </td>
                    <td class="admin-table-td text-right">
                        <button onclick="openDeleteModal({{ $banner->id }})" 
                                class="text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 p-2 rounded-lg transition-colors" title="ลบ">
                            <i class="fa-regular fa-trash-can"></i> ลบรูป
                        </button>
                    </td>
                </tr>
                @endforeach
                
                @if($banners->isEmpty())
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        ยังไม่มีรูปภาพแบนเนอร์
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Add Banner Modal -->
<div id="addModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">เพิ่มรูปภาพหน้าแรก (Banner)</h3>
            <button onclick="closeModals()" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ไฟล์รูปภาพ * <span class="text-xs text-gray-500 dark:text-gray-400">(แนะนำ: แนวนอน)</span></label>
                <input type="file" name="image" id="add_image" accept="image/*" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หัวข้อ/ชื่อรูป (ไม่บังคับ)</label>
                <input type="text" name="title" id="add_title" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="เช่น กิจกรรมรับน้องใหม่">
            </div>

            <div class="mb-6">
                <label for="link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ลิงก์ปลายทางเมื่อคลิกรูป (ไม่บังคับ)</label>
                <input type="url" name="link" id="add_link" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="https://...">
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg font-medium transition-colors shadow-sm">อัปโหลด</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Banner Modal -->
<div id="deleteModal" class="modal-overlay hidden">
    <div class="modal-content !max-w-sm p-6 text-center">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">ยืนยันการลบรูปภาพ?</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6 font-medium text-sm">รูปภาพจะถูกลบออกจากระบบอย่างถาวร<br>การกระทำนี้ไม่สามารถย้อนกลับได้</p>
        
        <form id="deleteForm" method="POST" class="flex justify-center space-x-3">
            @csrf
            <button type="button" onclick="closeModals()" class="w-full px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
            <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors shadow-sm">ยืนยันลบ</button>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function openDeleteModal(id) {
        let form = document.getElementById('deleteForm');
        form.action = "{{ route('admin.banners.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeModals() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModals();
        }
    });
</script>
@endsection
