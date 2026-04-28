<?php
// request.php
// จำลองการเช็คอิน
$is_logged_in = isset($_GET['login']) && $_GET['login'] == '1';
if (!$is_logged_in) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนใช้งาน'); window.location.href='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบฟอร์มแจ้งความต้องการ | NSRU-MS DETA BEST</title>
    <!-- เรียกใช้งาน Font Awesome สำหรับไอคอน -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- เรียกใช้งานฟอนต์ Prompt จาก Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="theme.js"></script>
</head>
<body>
    <!-- แถบนำทางด้านบน -->
    <nav class="navbar">
        <div class="container navbar-container">
            <div>
                <a class="logo" href="index.php?login=1"> NSRU-MS DETA BEST </a>
            </div>
            <div class="nav-right">
                <button onclick="toggleDarkMode()" class="theme-btn" title="สลับโหมดสว่าง/มืด">
                    <i class="fa-solid fa-moon" id="theme-icon"></i>
                </button>
                <a href="index.php" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> ออกจากระบบ</a>
            </div>
        </div>
    </nav>

    <!-- ส่วนเนื้อหาหลัก -->
    <main class="container main-content">
        <!-- Header Card -->
        <div class="header-card">
            <div class="lightbulb-icon">
                <i class="fa-regular fa-lightbulb"></i>
            </div>
            <h2>เสียงของท่านสำคัญต่อการพัฒนาคณะวิทยาการจัดการ</h2>
            <p>ทุกความต้องการและข้อเสนอแนะของท่าน จะถูกนำไปใช้วิเคราะห์เพื่อปรับปรุงหลักสูตร สิ่งอำนวยความสะดวก และบริการของเราให้ดียิ่งขึ้น เพื่อตอบโจทย์อนาคตของทุกคน</p>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <i class="fa-regular fa-message"></i> แบบฟอร์มแจ้งความต้องการใหม่
            </div>
            
            <form action="#" method="POST" class="needs-form">
                <div class="form-group">
                    <label>รายละเอียดความต้องการ / ข้อเสนอแนะ <span class="required">*</span></label>
                    <textarea rows="5" placeholder="โปรดอธิบายสิ่งที่ท่านต้องการให้คณะฯ ปรับปรุง หรือเพิ่มเติมอย่างละเอียด..."></textarea>
                </div>
                
                <div class="form-actions-row">
                    <div class="privacy-note">
                        <i class="fa-solid fa-circle-info"></i> ข้อมูลจะถูกเก็บเป็นความลับและใช้วิเคราะห์ภาพรวม
                    </div>
                    <button type="button" class="btn-ai"><i class="fa-solid fa-wand-magic-sparkles"></i> ให้ AI ช่วยวิเคราะห์หมวดหมู่</button>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>หมวดหมู่ความต้องการ <span class="required">*</span></label>
                        <select>
                            <option value="">-- โปรดเลือกหมวดหมู่ --</option>
                            <option value="course">หลักสูตรและการเรียนการสอน</option>
                            <option value="facility">สิ่งอำนวยความสะดวก</option>
                            <option value="service">บริการและอื่นๆ</option>
                        </select>
                    </div>
                    <div class="form-group half">
                        <label>ระดับความสำคัญ <span class="required">*</span></label>
                        <select>
                            <option value="">-- โปรดเลือกระดับความสำคัญ --</option>
                            <option value="high">สูง (เร่งด่วน)</option>
                            <option value="medium">ปานกลาง</option>
                            <option value="low">ต่ำ (ทั่วไป)</option>
                        </select>
                    </div>
                </div>

                <div class="form-submit-row">
                    <button type="submit" class="btn-submit"><i class="fa-regular fa-paper-plane"></i> ส่งข้อมูลความต้องการ</button>
                </div>
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
