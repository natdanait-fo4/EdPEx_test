<?php
// จำลองสถานะการล็อกอิน (ถ้ามี ?login=1 ใน URL แปลว่าล็อกอินแล้ว)
$is_logged_in = isset($_GET['login']) && $_GET['login'] == '1';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSRU-MS DETA BEST</title>
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
                <a class="logo" href="index.php<?php echo $is_logged_in ? '?login=1' : ''; ?>"> NSRU-MS DETA BEST </a>
            </div>
            <div class="nav-right">
                <button onclick="toggleDarkMode()" class="theme-btn" title="สลับโหมดสว่าง/มืด">
                    <i class="fa-solid fa-moon" id="theme-icon"></i>
                </button>
                <?php if ($is_logged_in): ?>
                    <a href="index.php" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> ออกจากระบบ</a>
                <?php else: ?>
                    <a href="index.php?login=1" class="login-btn"><i class="fa-solid fa-arrow-right-to-bracket"></i> เข้าสู่ระบบ</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- ส่วนเนื้อหาหลัก -->
    <main class="container main-content">
        <!-- แถบข้อความต้อนรับ -->
        <div class="welcome-banner">
            <div class="welcome-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div class="welcome-text">
                <h2>ยินดีต้อนรับสู่ระบบรับฟังเสียงลูกค้า</h2>
                <p>ข้อมูลของท่านจะถูกนำไปพัฒนาคณะวิทยาการจัดการสู่ความเป็นเลิศ</p>
            </div>
        </div>

        <!-- กริดสำหรับแสดงการ์ดเมนู -->
        <div class="card-grid">
            <!-- การ์ด 1: แจ้งความต้องการ -->
            <?php if ($is_logged_in): ?>
            <div class="card">
                <div class="card-icon icon-blue">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <h3>แจ้งความต้องการ</h3>
                <p>เสนอแนะสิ่งที่อยากให้มีในหลักสูตร/บริการ</p>
                <a href="request.php?login=1" class="btn btn-outline-blue">กรอกข้อมูล</a>
            </div>
            <?php
endif; ?>

            <!-- การ์ด 2: ประเมินความพึงพอใจ -->
            <div class="card">
                <div class="card-icon icon-yellow">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3>ประเมินความพึงพอใจ</h3>
                <p>ให้คะแนนการบริการและหลักสูตร</p>
                <a href="assessment.php<?php echo $is_logged_in ? '?login=1' : ''; ?>" class="btn btn-outline-yellow">ทำแบบประเมิน</a>
            </div>

            <!-- การ์ด 3: ข้อร้องเรียน -->
            <?php if ($is_logged_in): ?>
            <div class="card">
                <div class="card-icon icon-red">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h3>ข้อร้องเรียน</h3>
                <p>แจ้งปัญหาและติดตามสถานะดำเนินการ</p>
                <a href="complaint.php?login=1" class="btn btn-outline-red">ส่งเรื่องร้องเรียน</a>
            </div>
            <?php
endif; ?>

            <!-- การ์ด 4: ถาม - ตอบ (Q&A) -->
            <div class="card">
                <div class="card-icon icon-cyan">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <h3>ถาม - ตอบ (Q&A)</h3>
                <p>ค้นหาคำตอบหรือตั้งคำถามใหม่</p>
                <a href="qa.php<?php echo $is_logged_in ? '?login=1' : ''; ?>" class="btn btn-outline-cyan">ดูคำถามที่พบบ่อย</a>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
