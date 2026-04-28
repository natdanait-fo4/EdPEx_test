<footer class="site-footer">
    <div class="container footer-container">
        <!-- โลโก้ และ ข้อมูลสรุป -->
        <div class="footer-col about-col">
            <h3 class="footer-logo flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px; width: auto; margin-right: 10px;">
                NSRU-MS DETA SHIP
            </h3>
            <p>ระบบรับฟังเสียงจากทุกท่าน เพื่อพัฒนาคณะวิทยาการจัดการสู่ความเป็นเลิศ</p>
            <p>มหาวิทยาลัยราชภัฏนครสวรรค์</p>
        </div>
        
        <!-- ลิงก์ด่วน -->
        <div class="footer-col links-col">
            <h4>ลิงก์ด่วน</h4>
            <ul>
                <li><a href="{{ route('home.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> หน้าแรก</a></li>
                @if($isLoggedIn)
                <li><a href="{{ route('request.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> แจ้งความต้องการ</a></li>
                @endif
                <li><a href="{{ route('assessment.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> แบบประเมินความพึงพอใจ</a></li>
                @if($isLoggedIn)
                <li><a href="{{ route('complaint.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> ข้อร้องเรียน</a></li>
                @endif
                <li><a href="{{ route('qa.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> ถาม - ตอบ (Q&A)</a></li>
            </ul>
        </div>
        
        <!-- ข้อมูลติดต่อ -->
        <div class="footer-col contact-col">
            <h4>ติดต่อเรา</h4>
            <p><i class="fa-solid fa-location-dot"></i> 460 ถนนนครสวรรค์ ตำบลนครสวรรค์ตก อำเภอเมือง จังหวัดนครสวรรค์ 60000</p>
            <p><i class="fa-solid fa-phone"></i> 056-839-500</p>
            <p><i class="fa-solid fa-envelope"></i> contact@nsru.ac.th</p>
        </div>
        
        <!-- ข้อมูลผู้พัฒนา -->
        <div class="footer-col author-col">
            <h4>ผู้พัฒนา</h4>
            <p><i class="fa-solid fa-code"></i>ทีมผู้จัดทำ BC 66</p>
            <p><i class="fa-solid fa-envelope"></i> [EMAIL_ADDRESS]</p>
        </div>
    </div>
    
    <!-- ข้อมูลลิขสิทธิ์ -->
    <div class="footer-bottom">
        <div class="container">
            <p>&copy; {{ date("Y") }} NSRU-MS DETA SHIP, คณะวิทยาการจัดการ มหาวิทยาลัยราชภัฏนครสวรรค์. สงวนลิขสิทธิ์.</p>
        </div>
    </div>
</footer>
