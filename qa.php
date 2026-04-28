<?php
require_once 'connect.php';
$is_logged_in = isset($_GET['login']) && $_GET['login'] == '1';

// Fetch FAQs (Regular FAQs)
$faqs_result = [];
try {
    $stmt = $conn->query("SELECT * FROM faqs ORDER BY id ASC");
    if ($stmt) {
        $faqs_result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
} catch (PDOException $e) { $faqs_result = []; }

// Fetch Public User Inquiries (Answered Questions)
$public_inquiries = [];
try {
    $stmt = $conn->query("SELECT * FROM questions WHERE privacy = 'public' AND status = 'answered' ORDER BY created_at DESC");
    if ($stmt) {
        $public_inquiries = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
} catch (PDOException $e) { $public_inquiries = []; }

// Handle User Question Submission
$submit_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_qa'])) {
    $title = $_POST['title'] ?? '';
    $details = $_POST['details'] ?? '';
    $privacy = $_POST['privacy'] ?? 'public';
    $notify = isset($_POST['notify_email']) ? 1 : 0;
    
    try {
        $stmt = $conn->prepare("INSERT INTO questions (title, details, privacy, notify_email, status) VALUES (?, ?, ?, ?, 'waiting')");
        $stmt->execute([$title, $details, $privacy, $notify]);
        $submit_msg = "ส่งคำถามเรียบร้อยแล้ว! เจ้าหน้าที่จะตรวจสอบและตอบกลับเร็วๆ นี้";
    } catch (PDOException $e) {
        $submit_msg = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ศูนย์ช่วยเหลือและถามตอบ (Q&A) | NSRU-MS DETA BEST</title>
    <!-- เรียกใช้งาน Font Awesome สำหรับไอคอน -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- เรียกใช้งานฟอนต์ Prompt จาก Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

    <!-- Header Section (White) -->
    <div class="qa-hero">
        <div class="container">
            <h2>ศูนย์ช่วยเหลือและถามตอบ (Q&A)</h2>
            <p>ค้นหาคำตอบที่คุณต้องการ หรือตั้งคำถามใหม่เพื่อให้เจ้าหน้าที่ช่วยเหลือ</p>
            
            <div class="qa-search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" class="qa-search-input" placeholder="พิมพ์คำค้น เช่น ค่าเทอม, ทุนการศึกษา...">
            </div>
            
            <div class="qa-tags">
                <span class="qa-tag active" data-target="all">ทั้งหมด</span>
                <span class="qa-tag" data-target="course">หลักสูตร</span>
                <span class="qa-tag" data-target="admission">การรับสมัคร</span>
                <span class="qa-tag" data-target="scholarship">ทุนกู้ยืม</span>
                <span class="qa-tag" data-target="activity">กิจกรรม</span>
            </div>
        </div>
    </div>

    <!-- Main Content Section (Gray) -->
    <div class="qa-main-wrapper">
        <div class="container qa-layout">
            
            <!-- Left Column: FAQ -->
            <div class="qa-left-col">
                <div class="qa-section-header">
                    <i class="fa-regular fa-comment-dots" style="color: var(--primary-color);"></i> คำถามที่พบบ่อย (FAQ)
                </div>
                
                <div class="faq-list">
                    <?php if (empty($faqs_result)): ?>
                        <div style="padding: 30px; text-align: center; color: #888; background: #fff; border-radius: 8px;">
                            <i class="fa-solid fa-circle-info fa-2x mb-3" style="color: var(--primary-color);"></i>
                            <br>ยังไม่มีคำถามในระบบ หรือ ฐานข้อมูลยังไม่ได้ถูกตั้งค่า 
                            <br><a href="init_db.php" class="btn" style="display:inline-block; margin-top: 15px; background:var(--primary-color); color:#fff; padding: 8px 16px; text-decoration: none; border-radius:4px;"><i class="fa-solid fa-database"></i> ติดตั้งฐานข้อมูลคลิกที่นี่</a>
                        </div>
                    <?php else: ?>
                        <?php foreach($faqs_result as $index => $faq): ?>
                            <div class="faq-item <?php echo $index === 0 ? 'active' : ''; ?>" data-category="<?php echo htmlspecialchars($faq->category); ?>">
                                <div class="faq-question">
                                    <span class="faq-q-text"><span class="faq-icon-text"><?php echo $index === 0 ? '[-]' : '[+]'; ?></span> <?php echo htmlspecialchars($faq->question); ?></span>
                                    <i class="fa-solid <?php echo $index === 0 ? 'fa-chevron-up' : 'fa-chevron-down'; ?>"></i>
                                </div>
                                <div class="faq-answer" style="<?php echo $index === 0 ? '' : 'display: none;'; ?>">
                                    <?php echo $faq->answer; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- User Public Inquiries Section -->
                <?php if(!empty($public_inquiries)): ?>
                <div class="public-inquiries-section" style="margin-top: 50px;">
                    <h2 class="qa-section-header">
                        <i class="fa-solid fa-comments text-primary-color"></i> คำถามที่น่าสนใจจากผู้ใช้จริง
                    </h2>
                    <div class="faq-list">
                        <?php foreach($public_inquiries as $q): ?>
                        <div class="faq-item">
                            <div class="faq-question">
                                <span class="faq-q-text">
                                    <span class="faq-icon-text">[+]</span> <?php echo htmlspecialchars($q->title); ?>
                                </span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer" style="display: none;">
                                <div style="margin-bottom: 12px; font-size: 0.9rem; color: #666;">
                                    <strong>รายละเอียด:</strong> <?php echo nl2br(htmlspecialchars($q->details)); ?>
                                </div>
                                <div style="background: #fdf2ff; border-left: 3px solid var(--primary-color); padding: 15px; border-radius: 4px; border: 1px solid #f5e6ff; background-color: #fdf8ff;">
                                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--primary-color); text-transform: uppercase; margin-bottom: 5px;">คำตอบจากทางมหาวิทยาลัย:</div>
                                    <div style="color: #333; font-weight: 500;"><?php echo nl2br(htmlspecialchars($q->answer)); ?></div>
                                </div>
                                <div style="margin-top: 10px; font-size: 0.75rem; color: #999; text-align: right;">
                                    <i class="fa-regular fa-clock"></i> เมื่อวันที่ <?php echo date('d/m/Y', strtotime($q->created_at)); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Right Column: Sidebar -->
            <div class="qa-right-col">
                <!-- Ask New Question Card -->
                <div class="ask-card">
                    <h3>ไม่พบคำตอบที่ต้องการ?</h3>
                    <p>คุณสามารถตั้งคำถามใหม่เพื่อให้เจ้าหน้าที่ของเราช่วยตอบได้</p>
                    <button class="btn-ask"><i class="fa-solid fa-plus"></i> ตั้งกระทู้ถาม</button>
                </div>
                
                <!-- Question History Card -->
                <div class="history-card">
                    <div class="history-header">
                        <i class="fa-regular fa-clock"></i> ประวัติคำถามของฉัน
                    </div>
                    
                    <div style="text-align: center; color: #888; padding: 20px 0; font-size: 0.9rem;">
                        ยังไม่มีประวัติการตั้งคำถาม
                    </div>
                    
                    <!-- ข้อมูลประวัติจริงจะถูกดึงมาจาก Database (MySQL) ในภายหลัง -->
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- New Question Modal -->
    <div id="askModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>ตั้งกระทู้ถามใหม่</h3>
                <span class="close-modal">&times;</span>
            </div>
            <form action="qa.php" method="POST" id="qaForm">
                <input type="hidden" name="submit_qa" value="1">
                <input type="hidden" name="privacy" id="qaPrivacy" value="public">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>หัวข้อเรื่อง</label>
                        <input type="text" name="title" id="qaTitle" class="form-control" placeholder="เช่น สอบถามเรื่องค่าเทอมสาขาคอมธุรกิจ">
                        <div id="qaTitleError" class="error-text"><i class="fa-solid fa-asterisk"></i> กรุณากรอกหัวข้อเรื่อง</div>
                    </div>
                    
                    <div class="form-group">
                        <label>รายละเอียดคำถาม</label>
                        <textarea name="details" id="qaDesc" class="form-control" rows="4" placeholder="อธิบายรายละเอียดหรือข้อสงสัยของคุณเพิ่มเติม..."></textarea>
                        <div id="qaDescError" class="error-text"><i class="fa-solid fa-asterisk"></i> กรุณากรอกรายละเอียดคำถาม</div>
                    </div>
                    
                    <div class="form-group">
                        <label>ความเป็นส่วนตัวของคำถาม</label>
                        <div class="privacy-options">
                            <div class="privacy-btn active" data-privacy="public">
                                <i class="fa-solid fa-earth-americas"></i> ถามแบบสาธารณะ
                            </div>
                            <div class="privacy-btn" data-privacy="private">
                                <i class="fa-solid fa-lock"></i> ถามส่วนตัว
                            </div>
                        </div>
                        <p class="privacy-note" id="privacyNote">* คำถามแบบสาธารณะจะแสดงในหน้า FAQ เพื่อเป็นประโยชน์ต่อผู้อื่น</p>
                    </div>
                    
                    <div class="form-group checkbox-group" style="margin-bottom: 0;">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="notify_email" checked>
                            <span class="checkmark"></span>
                            แจ้งเตือนทางอีเมลเมื่อได้รับคำตอบ
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel">ยกเลิก</button>
                    <button type="button" class="btn btn-submit-qa">ส่งคำถาม</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple Accordion Logic for FAQ
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                const answer = item.querySelector('.faq-answer');
                const icon = item.querySelector('.faq-question i');
                
                // Close all other items 
                faqItems.forEach(otherItem => {
                    otherItem.classList.remove('active');
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    if (otherAnswer) otherAnswer.style.display = 'none';
                    
                    const Irish = otherItem.querySelector('.faq-question i');
                    if (Irish) Irish.className = Irish.className.replace('fa-chevron-up', 'fa-chevron-down');
                    
                    const iconText = otherItem.querySelector('.faq-icon-text');
                    if (iconText) iconText.textContent = '[+]';
                });
                
                // Toggle current item
                if (!isActive) {
                    item.classList.add('active');
                    if (answer) answer.style.display = 'block';
                    if (icon) icon.className = icon.className.replace('fa-chevron-down', 'fa-chevron-up');
                    
                    const iconText = item.querySelector('.faq-icon-text');
                    if (iconText) iconText.textContent = '[-]';
                }
            });
        });
        
        // Category Tags Logic
        const qaTags = document.querySelectorAll('.qa-tag');
        qaTags.forEach(tag => {
            tag.addEventListener('click', function() {
                // ลบ active ออกจากปุ่มอื่นทั้งหมด
                qaTags.forEach(t => t.classList.remove('active'));
                // เพิ่ม active ให้ปุ่มที่กำลังโดนคลิก
                this.classList.add('active');
                
                const target = this.getAttribute('data-target');
                
                // Filter FAQ items
                faqItems.forEach(item => {
                    if (target === 'all' || item.getAttribute('data-category') === target) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                    
                    // Collapse item when filter changes
                    item.classList.remove('active');
                    item.querySelector('.faq-answer').style.display = 'none';
                    item.querySelector('.faq-question i').className = 'fa-solid fa-chevron-down';
                    item.querySelector('.faq-icon-text').textContent = '[+]';
                });
            });
        });

        // Modal Logic
        const modal = document.getElementById('askModal');
        const btnAsk = document.querySelector('.btn-ask');
        const spanClose = document.querySelector('.close-modal');
        const btnCancel = document.querySelector('.btn-cancel');

        btnAsk.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        const closeModal = () => {
            modal.style.display = 'none';
        };

        spanClose.addEventListener('click', closeModal);
        btnCancel.addEventListener('click', closeModal);

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        // Privacy Options Logic
        const privacyBtns = document.querySelectorAll('.privacy-btn');
        const privacyNote = document.getElementById('privacyNote');
        
        privacyBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                privacyBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const privacyValue = this.getAttribute('data-privacy');
                document.getElementById('qaPrivacy').value = privacyValue;
                
                if (privacyValue === 'public') {
                    privacyNote.textContent = '* คำถามแบบสาธารณะจะแสดงในหน้า FAQ เพื่อเป็นประโยชน์ต่อผู้อื่น';
                } else {
                    privacyNote.textContent = '* คำถามส่วนตัวจะมองเห็นได้เฉพาะคุณและเจ้าหน้าที่เท่านั้น';
                }
            });
        });

        // Form Validation Logic
        const btnSubmitQa = document.querySelector('.btn-submit-qa');
        const qaTitle = document.getElementById('qaTitle');
        const qaDesc = document.getElementById('qaDesc');
        const qaTitleError = document.getElementById('qaTitleError');
        const qaDescError = document.getElementById('qaDescError');

        btnSubmitQa.addEventListener('click', function() {
            let isValid = true;
            
            // Validate Title
            if (qaTitle.value.trim() === '') {
                qaTitle.classList.add('input-error');
                qaTitleError.style.display = 'block';
                isValid = false;
            } else {
                qaTitle.classList.remove('input-error');
                qaTitleError.style.display = 'none';
            }
            
            // Validate Description
            if (qaDesc.value.trim() === '') {
                qaDesc.classList.add('input-error');
                qaDescError.style.display = 'block';
                isValid = false;
            } else {
                qaDesc.classList.remove('input-error');
                qaDescError.style.display = 'none';
            }
            
            if (isValid) {
                // Submit form
                document.getElementById('qaForm').submit();
            }
        });

        // Show welcome message if just submitted
        <?php if ($submit_msg): ?>
            alert('<?php echo $submit_msg; ?>');
        <?php endif; ?>

        // Remove error when typing
        qaTitle.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('input-error');
                qaTitleError.style.display = 'none';
            }
        });
        
        qaDesc.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('input-error');
                qaDescError.style.display = 'none';
            }
        });
    });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
