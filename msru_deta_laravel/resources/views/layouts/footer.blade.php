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
            
            <!-- ช่องทาง Social Media -->
            <div class="social-links" style="display: flex; gap: 12px; margin-top: 10px; margin-bottom: 20px;">
                <a href="https://www.facebook.com/ManagementSciencesNSRU" target="_blank" title="Facebook คณะวิทยาการจัดการ มรนส." 
                   style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background-color: rgba(255, 255, 255, 0.1); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2); transition: all 0.3s ease; text-decoration: none;"
                   onmouseover="this.style.backgroundColor='#1877F2'; this.style.borderColor='#1877F2'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 5px 15px rgba(24, 119, 242, 0.4)'"
                   onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(255, 255, 255, 0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fa-brands fa-facebook-f" style="font-size: 18px;"></i>
                </a>
                <a href="https://www.youtube.com/@nsruchanneltv" target="_blank" title="YouTube NSRU Channel" 
                   style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background-color: rgba(255, 255, 255, 0.1); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2); transition: all 0.3s ease; text-decoration: none;"
                   onmouseover="this.style.backgroundColor='#FF0000'; this.style.borderColor='#FF0000'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 5px 15px rgba(255, 0, 0, 0.4)'"
                   onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(255, 255, 255, 0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fa-brands fa-youtube" style="font-size: 18px;"></i>
                </a>
            </div>
        </div>
        
        <!-- ลิงก์ด่วน -->
        <div class="footer-col links-col hide-on-mobile">
            <h4>ลิงก์ด่วน</h4>
            <ul>
                <li><a href="{{ route('home.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> หน้าแรก</a></li>
                <li><a href="{{ route('request.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> แจ้งความต้องการ</a></li>
                <li><a href="{{ route('assessment.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> แบบประเมินความพึงพอใจ</a></li>
                <li><a href="{{ route('complaint.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> ข้อร้องเรียน</a></li>
                <li><a href="{{ route('qa.index') }}{{ $isLoggedIn ? '?login=1' : '' }}"><i class="fa-solid fa-chevron-right"></i> ถาม - ตอบ (Q&A)</a></li>
            </ul>
        </div>
        
        <!-- ข้อมูลติดต่อ -->
        <div class="footer-col contact-col hide-on-mobile">
            <h4>ติดต่อเรา</h4>
            <p><i class="fa-solid fa-location-dot"></i> 460 ถนนนครสวรรค์ ตำบลนครสวรรค์ตก อำเภอเมือง จังหวัดนครสวรรค์ 60000</p>
            <p><i class="fa-solid fa-phone"></i> 056-839-500</p>
            <p><i class="fa-solid fa-envelope"></i> contact@nsru.ac.th</p>
        </div>
        
        <!-- ข้อมูลผู้พัฒนา -->
        <div class="footer-col author-col hide-on-mobile">
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
