@extends('layouts.admin')

@section('title', 'จัดการข้อความ POL | Admin Dashboard')
@section('header_title', 'จัดการข้อความ POL (หน้าแรก)')

@section('content')
<div class="admin-card">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">ข้อความ POL ทั้งหมด</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">ข้อความเหล่านี้จะแสดงผลบริเวณสไลด์ตรงกลางของหน้าแรก</p>
        </div>
        <button onclick="openAddModal()" class="bg-[#7e059c] hover:bg-[#680482] text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> เพิ่มข้อความใหม่
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
                    <th class="admin-table-th" width="40"></th>
                    <th class="admin-table-th" width="80">ไอคอน</th>
                    <th class="admin-table-th">ชื่อ/รายการอ้างอิง</th>
                    <th class="admin-table-th">สถานะ</th>
                    <th class="admin-table-th text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody id="sortable-pols" class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($pols as $pol)
                <tr class="admin-table-tr" data-id="{{ $pol->id }}">
                    <td class="admin-table-td text-center" style="vertical-align: middle; padding-left: 15px; padding-right: 15px;">
                        <i class="fa-solid fa-list text-[#7e059c] cursor-move hover:text-[#680482] transition-colors p-2 text-base" title="ลากเพื่อจัดเรียงลำดับ"></i>
                    </td>
                    <td class="admin-table-td text-center">
                        <div style="background: rgba(126, 5, 156, 0.1); color: #7e059c; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto;">
                            <i class="{{ $pol->icon_class ?? 'fa-solid fa-bullhorn' }}"></i>
                        </div>
                    </td>
                    <td class="admin-table-td">
                        <div class="font-medium text-gray-800 dark:text-gray-200">{{ $pol->title ?: '-' }}</div>
                        @if($pol->description)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-1">{{ $pol->description }}</div>
                        @endif
                    </td>
                    <td class="admin-table-td">
                        <form action="{{ route('admin.pols.update', $pol->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="toggle_status" value="1">
                            <button type="submit" class="badge-status {{ $pol->is_active ? 'badge-completed hover:bg-green-200 dark:hover:bg-green-800/60' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }} transition-colors">
                                <i class="fa-solid {{ $pol->is_active ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                {{ $pol->is_active ? 'แสดงผล' : 'ซ่อน' }}
                            </button>
                        </form>
                    </td>
                    <td class="admin-table-td text-right">
                        <button onclick="openEditModal({{ json_encode($pol) }})" 
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 px-3 py-1.5 rounded-lg transition-colors mr-2 inline-flex items-center gap-1 font-medium text-xs border border-transparent hover:border-blue-200 dark:hover:border-blue-800/40" title="แก้ไข">
                            <i class="fa-regular fa-pen-to-square text-sm"></i> แก้ไข
                        </button>
                        <button onclick="openDeleteModal({{ $pol->id }})" 
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1 font-medium text-xs border border-transparent hover:border-red-200 dark:hover:border-red-800/40" title="ลบ">
                            <i class="fa-regular fa-trash-can text-sm"></i> ลบ
                        </button>
                    </td>
                </tr>
                @endforeach
                
                @if($pols->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        ยังไม่มีข้อความ POL
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Add POL Modal -->
<div id="addModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">เพิ่มข้อความ POL ใหม่</h3>
            <button onclick="closeModals()" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('admin.pols.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หัวข้อ (เช่น POL 8)</label>
                <input type="text" name="title" id="add_title" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="POL 8" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คำบรรยาย</label>
                <textarea name="description" id="add_description" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none" placeholder="รายละเอียดของ POL..."></textarea>
            </div>

            <div class="mb-6">
                <label for="icon_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คลาสของไอคอน (FontAwesome)</label>
                <input type="text" name="icon_class" id="add_icon_class" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="fa-solid fa-bullhorn" value="fa-solid fa-bullhorn">
                <p class="text-xs text-gray-500 mt-1">สามารถหาชื่อไอคอนได้จาก <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" class="text-blue-500 underline">FontAwesome Free</a></p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg font-medium transition-colors shadow-sm">บันทึก</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit POL Modal -->
<div id="editModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">แก้ไขรายละเอียด POL</h3>
            <button onclick="closeModals()" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label for="edit_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หัวข้อ</label>
                <input type="text" name="title" id="edit_title" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
            </div>

            <div class="mb-4">
                <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คำบรรยาย</label>
                <textarea name="description" id="edit_description" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"></textarea>
            </div>

            <div class="mb-4">
                <label for="edit_icon_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คลาสของไอคอน (FontAwesome)</label>
                <input type="text" name="icon_class" id="edit_icon_class" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="mb-6">
                <label for="edit_is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">สถานะการแสดงผล</label>
                <select name="is_active" id="edit_is_active" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="1">แสดงผล</option>
                    <option value="0">ซ่อน</option>
                </select>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg font-medium transition-colors shadow-sm">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete POL Modal -->
<div id="deleteModal" class="modal-overlay hidden">
    <div class="modal-content !max-w-sm p-6 text-center">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">ยืนยันการลบข้อความ POL?</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6 font-medium text-sm">ข้อความจะถูกลบออกจากระบบอย่างถาวร<br>การกระทำนี้ไม่สามารถย้อนกลับได้</p>
        
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

    function openEditModal(pol) {
        let form = document.getElementById('editForm');
        form.action = "{{ route('admin.pols.update', 'REPLACE_ID') }}".replace('REPLACE_ID', pol.id);
        
        document.getElementById('edit_title').value = pol.title || '';
        document.getElementById('edit_description').value = pol.description || '';
        document.getElementById('edit_icon_class').value = pol.icon_class || '';
        document.getElementById('edit_is_active').value = pol.is_active ? "1" : "0";
        
        document.getElementById('editModal').classList.remove('hidden');
    }

    function openDeleteModal(id) {
        let form = document.getElementById('deleteForm');
        form.action = "{{ route('admin.pols.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeModals() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModals();
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('sortable-pols');
        if (el) {
            new Sortable(el, {
                handle: '.cursor-move',
                animation: 150,
                onEnd: function (evt) {
                    const rows = el.querySelectorAll('tr[data-id]');
                    const order = Array.from(rows).map(row => row.getAttribute('data-id'));
                    
                    fetch("{{ route('admin.pols.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order: order })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToastSuccess('จัดเรียงลำดับ POL เรียบร้อยแล้ว');
                        }
                    })
                    .catch(err => {
                        console.error('Reorder error:', err);
                    });
                }
            });
        }
    });

    function showToastSuccess(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2 transform translate-y-10 opacity-0 transition-all duration-300';
        toast.innerHTML = `<i class="fa-solid fa-circle-check text-lg"></i> <span class="font-medium text-sm">${message}</span>`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        }, 10);

        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
</script>
@endsection
