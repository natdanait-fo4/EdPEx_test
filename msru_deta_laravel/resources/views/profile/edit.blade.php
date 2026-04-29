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
        position: relative;
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-avatar i {
        font-size: 60px;
        color: #ccc;
    }
    .avatar-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: 0.3s;
        cursor: pointer;
        font-size: 14px;
    }
    .profile-avatar-wrapper:hover .avatar-overlay {
        opacity: 1;
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
</style>
@endpush

@section('content')
<div class="container" style="padding: 20px;">
    <div class="profile-container">
        <!-- Header / Cover -->
        <div class="profile-header">
            <div class="profile-title"><i class="fa-solid fa-user-pen"></i> แก้ไขโปรไฟล์ส่วนตัว</div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
            @csrf

            <!-- Avatar Section -->
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" id="avatarPreview">
                    @else
                        <i class="fa-solid fa-user" id="avatarIcon"></i>
                        <img src="" alt="Avatar" id="avatarPreview" style="display: none;">
                    @endif
                    
                    <!-- Overlay Upload -->
                    <label for="avatar" class="avatar-overlay">
                        <i class="fa-solid fa-camera" style="font-size: 24px; color: white; margin-bottom: 5px;"></i>
                        <span>เปลี่ยนรูป</span>
                    </label>
                </div>
                <input type="file" name="avatar" id="avatar" style="display: none;" accept="image/*" onchange="previewImage(this)">
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
                    <div class="section-title"><i class="fa-solid fa-lock"></i> ข้อมูลบัญชี (ไม่สามารถแก้ไขได้)</div>
                    
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
                <div class="form-section" style="background: #fff; border-color: #ddd;">
                    <div class="section-title" style="color: #333;"><i class="fa-solid fa-pen-to-square"></i> ข้อมูลที่สามารถแก้ไขได้</div>
                    
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
                <a href="{{ url('/') }}" class="btn-cancel">ยกเลิก</a>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-save"></i> บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var preview = document.getElementById('avatarPreview');
                var icon = document.getElementById('avatarIcon');
                
                preview.src = e.target.result;
                preview.style.display = 'block';
                if(icon) icon.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
