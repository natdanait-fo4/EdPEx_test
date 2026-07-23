@extends('layouts.admin')

@section('title', 'จัดการข้อมูลหน้าแรก | Admin Dashboard')
@section('header_title', 'จัดการข้อมูลหน้าแรก (แบนเนอร์ & PLO)')

@section('content')

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

<!-- ========================================== -->
<!-- SECTION 2: PLOS -->
<!-- ========================================== -->
<div class="admin-card mb-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">รูปภาพ PLO ทั้งหมด</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">รูปภาพเหล่านี้จะแสดงผลบริเวณสไลด์ตรงกลางของหน้าแรก</p>
        </div>
        <button onclick="openAddPloModal()" class="bg-[#7e059c] hover:bg-[#680482] text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> เพิ่มรูปภาพ PLO ใหม่
        </button>
    </div>

    <div class="admin-table-wrapper">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="admin-table-thead-tr">
                    <th class="admin-table-th" width="40"></th>
                    <th class="admin-table-th" width="100">รูปภาพ/ไอคอน</th>
                    <th class="admin-table-th">ชื่อ/รายการอ้างอิง</th>
                    <th class="admin-table-th">สถานะ</th>
                    <th class="admin-table-th text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody id="sortable-plos" class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($plos as $plo)
                <tr class="admin-table-tr" data-id="{{ $plo->id }}">
                    <td class="admin-table-td text-center" style="vertical-align: middle; padding-left: 15px; padding-right: 15px;">
                        <i class="fa-solid fa-list text-[#7e059c] cursor-move hover:text-[#680482] transition-colors p-2 text-base" title="ลากเพื่อจัดเรียงลำดับ"></i>
                    </td>
                    <td class="admin-table-td text-center">
                        @if($plo->image_path)
                            <img src="{{ asset($plo->image_path) }}" alt="PLO" class="h-24 w-16 object-cover rounded shadow-sm border dark:border-gray-700 mx-auto">
                        @else
                            <div style="background: rgba(126, 5, 156, 0.1); color: #7e059c; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto;">
                                <i class="{{ $plo->icon_class ?? 'fa-solid fa-bullhorn' }}"></i>
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-td">
                        <div class="font-medium text-gray-800 dark:text-gray-200">
                            {{ $plo->title ?: '-' }}
                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full {{ $plo->degree_level === 'master' ? 'bg-blue-100 text-blue-800' : ($plo->degree_level === 'doctoral' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800') }}">
                                {{ $plo->degree_level === 'master' ? 'ป.โท' : ($plo->degree_level === 'doctoral' ? 'ป.เอก' : 'ป.ตรี') }}
                            </span>
                        </div>
                        @if($plo->description)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-1">{{ $plo->description }}</div>
                        @endif
                    </td>
                    <td class="admin-table-td">
                        <form action="{{ route('admin.plos.update', $plo->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="toggle_status" value="1">
                            <button type="submit" class="badge-status {{ $plo->is_active ? 'badge-completed hover:bg-green-200 dark:hover:bg-green-800/60' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }} transition-colors">
                                <i class="fa-solid {{ $plo->is_active ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                {{ $plo->is_active ? 'แสดงผล' : 'ซ่อน' }}
                            </button>
                        </form>
                    </td>
                    <td class="admin-table-td text-right">
                        <button onclick="openEditPloModal({{ json_encode($plo) }})" 
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 px-3 py-1.5 rounded-lg transition-colors mr-2 inline-flex items-center gap-1 font-medium text-xs border border-transparent hover:border-blue-200 dark:hover:border-blue-800/40" title="แก้ไข">
                            <i class="fa-regular fa-pen-to-square text-sm"></i> แก้ไข
                        </button>
                        <button onclick="openDeletePloModal({{ $plo->id }})" 
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1 font-medium text-xs border border-transparent hover:border-red-200 dark:hover:border-red-800/40" title="ลบ">
                            <i class="fa-regular fa-trash-can text-sm"></i> ลบ
                        </button>
                    </td>
                </tr>
                @endforeach
                
                @if($plos->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        ยังไม่มีรูปภาพ PLO
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- SECTION 1: BANNERS -->
<!-- ========================================== -->
<div class="admin-card">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">แบนเนอร์ทั้งหมด</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">รูปภาพเหล่านี้จะแสดงผลบริเวณด้านล่างของหน้าแรก</p>
        </div>
        <button onclick="openAddBannerModal()" class="bg-[#7e059c] hover:bg-[#680482] text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> เพิ่มรูปภาพใหม่
        </button>
    </div>

    <div class="admin-table-wrapper">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="admin-table-thead-tr">
                    <th class="admin-table-th" width="40"></th>
                    <th class="admin-table-th" width="150">รูปภาพ</th>
                    <th class="admin-table-th">ชื่อ/รายการอ้างอิง</th>
                    <th class="admin-table-th">สถานะ</th>
                    <th class="admin-table-th text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody id="sortable-banners" class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($banners as $banner)
                <tr class="admin-table-tr" data-id="{{ $banner->id }}">
                    <td class="admin-table-td text-center" style="vertical-align: middle; padding-left: 15px; padding-right: 15px;">
                        <i class="fa-solid fa-list text-[#7e059c] cursor-move hover:text-[#680482] transition-colors p-2 text-base" title="ลากเพื่อจัดเรียงลำดับ"></i>
                    </td>
                    <td class="admin-table-td">
                        <img src="{{ asset($banner->image_path) }}" alt="Banner" class="h-16 w-32 object-cover rounded shadow-sm border dark:border-gray-700">
                    </td>
                    <td class="admin-table-td">
                        <div class="font-medium text-gray-800 dark:text-gray-200">{{ $banner->title ?: '-' }}</div>
                        @if($banner->description)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-1">{{ $banner->description }}</div>
                        @endif
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" class="text-xs text-blue-500 hover:underline"><i class="fa-solid fa-link"></i> {{ $banner->link }}</a>
                        @else
                            <div class="text-xs text-gray-400">ไม่มีลิงก์</div>
                        @endif
                    </td>
                    <td class="admin-table-td">
                        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="toggle_status" value="1">
                            <button type="submit" class="badge-status {{ $banner->is_active ? 'badge-completed hover:bg-green-200 dark:hover:bg-green-800/60' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }} transition-colors">
                                <i class="fa-solid {{ $banner->is_active ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                {{ $banner->is_active ? 'แสดงผล' : 'ซ่อน' }}
                            </button>
                        </form>
                    </td>
                    <td class="admin-table-td text-right">
                        <button onclick="openEditBannerModal({{ json_encode($banner) }})" 
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 px-3 py-1.5 rounded-lg transition-colors mr-2 inline-flex items-center gap-1 font-medium text-xs border border-transparent hover:border-blue-200 dark:hover:border-blue-800/40" title="แก้ไข">
                            <i class="fa-regular fa-pen-to-square text-sm"></i> แก้ไข
                        </button>
                        <button onclick="openDeleteBannerModal({{ $banner->id }})" 
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1 font-medium text-xs border border-transparent hover:border-red-200 dark:hover:border-red-800/40" title="ลบ">
                            <i class="fa-regular fa-trash-can text-sm"></i> ลบ
                        </button>
                    </td>
                </tr>
                @endforeach
                
                @if($banners->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        ยังไม่มีรูปภาพแบนเนอร์
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- MODALS FOR BANNERS -->
<!-- ========================================== -->

<!-- Add Banner Modal -->
<div id="addBannerModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">เพิ่มรูปภาพหน้าแรก (Banner)</h3>
            <button onclick="closeBannerModals()" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ไฟล์รูปภาพ * <span class="text-xs text-gray-500 dark:text-gray-400">(แนะนำ: แนวนอน)</span></label>
                <div class="relative">
                    <input type="file" name="image" id="add_banner_image" accept="image/*" required class="hidden" onchange="handleAddBannerImageChange(this)">
                    <label for="add_banner_image" class="flex items-center justify-between w-full pl-4 pr-1 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors" style="height: 42px; box-sizing: border-box;">
                        <span id="add_banner_image_name" class="text-sm text-gray-500 dark:text-gray-400 truncate mr-4">ยังไม่ได้เลือกไฟล์...</span>
                        <span class="flex items-center justify-center bg-[#7e059c] hover:bg-[#680482] text-white text-xs font-semibold px-4 rounded-md transition-colors whitespace-nowrap" style="height: 32px; box-sizing: border-box;">
                            <i class="fa-solid fa-cloud-arrow-up mr-1"></i> เลือกรูปภาพ
                        </span>
                    </label>
                </div>
                <div id="add_banner_preview_container" style="display: none; margin-top: 12px;">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">ตัวอย่างรูปภาพที่เลือก:</label>
                    <img id="add_banner_preview_image" src="" alt="Preview" class="h-24 w-48 object-cover rounded border dark:border-gray-700 shadow-sm">
                </div>
            </div>
            
            <div class="mb-4">
                <label for="add_banner_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หัวข้อ/ชื่อรูป (ไม่บังคับ)</label>
                <input type="text" name="title" id="add_banner_title" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="เช่น กิจกรรมรับน้องใหม่">
            </div>

            <div class="mb-4">
                <label for="add_banner_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คำบรรยาย/รายละเอียดแบนเนอร์ (ไม่บังคับ)</label>
                <textarea name="description" id="add_banner_description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none" placeholder="รายละเอียดที่จะแสดงผลบรรทัดถัดมา..."></textarea>
            </div>

            <div class="mb-6">
                <label for="add_banner_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ลิงก์ปลายทางเมื่อคลิกรูป (ไม่บังคับ)</label>
                <input type="url" name="link" id="add_banner_link" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="https://...">
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeBannerModals()" class="px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg font-medium transition-colors shadow-sm">อัปโหลด</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Banner Modal -->
<div id="editBannerModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">แก้ไขรายละเอียดแบนเนอร์</h3>
            <button onclick="closeBannerModals()" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editBannerForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">รูปภาพปัจจุบัน</label>
                <img id="edit_banner_preview" src="" alt="Current Banner" class="h-24 w-48 object-cover rounded border dark:border-gray-700 mb-2 shadow-sm">
                <label for="edit_banner_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เปลี่ยนรูปภาพใหม่ (ไม่บังคับ) <span class="text-xs text-gray-500 dark:text-gray-400">(แนะนำ: แนวนอน)</span></label>
                <div class="relative">
                    <input type="file" name="image" id="edit_banner_image" accept="image/*" class="hidden" onchange="handleEditBannerImageChange(this)">
                    <label for="edit_banner_image" class="flex items-center justify-between w-full pl-4 pr-1 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors" style="height: 42px; box-sizing: border-box;">
                        <span id="edit_banner_image_name" class="text-sm text-gray-500 dark:text-gray-400 truncate mr-4">ยังไม่ได้เลือกไฟล์ใหม่...</span>
                        <span class="flex items-center justify-center bg-[#7e059c] hover:bg-[#680482] text-white text-xs font-semibold px-4 rounded-md transition-colors whitespace-nowrap" style="height: 32px; box-sizing: border-box;">
                            <i class="fa-solid fa-cloud-arrow-up mr-1"></i> เลือกรูปภาพ
                        </span>
                    </label>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="edit_banner_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หัวข้อ/ชื่อรูป (ไม่บังคับ)</label>
                <input type="text" name="title" id="edit_banner_title" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="เช่น กิจกรรมรับน้องใหม่">
            </div>

            <div class="mb-4">
                <label for="edit_banner_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คำบรรยาย/รายละเอียดแบนเนอร์ (ไม่บังคับ)</label>
                <textarea name="description" id="edit_banner_description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none" placeholder="รายละเอียดที่จะแสดงผลบรรทัดถัดมา..."></textarea>
            </div>

            <div class="mb-4">
                <label for="edit_banner_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ลิงก์ปลายทางเมื่อคลิกรูป (ไม่บังคับ)</label>
                <input type="url" name="link" id="edit_banner_link" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="https://...">
            </div>

            <div class="mb-6">
                <label for="edit_banner_is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">สถานะการแสดงผล</label>
                <select name="is_active" id="edit_banner_is_active" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="1">แสดงผล</option>
                    <option value="0">ซ่อน</option>
                </select>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeBannerModals()" class="px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg font-medium transition-colors shadow-sm">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Banner Modal -->
<div id="deleteBannerModal" class="modal-overlay hidden">
    <div class="modal-content !max-w-sm p-6 text-center">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">ยืนยันการลบรูปภาพ?</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6 font-medium text-sm">รูปภาพจะถูกลบออกจากระบบอย่างถาวร<br>การกระทำนี้ไม่สามารถย้อนกลับได้</p>
        
        <form id="deleteBannerForm" method="POST" class="flex justify-center space-x-3">
            @csrf
            <button type="button" onclick="closeBannerModals()" class="w-full px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
            <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors shadow-sm">ยืนยันลบ</button>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODALS FOR PLOS -->
<!-- ========================================== -->

<!-- Add PLO Modal -->
<div id="addPloModal" class="modal-overlay hidden" style="align-items: flex-start; padding-top: 3rem; padding-bottom: 3rem; overflow-y: auto;">
    <div class="modal-content" style="max-height: none; overflow: hidden; margin: auto;">
        <div class="modal-header">
            <h3 class="text-lg font-bold">เพิ่มรูปภาพ PLO ใหม่</h3>
            <button onclick="closePloModals()" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('admin.plos.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label for="add_plo_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รูปภาพ PLO (แนวตั้ง) *</label>
                <div class="relative">
                    <input type="file" name="image" id="add_plo_image" accept="image/*" required class="hidden" onchange="handleAddPloImageChange(this)">
                    <label for="add_plo_image" class="flex items-center justify-between w-full pl-4 pr-1 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors" style="height: 42px; box-sizing: border-box;">
                        <span id="add_plo_image_name" class="text-sm text-gray-500 dark:text-gray-400 truncate mr-4">ยังไม่ได้เลือกไฟล์...</span>
                        <span class="flex items-center justify-center bg-[#7e059c] hover:bg-[#680482] text-white text-xs font-semibold px-4 rounded-md transition-colors whitespace-nowrap" style="height: 32px; box-sizing: border-box;">
                            <i class="fa-solid fa-cloud-arrow-up mr-1"></i> เลือกรูปภาพ
                        </span>
                    </label>
                </div>
                <div id="add_plo_preview_container" style="display: none; margin-top: 12px;">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">ตัวอย่างรูปภาพที่เลือก:</label>
                    <img id="add_plo_preview_image" src="" alt="Preview" class="h-32 w-24 object-cover rounded border dark:border-gray-700 shadow-sm">
                </div>
            </div>

            <div class="mb-4">
                <label for="add_plo_degree_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ระดับปริญญา *</label>
                <select name="degree_level" id="add_plo_degree_level" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    <option value="bachelor">ปริญญาตรี (Bachelor)</option>
                    <option value="master">ปริญญาโท (Master)</option>
                    <option value="doctoral">ปริญญาเอก (Doctoral)</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="add_plo_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หัวข้อ (เช่น PLO สาขาธุรกิจดิจิทัลฯ)</label>
                <input type="text" name="title" id="add_plo_title" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
            </div>

            <div class="mb-4">
                <label for="add_plo_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คำบรรยาย (ไม่บังคับ)</label>
                <textarea name="description" id="add_plo_description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"></textarea>
            </div>

            <div class="mb-6">
                <label for="add_plo_icon_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คลาสไอคอน (ไม่บังคับ จะแสดงเมื่อไม่มีรูปภาพ)</label>
                <input type="text" name="icon_class" id="add_plo_icon_class" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="fa-solid fa-bullhorn">
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closePloModals()" class="px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg font-medium transition-colors shadow-sm">บันทึก</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit PLO Modal -->
<div id="editPloModal" class="modal-overlay hidden" style="align-items: flex-start; padding-top: 3rem; padding-bottom: 3rem; overflow-y: auto;">
    <div class="modal-content" style="max-height: none; overflow: hidden; margin: auto;">
        <div class="modal-header">
            <h3 class="text-lg font-bold">แก้ไขรายละเอียด PLO</h3>
            <button onclick="closePloModals()" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editPloForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">รูปภาพปัจจุบัน</label>
                <img id="edit_plo_preview" src="" alt="Current PLO" class="h-32 w-24 object-cover rounded border dark:border-gray-700 mb-2 shadow-sm" style="display:none;">
                <label for="edit_plo_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 mt-2">เปลี่ยนรูปภาพใหม่ (ไม่บังคับ)</label>
                <div class="relative">
                    <input type="file" name="image" id="edit_plo_image" accept="image/*" class="hidden" onchange="handleEditPloImageChange(this)">
                    <label for="edit_plo_image" class="flex items-center justify-between w-full pl-4 pr-1 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors" style="height: 42px; box-sizing: border-box;">
                        <span id="edit_plo_image_name" class="text-sm text-gray-500 dark:text-gray-400 truncate mr-4">ยังไม่ได้เลือกไฟล์ใหม่...</span>
                        <span class="flex items-center justify-center bg-[#7e059c] hover:bg-[#680482] text-white text-xs font-semibold px-4 rounded-md transition-colors whitespace-nowrap" style="height: 32px; box-sizing: border-box;">
                            <i class="fa-solid fa-cloud-arrow-up mr-1"></i> เลือกรูปภาพ
                        </span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label for="edit_plo_degree_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ระดับปริญญา *</label>
                <select name="degree_level" id="edit_plo_degree_level" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    <option value="bachelor">ปริญญาตรี (Bachelor)</option>
                    <option value="master">ปริญญาโท (Master)</option>
                    <option value="doctoral">ปริญญาเอก (Doctoral)</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="edit_plo_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หัวข้อ</label>
                <input type="text" name="title" id="edit_plo_title" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
            </div>

            <div class="mb-4">
                <label for="edit_plo_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คำบรรยาย</label>
                <textarea name="description" id="edit_plo_description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"></textarea>
            </div>

            <div class="mb-4">
                <label for="edit_plo_icon_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">คลาสของไอคอน (ไม่บังคับ)</label>
                <input type="text" name="icon_class" id="edit_plo_icon_class" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="mb-6">
                <label for="edit_plo_is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">สถานะการแสดงผล</label>
                <select name="is_active" id="edit_plo_is_active" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="1">แสดงผล</option>
                    <option value="0">ซ่อน</option>
                </select>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closePloModals()" class="px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 text-white bg-[#7e059c] hover:bg-[#680482] rounded-lg font-medium transition-colors shadow-sm">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete PLO Modal -->
<div id="deletePloModal" class="modal-overlay hidden" style="align-items: flex-start; padding-top: 3rem; padding-bottom: 3rem; overflow-y: auto;">
    <div class="modal-content !max-w-sm p-6 text-center" style="max-height: none; overflow: hidden; margin: auto;">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">ยืนยันการลบรูปภาพ PLO?</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6 font-medium text-sm">รูปภาพจะถูกลบออกจากระบบอย่างถาวร<br>การกระทำนี้ไม่สามารถย้อนกลับได้</p>
        
        <form id="deletePloForm" method="POST" class="flex justify-center space-x-3">
            @csrf
            <button type="button" onclick="closePloModals()" class="w-full px-4 py-2 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium transition-colors">ยกเลิก</button>
            <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors shadow-sm">ยืนยันลบ</button>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPTS -->
<!-- ========================================== -->
<script>
    // --- Banner Scripts ---
    function openAddBannerModal() {
        document.getElementById('addBannerModal').classList.remove('hidden');
    }

    function openEditBannerModal(banner) {
        let form = document.getElementById('editBannerForm');
        form.action = "{{ route('admin.banners.update', 'REPLACE_ID') }}".replace('REPLACE_ID', banner.id);
        
        document.getElementById('edit_banner_preview').src = "{{ asset('') }}" + banner.image_path;
        document.getElementById('edit_banner_title').value = banner.title || '';
        document.getElementById('edit_banner_description').value = banner.description || '';
        document.getElementById('edit_banner_link').value = banner.link || '';
        document.getElementById('edit_banner_is_active').value = banner.is_active ? "1" : "0";
        
        document.getElementById('editBannerModal').classList.remove('hidden');
    }

    function openDeleteBannerModal(id) {
        let form = document.getElementById('deleteBannerForm');
        form.action = "{{ route('admin.banners.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        document.getElementById('deleteBannerModal').classList.remove('hidden');
    }

    function closeBannerModals() {
        document.getElementById('addBannerModal').classList.add('hidden');
        document.getElementById('editBannerModal').classList.add('hidden');
        document.getElementById('deleteBannerModal').classList.add('hidden');
        
        // Reset file names text on close
        document.getElementById('add_banner_image').value = '';
        document.getElementById('add_banner_image_name').textContent = 'ยังไม่ได้เลือกไฟล์...';
        document.getElementById('edit_banner_image').value = '';
        document.getElementById('edit_banner_image_name').textContent = 'ยังไม่ได้เลือกไฟล์ใหม่...';

        // Clear preview image on close
        document.getElementById('add_banner_preview_container').style.display = 'none';
        document.getElementById('add_banner_preview_image').src = '';
    }

    function handleAddBannerImageChange(input) {
        const fileNameSpan = document.getElementById('add_banner_image_name');
        const previewContainer = document.getElementById('add_banner_preview_container');
        const previewImage = document.getElementById('add_banner_preview_image');
        
        if (input.files && input.files[0]) {
            fileNameSpan.textContent = input.files[0].name;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            fileNameSpan.textContent = 'ยังไม่ได้เลือกไฟล์...';
            previewContainer.style.display = 'none';
            previewImage.src = '';
        }
    }

    function handleEditBannerImageChange(input) {
        const fileNameSpan = document.getElementById('edit_banner_image_name');
        const previewImage = document.getElementById('edit_banner_preview');
        
        if (input.files && input.files[0]) {
            fileNameSpan.textContent = input.files[0].name;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            fileNameSpan.textContent = 'ยังไม่ได้เลือกไฟล์ใหม่...';
        }
    }

    // --- PLO Scripts ---
    function openAddPloModal() {
        document.getElementById('addPloModal').classList.remove('hidden');
    }

    function openEditPloModal(plo) {
        let form = document.getElementById('editPloForm');
        form.action = "{{ route('admin.plos.update', 'REPLACE_ID') }}".replace('REPLACE_ID', plo.id);
        
        if (plo.image_path) {
            document.getElementById('edit_plo_preview').src = "{{ asset('') }}" + plo.image_path;
            document.getElementById('edit_plo_preview').style.display = 'block';
        } else {
            document.getElementById('edit_plo_preview').style.display = 'none';
        }
        
        document.getElementById('edit_plo_title').value = plo.title || '';
        document.getElementById('edit_plo_degree_level').value = plo.degree_level || 'bachelor';
        document.getElementById('edit_plo_description').value = plo.description || '';
        document.getElementById('edit_plo_icon_class').value = plo.icon_class || '';
        document.getElementById('edit_plo_is_active').value = plo.is_active ? "1" : "0";
        
        document.getElementById('editPloModal').classList.remove('hidden');
    }

    function openDeletePloModal(id) {
        let form = document.getElementById('deletePloForm');
        form.action = "{{ route('admin.plos.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        document.getElementById('deletePloModal').classList.remove('hidden');
    }

    function closePloModals() {
        document.getElementById('addPloModal').classList.add('hidden');
        document.getElementById('editPloModal').classList.add('hidden');
        document.getElementById('deletePloModal').classList.add('hidden');
        
        document.getElementById('add_plo_image').value = '';
        document.getElementById('add_plo_image_name').textContent = 'ยังไม่ได้เลือกไฟล์...';
        document.getElementById('edit_plo_image').value = '';
        document.getElementById('edit_plo_image_name').textContent = 'ยังไม่ได้เลือกไฟล์ใหม่...';
        document.getElementById('add_plo_preview_container').style.display = 'none';
        document.getElementById('add_plo_preview_image').src = '';
    }

    function handleAddPloImageChange(input) {
        const fileNameSpan = document.getElementById('add_plo_image_name');
        const previewContainer = document.getElementById('add_plo_preview_container');
        const previewImage = document.getElementById('add_plo_preview_image');
        
        if (input.files && input.files[0]) {
            fileNameSpan.textContent = input.files[0].name;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            fileNameSpan.textContent = 'ยังไม่ได้เลือกไฟล์...';
            previewContainer.style.display = 'none';
            previewImage.src = '';
        }
    }

    function handleEditPloImageChange(input) {
        const fileNameSpan = document.getElementById('edit_plo_image_name');
        const previewImage = document.getElementById('edit_plo_preview');
        
        if (input.files && input.files[0]) {
            fileNameSpan.textContent = input.files[0].name;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            fileNameSpan.textContent = 'ยังไม่ได้เลือกไฟล์ใหม่...';
        }
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeBannerModals();
            closePloModals();
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Banner Sortable
        const elBanners = document.getElementById('sortable-banners');
        if (elBanners) {
            new Sortable(elBanners, {
                handle: '.cursor-move',
                animation: 150,
                onEnd: function (evt) {
                    const rows = elBanners.querySelectorAll('tr[data-id]');
                    const order = Array.from(rows).map(row => row.getAttribute('data-id'));
                    
                    fetch("{{ route('admin.banners.reorder') }}", {
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
                            showToastSuccess('จัดเรียงลำดับแบนเนอร์เรียบร้อยแล้ว');
                        }
                    })
                    .catch(err => console.error('Reorder error:', err));
                }
            });
        }

        // PLO Sortable
        const elPols = document.getElementById('sortable-plos');
        if (elPols) {
            new Sortable(elPols, {
                handle: '.cursor-move',
                animation: 150,
                onEnd: function (evt) {
                    const rows = elPols.querySelectorAll('tr[data-id]');
                    const order = Array.from(rows).map(row => row.getAttribute('data-id'));
                    
                    fetch("{{ route('admin.plos.reorder') }}", {
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
                            showToastSuccess('จัดเรียงลำดับ PLO เรียบร้อยแล้ว');
                        }
                    })
                    .catch(err => console.error('Reorder error:', err));
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
