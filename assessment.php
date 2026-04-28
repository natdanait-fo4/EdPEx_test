<?php
$is_logged_in = isset($_GET['login']) && $_GET['login'] == '1';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบประเมินความพึงพอใจ | NSRU-MS DETA BEST</title>
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
                <?php
else: ?>
                    <a href="index.php?login=1" class="login-btn"><i class="fa-solid fa-arrow-right-to-bracket"></i> เข้าสู่ระบบ</a>
                <?php
endif; ?>
            </div>
        </div>
    </nav>

    <!-- ส่วนเนื้อหาหลัก -->
    <main class="container main-content">
        <form action="#" method="POST" class="assessment-card">
            <div class="assessment-header">
                <h2>แบบประเมินความพึงพอใจ</h2>
                <p>คณะวิทยาการจัดการ ขอความอนุเคราะห์ท่านตอบแบบประเมินตามความเป็นจริง คะแนนและข้อเสนอแนะของท่านจะถูกนำไปวิเคราะห์เพื่อวางแผนกลยุทธ์ พัฒนาคุณภาพการศึกษา (EdPEx) และปรับปรุงระบบเว็บไซต์ให้มีประสิทธิภาพสูงสุด</p>
            </div>
            
            <div class="assessment-body">
                <!-- Section 1 -->
                <div class="section-title">
                    <div class="circle-number">1</div>
                    ความพึงพอใจต่อคณะวิทยาการจัดการ
                </div>
                <div class="rating-box">
                    <div class="rating-row">
                        <div class="rating-text">1. คุณภาพการให้บริการของเจ้าหน้าที่</div>
                        <div class="rating-stars">
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <div class="rating-row">
                        <div class="rating-text">2. ความเหมาะสมของหลักสูตรและสิ่งอำนวยความสะดวก</div>
                        <div class="rating-stars">
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="section-title">
                    <div class="circle-number">2</div>
                    ความพึงพอใจต่อระบบเว็บไซต์
                </div>
                <div class="rating-box">
                    <div class="rating-row">
                        <div class="rating-text">1. คุณภาพของข้อมูลระบบเว็บไซต์มีความถูกต้อง</div>
                        <div class="rating-stars">
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <div class="rating-row">
                        <div class="rating-text">2. ระบบไม่มีข้อผิดพลาด (Error)</div>
                        <div class="rating-stars">
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <div class="rating-row">
                        <div class="rating-text">3. การออกแบบหน้าเว็บมีความสวยงามและค้นหาข้อมูลได้ง่าย</div>
                        <div class="rating-stars">
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                </div>

                <!-- Section 3 -->
                <div class="section-title">
                    <div class="circle-number">3</div>
                    ข้อเสนอแนะเพิ่มเติม
                </div>
                <div class="suggestion-box">
                    <textarea rows="4" placeholder="พิมพ์ข้อเสนอแนะ หรือรายละเอียดเชิงลึกที่คุณต้องการให้เราปรับปรุง..."></textarea>
                </div>

                <div class="assessment-footer">
                    <button type="submit" class="btn-submit"><i class="fa-regular fa-paper-plane"></i> ส่งผลการประเมิน</button>
                </div>
            </div>
        </form>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const starGroups = document.querySelectorAll('.rating-stars');
        
        starGroups.forEach(group => {
            const stars = group.querySelectorAll('i');
            
            // ทำให้สามารถ Hover และ Click ดาวได้
            stars.forEach((star, index) => {
                star.addEventListener('mouseover', function() {
                    updateStars(stars, index + 1);
                });
                
                star.addEventListener('mouseout', function() {
                    const currentRating = group.getAttribute('data-rating') || 0;
                    updateStars(stars, currentRating);
                });
                
                star.addEventListener('click', function() {
                    group.setAttribute('data-rating', index + 1);
                    updateStars(stars, index + 1);
                });
            });
        });
        
        function updateStars(stars, rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('fa-regular');
                    star.classList.add('fa-solid', 'star-active');
                } else {
                    star.classList.remove('fa-solid', 'star-active');
                    star.classList.add('fa-regular');
                }
            });
        }
    });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
