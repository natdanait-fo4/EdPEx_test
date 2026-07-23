@extends('layouts.app')

@section('title', 'แก้ไขโปรไฟล์ - NSRU-MS DETA SHIP')

@push('head')
<style>
    .profile-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .profile-header {
        height: 180px;
        background: linear-gradient(135deg, #7e059c 0%, #d53369 100%);
        position: relative;
    }
    .profile-title {
        position: absolute;
        top: 25px;
        left: 30px;
        color: #fff;
        font-size: 26px;
        font-weight: bold;
    }
    .profile-avatar-wrapper {
        position: relative;
        width: 140px;
        height: 140px;
        margin: -70px auto 30px auto;
        background: white;
        border-radius: 50%;
        padding: 5px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .profile-avatar i {
        font-size: 60px;
        color: #ccc;
    }
    .profile-form {
        padding: 0 40px 40px 40px;
    }
    .form-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
    }
    .form-section {
        flex: 1;
        min-width: 320px;
        background: #fafafa;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #eaeaea;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #444;
        font-size: 15px;
    }
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-family: inherit;
        font-size: 15px;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .form-control:focus {
        border-color: #7e059c;
        outline: none;
        box-shadow: 0 0 0 3px rgba(126,5,156,0.15);
    }
    .form-control:disabled {
        background: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }
    .section-title {
        font-size: 18px;
        font-weight: bold;
        color: #7e059c;
        margin-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
    .btn-submit {
        background: #7e059c;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
        font-family: inherit;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit:hover {
        background: #5d0373;
        transform: translateY(-2px);
    }
    .btn-cancel {
        background: #fff;
        color: #555;
        border: 1px solid #ccc;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-family: inherit;
        font-weight: 600;
    }
    .btn-cancel:hover {
        background: #f0f0f0;
    }
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid #eee;
    }
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-size: 15px;
    }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .alert ul { margin: 0; padding-left: 20px; }
    /* Dark Mode overrides */
    html.dark .profile-container {
        background-color: var(--card-bg);
    }
    html.dark .profile-avatar-wrapper {
        background-color: var(--card-bg);
    }
    html.dark .profile-avatar {
        background-color: #4a5568;
    }
    html.dark .profile-avatar i {
        color: #a0aec0;
    }
    html.dark .form-section {
        background-color: #374151 !important;
        border-color: var(--border-color) !important;
    }
    html.dark .form-section .section-title {
        color: #d8b4e2 !important;
        border-bottom-color: var(--border-color) !important;
    }
    html.dark .form-group label {
        color: var(--text-main);
    }
    html.dark .btn-cancel {
        background-color: var(--card-bg);
        color: var(--text-main);
        border-color: var(--border-color);
    }
    html.dark .btn-cancel:hover {
        background-color: #4a5568;
    }
    
    @media (max-width: 600px) {
        .edit-profile-wrapper {
            padding: 10px !important;
        }
        .profile-container {
            margin: 15px auto;
        }
        .profile-form {
            padding: 0 15px 25px 15px;
        }
        .form-section {
            min-width: 100%;
            padding: 15px;
        }
        .profile-header {
            height: 120px;
        }
        .profile-avatar-wrapper {
            width: 110px;
            height: 110px;
            margin: -55px auto 20px auto;
        }
        .profile-avatar i {
            font-size: 50px;
        }
        .profile-title {
            font-size: 20px;
            top: 20px;
            left: 20px;
        }
    }
</style>
@endpush

@section('content')
@php
    // ชุดสี Gradient สวยงาม 8 แบบ
    $gradients = [
        'linear-gradient(135deg, #7e059c 0%, #d53369 100%)', // ม่วง-ชมพู (ต้นฉบับ)
        'linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)', // น้ำเงิน-ม่วง (Indigo-Violet)
        'linear-gradient(135deg, #f43f5e 0%, #f97316 100%)', // กุหลาบ-ส้ม (Rose-Orange)
        'linear-gradient(135deg, #10b981 0%, #06b6d4 100%)', // เขียวมรกต-ฟ้า (Emerald-Cyan)
        'linear-gradient(135deg, #0284c7 0%, #3b82f6 100%)', // ท้องฟ้า-น้ำเงิน (Sky-Blue)
        'linear-gradient(135deg, #ea580c 0%, #e11d48 100%)', // ส้ม-แดงสว่าง (Orange-Rose)
        'linear-gradient(135deg, #2563eb 0%, #7c3aed 100%)', // น้ำเงินเข้ม-ม่วง (Blue-Violet)
        'linear-gradient(135deg, #0d9488 0%, #0284c7 100%)', // เขียวหัวเป็ด-ฟ้า (Teal-Sky)
    ];

    // คำนวณหาลำดับจาก ID ผู้ใช้ (ถ้าไม่ได้ล็อกอิน จะให้ใช้สีลำดับ 0 แทน)
    $userIndex = auth()->check() ? (auth()->id() % count($gradients)) : 0;
    $selectedGradient = $gradients[$userIndex];
@endphp
<div class="container edit-profile-wrapper" style="padding: 20px;">
    <div class="profile-container">
        <!-- Header / Cover -->
        <div class="profile-header" style="background: {{ $selectedGradient }};">
            <div class="profile-title"><i class="fa-solid fa-user-pen"></i> แก้ไขโปรไฟล์ส่วนตัว</div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
            @csrf

            <!-- Avatar Section -->
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar">
                    <i class="fa-solid fa-user" style="font-size: 60px; color: #ccc;"></i>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-grid">
                <!-- ข้อมูลที่แก้ไขไม่ได้ -->
                <div class="form-section">
                    <div class="section-title"><i class="fa-solid fa-lock"></i> ข้อมูลบัญชี <span class="whitespace-nowrap">(ไม่สามารถแก้ไขได้)</span></div>
                    
                    <div class="form-group">
                        <label>อีเมลนักศึกษา</label>
                        <input type="text" value="{{ $user->email }}" disabled class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>รหัสนักศึกษา</label>
                        <input type="text" value="{{ $user->student_id }}" disabled class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>สาขาวิชา</label>
                        <input type="text" value="{{ $user->major }}" disabled class="form-control">
                    </div>
                </div>

                <!-- ข้อมูลที่แก้ไขได้ -->
                <div class="form-section">
                    <div class="section-title"><i class="fa-solid fa-pen-to-square"></i> ข้อมูลที่สามารถแก้ไขได้</div>
                    
                    <div class="form-group">
                        <label for="fullname">ชื่อ-นามสกุล <span style="color: red;">*</span></label>
                        <input type="text" name="fullname" id="fullname" value="{{ old('fullname', $user->fullname) }}" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="phone">เบอร์โทรศัพท์ติดต่อ</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="address">ที่อยู่ปัจจุบัน</label>
                        <textarea name="address" id="address" rows="3" class="form-control">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('home.index') }}" class="btn-cancel">ยกเลิก</a>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-save"></i> บันทึก
                </button>
            </div>
        </form>
    </div>
</div>


@endsection
