<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิกใหม่ | NSRU-MS DETA SHIP</title>
    <!-- Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Prompt Font & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 py-12">

    <!-- Register Card -->
    <div
        class="bg-white rounded-2xl shadow-xl w-full max-w-[440px] p-8 md:p-10 border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-bl-full -z-10"></div>
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">NSRU-MS DETA SHIP</h1>
            <p class="text-[13px] text-gray-500 mt-2">ระบบจัดเก็บข้อมูลเพื่อความต้องการอันเป็นเลิศสู่ความสำเร็จ</p>
            <div
                class="mt-4 inline-block px-4 py-1 bg-purple-100 text-[#7e059c] text-[11px] font-bold rounded-full uppercase tracking-wider">
                สมัครสมาชิกใหม่
            </div>
        </div>

        <!-- Register Form -->
        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
            @csrf

            @if($errors->any())
                <div class="mb-4 bg-red-50 text-red-600 text-[13px] p-3 rounded-lg border border-red-100 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <!-- Student Email (Acts as Username) -->
            <div>
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">อีเมลนักศึกษา (@nsru.ac.th) <span
                        class="text-red-500">*</span></label>
                <div class="flex space-x-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                            placeholder="example.t@nsru.ac.th">
                    </div>
                    <button type="button" id="send_otp_btn" onclick="sendOTP()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-[#7e059c] font-semibold rounded-xl transition-all text-[12px] border border-gray-200 whitespace-nowrap min-w-[100px]">
                        ส่งรหัส OTP
                    </button>
                </div>
                <p id="otp_timer" class="text-[11px] text-gray-500 mt-1 hidden">ส่งอีกครั้งได้ใน <span
                        id="timer_count">60</span> วินาที</p>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">รหัสผ่าน <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="w-full pl-11 pr-12 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                        placeholder="Password">
                    <button type="button" onclick="togglePassword('password', 'eye_icon_1')"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#7e059c] transition-colors">
                        <i id="eye_icon_1" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">ยืนยันรหัสผ่าน <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-shield-check text-gray-400"></i>
                    </div>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full pl-11 pr-12 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                        placeholder="Confirm Password">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye_icon_2')"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#7e059c] transition-colors">
                        <i id="eye_icon_2" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Fullname -->
            <div>
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">ชื่อ-นามสกุล <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-regular fa-id-card text-gray-400"></i>
                    </div>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" required
                        class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                        placeholder="ชื่อจริง - นามสกุล">
                </div>
            </div>

            <!-- Student ID -->
            <div>
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">รหัสนักศึกษา <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-graduation-cap text-gray-400"></i>
                    </div>
                    <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" required
                        minlength="11" maxlength="11" oninput="autoSelectMajor(this.value)"
                        class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                        placeholder="รหัสนักศึกษาเต็ม">
                </div>
            </div>

            <!-- Major -->
            <div>
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">สาขาวิชา <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-book text-gray-400"></i>
                    </div>
                    <select name="major" id="major_select" required
                        onchange="this.classList.remove('text-gray-400'); this.classList.add('text-gray-700')"
                        class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-400 text-[14px] bg-white cursor-pointer">
                        <option value="" disabled {{ old('major') ? '' : 'selected' }}>เลือกสาขาวิชา...</option>
                        <option value="สาขาวิชาการบัญชี" {{ old('major') == 'สาขาวิชาการบัญชี' ? 'selected' : '' }}
                            class="text-gray-700">สาขาวิชาการบัญชี</option>
                        <option value="สาขาวิชาการบัญชี (เทียบโอน)" {{ old('major') == 'สาขาวิชาการบัญชี (เทียบโอน)' ? 'selected' : '' }} class="text-gray-700">สาขาวิชาการบัญชี (เทียบโอน)</option>
                        <option value="สาขาวิชาการตลาดดิจิทัล" {{ old('major') == 'สาขาวิชาการตลาดดิจิทัล' ? 'selected' : '' }} class="text-gray-700">สาขาวิชาการตลาดดิจิทัล</option>
                        <option value="สาขาวิชาการตลาดดิจิทัล (เทียบโอน)" {{ old('major') == 'สาขาวิชาการตลาดดิจิทัล (เทียบโอน)' ? 'selected' : '' }} class="text-gray-700">สาขาวิชาการตลาดดิจิทัล (เทียบโอน)
                        </option>
                        <option value="สาขาวิชานิเทศศาสตร์" {{ old('major') == 'สาขาวิชานิเทศศาสตร์' ? 'selected' : '' }}
                            class="text-gray-700">สาขาวิชานิเทศศาสตร์</option>
                        <option value="สาขาวิชาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่" {{ old('major') == 'สาขาวิชาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่' ? 'selected' : '' }}
                            class="text-gray-700">สาขาวิชาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่</option>
                        <option value="สาขาวิชาคอมพิวเตอร์ธุรกิจ" {{ old('major') == 'สาขาวิชาคอมพิวเตอร์ธุรกิจ' ? 'selected' : '' }} class="text-gray-700">สาขาวิชาคอมพิวเตอร์ธุรกิจ</option>
                        <option value="สาขาวิชาคอมพิวเตอร์ธุรกิจ (เทียบโอน)" {{ old('major') == 'สาขาวิชาคอมพิวเตอร์ธุรกิจ (เทียบโอน)' ? 'selected' : '' }} class="text-gray-700">สาขาวิชาคอมพิวเตอร์ธุรกิจ (เทียบโอน)
                        </option>
                        <option value="สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี" {{ old('major') == 'สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี' ? 'selected' : '' }}
                            class="text-gray-700">สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี</option>
                        <option value="สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี (เทียบโอน)" {{ old('major') == 'สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี (เทียบโอน)' ? 'selected' : '' }}
                            class="text-gray-700">สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี (เทียบโอน)</option>
                        <option value="สาขาวิชาการท่องเที่ยวและการโรงแรม" {{ old('major') == 'สาขาวิชาการท่องเที่ยวและการโรงแรม' ? 'selected' : '' }}
                            class="text-gray-700">สาขาวิชาการท่องเที่ยวและการโรงแรม</option>
                        <option value="สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ" {{ old('major') == 'สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ' ? 'selected' : '' }}
                            class="text-gray-700">สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ</option>
                        <option value="สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ (เทียบโอน)" {{ old('major') == 'สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ (เทียบโอน)' ? 'selected' : '' }} class="text-gray-700">สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ (เทียบโอน)</option>
                    </select>
                </div>
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">เบอร์โทรศัพท์</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-phone text-gray-400"></i>
                    </div>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px]"
                        placeholder="08x-xxx-xxxx">
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">ที่อยู่ตามบัตรประชาชน</label>
                <textarea name="address" rows="2"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-gray-700 text-[14px] resize-none"
                    placeholder="บ้านเลขที่, ถนน, ตำบล/แขวง...">{{ old('address') }}</textarea>
            </div>

            <div class="border-t border-dashed border-gray-200 my-6 pt-6"></div>

            <!-- OTP Code -->
            <div id="otp_section" class="hidden animate-fade-in">
                <label class="block text-[14px] font-medium text-gray-700 mb-1.5">รหัสยืนยัน OTP (6 หลัก) <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-shield-check text-purple-400"></i>
                    </div>
                    <input type="text" name="otp_code" id="otp_code" maxlength="6"
                        class="w-full pl-11 pr-4 py-2.5 border-2 border-purple-100 rounded-xl focus:ring-2 focus:ring-[#7e059c] focus:border-[#7e059c] outline-none transition-all text-center tracking-[0.5em] font-bold text-gray-800 text-[18px]"
                        placeholder="000000">
                </div>
                <p class="text-[11px] text-purple-600 mt-1"><i class="fa-solid fa-circle-info mr-1"></i>
                    รหัสถูกส่งไปที่อีเมลของคุณแล้ว</p>
            </div>

            <!-- Checkbox -->
            <div class="flex items-center pt-1">
                <input id="terms" type="checkbox" required
                    class="w-4 h-4 text-[#7e059c] border-gray-300 rounded focus:ring-[#7e059c]">
                <label for="terms" class="ml-2 text-[12px] text-gray-500">
                    ข้าพเจ้ายอมรับ <a href="javascript:void(0)" onclick="openModal()" class="text-[#7e059c] hover:underline">ข้อตกลงและเงื่อนไข</a>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-[#7e059c] hover:bg-[#680482] text-white font-medium py-3 rounded-xl transition-all shadow-md shadow-purple-100 mb-3 text-[15px]">
                    <i class="fa-solid fa-user-plus mr-2"></i> ลงทะเบียนสมาชิก
                </button>
                <a href="{{ route('login') }}"
                    class="w-full bg-white hover:bg-gray-50 text-gray-600 font-medium py-3 border border-gray-300 rounded-xl transition-all flex justify-center items-center text-[14px]">
                    <i class="fa-solid fa-xmark mr-2 text-gray-400"></i> ยกเลิก
                </a>
            </div>
        </form>

    </div>

    <!-- Terms and Conditions Modal -->
    <div id="termsModal" onclick="closeModal(event)">
        <div class="modal-content animate-fade-in" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="text-lg font-bold flex items-center">
                    <i class="fa-solid fa-shield-halved mr-2"></i> ข้อตกลงและเงื่อนไขการใช้งาน
                </h3>
                <button onclick="closeModal()" class="text-white hover:opacity-70 transition-opacity">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="space-y-4 text-justify">
                    <section>
                        <h4 class="font-bold text-gray-800 mb-1">1. การเก็บรวบรวมข้อมูลส่วนบุคคล (PDPA)</h4>
                        <p>ข้าพเจ้ายินยอมให้โครงการ <strong>NSRU-MS DETA SHIP</strong> และคณะวิทยาการจัดการ มหาวิทยาลัยราชภัฏนครสวรรค์ เก็บรวบรวมและใช้ข้อมูลส่วนบุคคล เช่น ชื่อ-นามสกุล, รหัสนักศึกษา, เบอร์โทรศัพท์ และที่อยู่ เพื่อวัตถุประสงค์ในการสมัครสมาชิก การติดต่อประสานงาน และการดำเนินกิจกรรมภายในโครงการเท่านั้น</p>
                    </section>
                    <section>
                        <h4 class="font-bold text-gray-800 mb-1">2. ความปลอดภัยของข้อมูล</h4>
                        <p>โครงการฯ จะรักษาความปลอดภัยของข้อมูลท่านเป็นอย่างดี และจะไม่นำข้อมูลส่วนบุคคลของท่านไปเปิดเผยต่อบุคคลภายนอก หรือใช้ในวัตถุประสงค์อื่นที่นอกเหนือจากที่ระบุไว้ เว้นแต่จะได้รับความยินยอมจากท่านหรือเป็นไปตามที่กฎหมายกำหนด</p>
                    </section>
                    <section>
                        <h4 class="font-bold text-gray-800 mb-1">3. ความถูกต้องของข้อมูล</h4>
                        <p>ผู้สมัครสมาชิกขอรับรองว่าข้อมูลที่กรอกในระบบเป็นข้อมูลที่ถูกต้องและเป็นความจริงทุกประการ หากตรวจพบว่าข้อมูลเป็นเท็จ ทางโครงการฯ ขอสงวนสิทธิ์ในการยกเลิกสิทธิ์การเข้าร่วมกิจกรรมทันที</p>
                    </section>
                    <section>
                        <h4 class="font-bold text-gray-800 mb-1">4. การยกเลิกและแก้ไขข้อมูล</h4>
                        <p>ท่านสามารถร้องขอเพื่อตรวจสอบ แก้ไข หรือลบข้อมูลส่วนบุคคลของท่านออกจากระบบได้ โดยติดต่อผ่านเจ้าหน้าที่ผู้ดูแลระบบโครงการ NSRU-MS DETA SHIP</p>
                    </section>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal()" class="px-6 py-2.5 bg-[#7e059c] hover:bg-[#680482] text-white rounded-xl font-medium transition-all shadow-md">
                    รับทราบและยอมรับ
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        /* Modal Styles */
        #termsModal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
        }
        #termsModal.show {
            display: flex;
            animation: fadeIn 0.3s ease-out forwards;
        }
        .modal-content {
            background: white;
            width: 100%;
            max-width: 550px;
            max-height: 85vh;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
        }
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to right, #7e059c, #9d09c1);
            color: white;
        }
        .modal-body {
            padding: 1.5rem;
            overflow-y: auto;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
        }
        .modal-footer {
            padding: 1.25rem;
            border-top: 1px solid #f3f4f6;
            background: #f9fafb;
            text-align: right;
        }
    </style>

    <script>
        // ฟังก์ชันเปิด-ปิดการมองเห็นรหัสผ่าน
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // ฟังก์ชัน Modal ข้อตกลง
        function openModal() {
            document.getElementById('termsModal').classList.add('show');
            document.body.style.overflow = 'hidden'; // กันเลื่อนหน้าจอหลัง
        }

        function closeModal(event) {
            document.getElementById('termsModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // ฟังก์ชันเลือกสาขาอัตโนมัติตามรหัสสาขา
        function autoSelectMajor(studentId) {
            if (studentId.length >= 7) {
                // รหัสสาขาหลัก (ตำแหน่งที่ 5, 6, 7)
                const code3 = studentId.substring(4, 7);
                // รหัสสาขาแบบละเอียด (ตำแหน่งที่ 5, 6, 7, 8) สำหรับบางกรณี
                const code4 = studentId.length >= 8 ? studentId.substring(4, 8) : "";

                const majorSelect = document.getElementById('major_select');
                let selectedMajor = "";

                // Mapping ภาคปกติ และ ภาคเทียบโอน
                switch (code3) {
                    case "474": selectedMajor = "สาขาวิชาการบัญชี"; break;
                    case "475": selectedMajor = "สาขาวิชาการบัญชี (เทียบโอน)"; break;

                    case "487": case "465": selectedMajor = "สาขาวิชาการตลาดดิจิทัล"; break;
                    case "468": selectedMajor = "สาขาวิชาการตลาดดิจิทัล (เทียบโอน)"; break;

                    case "481": selectedMajor = "สาขาวิชานิเทศศาสตร์"; break;
                    case "482": selectedMajor = "สาขาวิชาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่"; break;

                    case "467": selectedMajor = "สาขาวิชาคอมพิวเตอร์ธุรกิจ"; break;
                    case "470": selectedMajor = "สาขาวิชาคอมพิวเตอร์ธุรกิจ (เทียบโอน)"; break;

                    case "486": selectedMajor = "สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี"; break;

                    case "463": selectedMajor = "สาขาวิชาการท่องเที่ยวและการโรงแรม"; break;

                    case "466": case "489": selectedMajor = "สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ"; break;
                    case "469": selectedMajor = "สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ (เทียบโอน)"; break;
                }

                // ตรวจสอบรหัส 4 หลักเพิ่มเติม (เช่น 4863, 4873, 4893)
                if (code4 === "4863") selectedMajor = "สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี (เทียบโอน)";
                if (code4 === "4873") selectedMajor = "สาขาวิชาการตลาดดิจิทัล (เทียบโอน)";
                if (code4 === "4893") selectedMajor = "สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ (เทียบโอน)";

                // Default สำหรับปี 68 ขึ้นไปถ้ายังไม่มีสาขา
                if (selectedMajor === "" && parseInt(studentId.substring(0, 2)) >= 68) {
                    selectedMajor = "สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี";
                }

                if (selectedMajor !== "") {
                    majorSelect.value = selectedMajor;
                    majorSelect.classList.remove('text-gray-400');
                    majorSelect.classList.add('text-gray-700');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('major_select');
            if (select && select.value !== "") {
                select.classList.remove('text-gray-400');
                select.classList.add('text-gray-700');
            }
        });

        function sendOTP() {
            const email = document.getElementById('email').value;
            const btn = document.getElementById('send_otp_btn');
            const timerText = document.getElementById('otp_timer');
            const timerCount = document.getElementById('timer_count');
            const otpSection = document.getElementById('otp_section');

            if (!email || !email.endsWith('@nsru.ac.th')) {
                alert('กรุณากรอกอีเมลนักศึกษา @nsru.ac.th ให้ถูกต้อง');
                return;
            }

            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังส่ง...';

            // Call API
            fetch("{{ route('register.send_otp') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ email: email })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message && !data.errors) {
                        // Show Success
                        alert(data.message);
                        otpSection.classList.remove('hidden');

                        // Start Timer
                        let seconds = 60;
                        timerText.classList.remove('hidden');
                        btn.classList.add('bg-gray-50', 'text-gray-400');

                        const interval = setInterval(() => {
                            seconds--;
                            timerCount.textContent = seconds;
                            if (seconds <= 0) {
                                clearInterval(interval);
                                btn.disabled = false;
                                btn.innerHTML = 'ส่งรหัสอีกครั้ง';
                                btn.classList.remove('bg-gray-50', 'text-gray-400');
                                timerText.classList.add('hidden');
                            }
                        }, 1000);
                    } else {
                        alert(data.message || 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                        btn.disabled = false;
                        btn.innerHTML = 'ส่งรหัส OTP';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    btn.disabled = false;
                    btn.innerHTML = 'ส่งรหัส OTP';
                });
        }
    </script>
</body>

</html>