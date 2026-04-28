@extends('layouts.admin')

@section('title', 'จัดการผู้ใช้งาน | Admin Dashboard')
@section('header_title', 'จัดการบัญชีผู้ใช้งานระบบ')

@section('content')
<div class="admin-table-wrapper">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">รายชื่อผู้ใช้งานทั้งหมด</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">กำหนดสิทธิ์ แอดมิน หรือ ผู้ใช้ทั่วไป</p>
        </div>
        <button onclick="openAddModal()" class="bg-[#7e059c] hover:bg-[#680482] text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> เพิ่มผู้ใช้งาน
        </button>
    </div>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-full text-xs font-medium border {{ !request('role') ? 'bg-[#7e059c] text-white border-[#7e059c]' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50' }}">
            ทั้งหมด
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="px-4 py-2 rounded-full text-xs font-medium border {{ request('role') === 'admin' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50' }}">
            ผู้ดูแลระบบ (Admin)
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="px-4 py-2 rounded-full text-xs font-medium border {{ request('role') === 'user' ? 'bg-gray-600 text-white border-gray-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50' }}">
            ผู้ใช้ทั่วไป (User)
        </a>
    </div>


    
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
        </div>
    @endif

    @foreach ($errors->all() as $error)
        <div class="mb-2 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $error }}
        </div>
    @endforeach

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="admin-table-thead-tr">
                    <th class="admin-table-th">ID</th>
                    <th class="admin-table-th">ชื่อผู้ใช้งาน</th>
                    <th class="admin-table-th">ชื่อ-นามสกุล</th>
                    <th class="admin-table-th">รหัสนักศึกษา / สาขา</th>
                    <th class="admin-table-th">ระดับสิทธิ์</th>
                    <th class="admin-table-th text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($users as $user)
                <tr class="admin-table-tr">
                    <td class="admin-table-td text-gray-500 dark:text-gray-400">#{{ $user->id }}</td>
                    <td class="admin-table-td font-medium text-gray-800 dark:text-gray-200">
                        {{ $user->username }}
                        @if($user->id === auth()->id())
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">ฉันเอง</span>
                        @endif
                    </td>
                    <td class="admin-table-td">
                        <div class="text-sm text-gray-900 dark:text-white">{{ $user->fullname ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $user->phone ?? '' }}</div>
                    </td>
                    <td class="admin-table-td">
                        <div class="text-sm text-gray-900 dark:text-white">{{ $user->student_id ?? '-' }}</div>
                        <div class="text-[11px] text-gray-500">{{ $user->major ?? '-' }}</div>
                    </td>
                    <td class="admin-table-td">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fa-solid fa-shield-halved mr-1"></i> Admin
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                <i class="fa-solid fa-user mr-1"></i> User
                            </span>
                        @endif
                    </td>
                    <td class="admin-table-td text-right">
                        <button onclick="openEditModal(this)" 
                                data-id="{{ $user->id }}"
                                data-username="{{ $user->username }}"
                                data-fullname="{{ $user->fullname }}"
                                data-student_id="{{ $user->student_id }}"
                                data-major="{{ $user->major }}"
                                data-phone="{{ $user->phone }}"
                                data-address="{{ $user->address }}"
                                data-role="{{ $user->role }}"
                                class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 p-2 rounded-lg transition-colors mr-1" title="แก้ไข">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        
                        @if($user->id !== auth()->id())
                            <button onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->username) }}')" 
                                    class="text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 p-2 rounded-lg transition-colors" title="ลบ">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
                
                @if($users->isEmpty())
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        ไม่มีข้อมูลผู้ใช้งาน
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div id="addModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">เพิ่มผู้ใช้งานใหม่</h3>
            <button onclick="closeModals()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อผู้ใช้งาน (Username) *</label>
                    <input type="text" name="username" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รหัสผ่าน *</label>
                    <input type="password" name="password" required minlength="8" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อ-นามสกุล *</label>
                    <input type="text" name="fullname" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ระดับสิทธิ์ *</label>
                    <select name="role" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="user">ผู้ใช้ทั่วไป (User)</option>
                        <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รหัสนักศึกษา</label>
                    <input type="text" name="student_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">สาขาวิชา</label>
                    <input type="text" name="major" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ที่อยู่</label>
                <textarea name="address" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg font-medium shadow-sm transition-colors">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">แก้ไขข้อมูลผู้ใช้งาน</h3>
            <button onclick="closeModals()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อผู้ใช้งาน (Username) *</label>
                    <input type="text" name="username" id="edit_username" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เปลี่ยนรหัสผ่านใหม่ <span class="text-[10px] text-gray-500">(เว้นว่างไว้ถ้าไม่ต้องการเปลี่ยน)</span></label>
                    <input type="password" name="password" id="edit_password" minlength="8" placeholder="รหัสผ่านใหม่" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อ-นามสกุล *</label>
                    <input type="text" name="fullname" id="edit_fullname" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ระดับสิทธิ์ *</label>
                    <select name="role" id="edit_role" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="user">ผู้ใช้ทั่วไป (User)</option>
                        <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รหัสนักศึกษา</label>
                    <input type="text" name="student_id" id="edit_student_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">สาขาวิชา</label>
                    <input type="text" name="major" id="edit_major" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ที่อยู่</label>
                <textarea name="address" id="edit_address" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium shadow-sm transition-colors">อัปเดตข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-sm overflow-hidden transform transition-all text-center border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">ยืนยันการลบ?</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">คุณกำลังจะลบบัญชี <span id="delete_username_display" class="font-bold text-gray-700 dark:text-white"></span><br>การกระทำนี้ไม่สามารถย้อนกลับได้</p>
            
            <form id="deleteForm" method="POST" class="flex justify-center space-x-3">
                @csrf
                <button type="button" onclick="closeModals()" class="w-full px-4 py-2 text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium shadow-sm transition-colors">ยืนยันการลบ</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function openEditModal(btn) {
        const id = btn.getAttribute('data-id');
        const username = btn.getAttribute('data-username');
        const fullname = btn.getAttribute('data-fullname');
        const student_id = btn.getAttribute('data-student_id');
        const major = btn.getAttribute('data-major');
        const phone = btn.getAttribute('data-phone');
        const address = btn.getAttribute('data-address');
        const role = btn.getAttribute('data-role');

        let form = document.getElementById('editForm');
        form.action = "{{ route('admin.users.update', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_fullname').value = fullname || '';
        document.getElementById('edit_student_id').value = student_id || '';
        document.getElementById('edit_major').value = major || '';
        document.getElementById('edit_phone').value = phone || '';
        document.getElementById('edit_address').value = address || '';
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_password').value = ''; 
        
        document.getElementById('editModal').classList.remove('hidden');
    }

    function openDeleteModal(id, username) {
        let form = document.getElementById('deleteForm');
        form.action = "{{ route('admin.users.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        document.getElementById('delete_username_display').textContent = username;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeModals() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeModals();
    });
</script>
@endsection
