@extends('layouts.admin')

@section('title', 'จัดการหัวข้อการประเมิน | Admin Dashboard')
@section('header_title', 'จัดการหัวข้อการประเมิน')

@section('content')
<div class="admin-card">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">หัวข้อคำถามทั้งหมด</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">แอดมินสามารถเพิ่ม ลบ หรือแก้ไขหัวข้อคำถามที่จะแสดงในหน้าประเมินได้ที่นี่</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.assessment.responses') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium transition-colors border border-gray-300 dark:border-gray-600 flex items-center">
                <i class="fa-solid fa-list-check mr-2"></i> ดูผลการประเมิน
            </a>
            <button onclick="openAddModal()" class="bg-[#7e059c] hover:bg-[#680482] text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> เพิ่มหัวข้อใหม่
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg flex items-center">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-wrapper">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="admin-table-thead-tr">
                    <th class="admin-table-th" width="100">ลำดับ</th>
                    <th class="admin-table-th" width="250">หมวดหมู่</th>
                    <th class="admin-table-th">ข้อความคำถาม</th>
                    <th class="admin-table-th text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($questions->groupBy('category') as $category => $categoryQuestions)
                <tr class="bg-gray-50/50 dark:bg-gray-700/30">
                    <td colspan="4" class="px-6 py-2 font-bold text-[#7e059c] dark:text-purple-400 border-y border-gray-100 dark:border-gray-700 text-sm">
                        <i class="fa-solid fa-folder-open mr-2"></i> {{ $category ?: 'ยังไม่มีหมวดหมู่' }}
                    </td>
                </tr>
                @foreach($categoryQuestions as $question)
                <tr class="admin-table-tr">
                    <td class="admin-table-td pl-10 text-gray-600 dark:text-gray-400">{{ $question->order }}</td>
                    <td class="admin-table-td font-medium text-gray-400 dark:text-gray-500 text-xs">{{ $question->category ?: '-' }}</td>
                    <td class="admin-table-td text-gray-700 dark:text-gray-300">{{ $question->question_text }}</td>
                    <td class="admin-table-td text-right space-x-2">
                        <button onclick="openEditModal({{ json_encode($question) }})" class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 p-2 rounded-lg transition-colors">
                            <i class="fa-regular fa-pen-to-square"></i> แก้ไข
                        </button>
                        <button onclick="openDeleteModal({{ $question->id }})" class="text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 p-2 rounded-lg transition-colors">
                            <i class="fa-regular fa-trash-can"></i> ลบ
                        </button>
                    </td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@php
    $categories = $questions->pluck('category')->filter()->unique()->values();
@endphp

<!-- Add Question Modal -->
<div id="addModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">เพิ่มหัวข้อประเมินใหม่</h3>
            <button onclick="closeModals()" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('admin.assessment.question.store') }}" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หมวดหมู่ (ถ้ามี)</label>
                
                <div id="add_select_mode">
                    <select id="add_category_select" onchange="handleAddCategoryChange(this)" class="w-full px-4 py-2 border dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">ไม่มีหมวดหมู่</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                        <option value="__NEW__" class="font-bold text-[#7e059c] bg-purple-50 dark:bg-purple-900/30">
                            + เพิ่มหมวดหมู่ใหม่...
                        </option>
                    </select>
                </div>

                <div id="add_input_mode" class="hidden relative">
                    <input type="text" id="add_category_input" placeholder="พิมพ์ชื่อหมวดหมู่ใหม่..." class="w-full px-4 py-2 border dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white pr-10">
                    <button type="button" onclick="cancelAddCategoryNew()" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-full transition-colors" title="ยกเลิกการพิมพ์หมวดหมู่ใหม่">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                
                <input type="hidden" name="category" id="add_category_final" value="">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ข้อความคำถาม *</label>
                <textarea name="question_text" required rows="3" class="w-full px-4 py-2 border dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="เช่น ความรวดเร็วในการให้บริการ"></textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ลำดับการแสดงผล</label>
                <input type="number" name="order" value="{{ ($questions->max('order') ?? 0) + 1 }}" class="w-full px-4 py-2 border dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg">บันทึก</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Question Modal -->
<div id="editModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">แก้ไขหัวข้อประเมิน</h3>
            <button onclick="closeModals()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หมวดหมู่ (ถ้ามี)</label>
                
                <div id="edit_select_mode">
                    <select id="edit_category_select" onchange="handleEditCategoryChange(this)" class="w-full px-4 py-2 border dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">ไม่มีหมวดหมู่</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                        <option value="__NEW__" class="font-bold text-[#7e059c] bg-purple-50 dark:bg-purple-900/30">
                            + เพิ่มหมวดหมู่ใหม่...
                        </option>
                    </select>
                </div>

                <div id="edit_input_mode" class="hidden relative">
                    <input type="text" id="edit_category_input" placeholder="พิมพ์ชื่อหมวดหมู่ใหม่..." class="w-full px-4 py-2 border dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white pr-10">
                    <button type="button" onclick="cancelEditCategoryNew()" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-full transition-colors" title="ยกเลิกการพิมพ์หมวดหมู่ใหม่">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                
                <input type="hidden" name="category" id="edit_category_final" value="">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ข้อความคำถาม *</label>
                <textarea name="question_text" id="edit_question_text" required rows="3" class="w-full px-4 py-2 border dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ลำดับการแสดงผล</label>
                <input type="number" name="order" id="edit_order" class="w-full px-4 py-2 border dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal-overlay hidden">
    <div class="modal-content !max-w-sm p-6 text-center">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">ยืนยันการลบ?</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6 font-medium text-sm">หัวข้อคำถามนี้จะถูกลบออกจากระบบ<br>รวมถึงข้อมูลคำตอบที่เกี่ยวข้องในกรณีที่มีการเก็บสถิติ</p>
        <form id="deleteForm" method="POST" class="flex justify-center space-x-3">
            @csrf
            <button type="button" onclick="closeModals()" class="w-full px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg">ยกเลิก</button>
            <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg">ยืนยันลบ</button>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function openEditModal(question) {
        const cat = question.category || '';
        document.getElementById('edit_category_final').value = cat;
        
        const select = document.getElementById('edit_category_select');
        document.getElementById('edit_input_mode').classList.add('hidden');
        document.getElementById('edit_select_mode').classList.remove('hidden');
        document.getElementById('edit_category_input').value = '';
        
        let exists = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === cat && cat !== '__NEW__') {
                exists = true; break;
            }
        }
        
        if (exists || cat === '') {
            select.value = cat;
        } else {
            document.getElementById('edit_select_mode').classList.add('hidden');
            document.getElementById('edit_input_mode').classList.remove('hidden');
            document.getElementById('edit_category_input').value = cat;
            select.value = '__NEW__';
        }

        document.getElementById('edit_question_text').value = question.question_text;
        document.getElementById('edit_order').value = question.order;
        
        let form = document.getElementById('editForm');
        form.action = "{{ route('admin.assessment.question.update', 'REPLACE_ID') }}".replace('REPLACE_ID', question.id);
        
        document.getElementById('editModal').classList.remove('hidden');
    }

    function openDeleteModal(id) {
        let form = document.getElementById('deleteForm');
        form.action = "{{ route('admin.assessment.question.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeModals() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.add('hidden');
        
        // Reset modes
        cancelAddCategoryNew();
        cancelEditCategoryNew();
    }

    // Add Modal Handlers
    function handleAddCategoryChange(select) {
        if (select.value === '__NEW__') {
            document.getElementById('add_select_mode').classList.add('hidden');
            document.getElementById('add_input_mode').classList.remove('hidden');
            document.getElementById('add_category_input').focus();
            document.getElementById('add_category_final').value = '';
        } else {
            document.getElementById('add_category_final').value = select.value;
        }
    }

    function cancelAddCategoryNew() {
        document.getElementById('add_input_mode').classList.add('hidden');
        document.getElementById('add_select_mode').classList.remove('hidden');
        document.getElementById('add_category_select').value = '';
        document.getElementById('add_category_final').value = '';
        document.getElementById('add_category_input').value = '';
    }

    document.getElementById('add_category_input').addEventListener('input', function(e) {
        document.getElementById('add_category_final').value = e.target.value;
    });

    // Edit Modal Handlers
    function handleEditCategoryChange(select) {
        if (select.value === '__NEW__') {
            document.getElementById('edit_select_mode').classList.add('hidden');
            document.getElementById('edit_input_mode').classList.remove('hidden');
            document.getElementById('edit_category_input').focus();
            document.getElementById('edit_category_final').value = '';
        } else {
            document.getElementById('edit_category_final').value = select.value;
        }
    }

    function cancelEditCategoryNew() {
        document.getElementById('edit_input_mode').classList.add('hidden');
        document.getElementById('edit_select_mode').classList.remove('hidden');
        document.getElementById('edit_category_select').value = '';
        document.getElementById('edit_category_final').value = '';
        document.getElementById('edit_category_input').value = '';
    }

    document.getElementById('edit_category_input').addEventListener('input', function(e) {
        document.getElementById('edit_category_final').value = e.target.value;
    });
</script>
@endsection
