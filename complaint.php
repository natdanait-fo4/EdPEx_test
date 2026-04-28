<?php
// ตรวจสอบสถานะการเข้าสู่ระบบจำลอง
$is_logged_in = isset($_GET['login']) && $_GET['login'] == '1';
$login_param = $is_logged_in ? '?login=1' : '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบแจ้งเรื่องร้องเรียน คณะวิทยาการจัดการ</title>
    <!-- เรียกใช้งาน Font Awesome สำหรับไอคอน -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- นำเข้า Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        }
    </script>
    <!-- นำเข้า Theme Logic -->
    <script src="theme.js"></script>
    <!-- นำเข้า Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- ตั้งค่าฟอนต์ภาษาไทย -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        * { font-family: 'Prompt', sans-serif; }
        .hidden-block { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-[#7e059c] py-4 shadow-[0_2px_5px_rgba(0,0,0,0.1)]">
        <div class="max-w-[1100px] mx-auto px-[20px] w-full flex justify-between items-center">
            <div>
                <a href="index.php<?php echo $login_param; ?>" class="text-white font-medium text-[1.15rem] tracking-[0.5px]" style="text-decoration: none; font-family: 'Prompt', sans-serif;"> NSRU-MS DETA BEST </a>
            </div>
            <div class="flex items-center gap-5">
                <button onclick="toggleDarkMode()" class="text-white hover:opacity-80 transition-opacity flex items-center justify-center w-8 h-8 rounded-full bg-black/10" title="สลับโหมดสว่าง/มืด">
                    <i class="fa-solid fa-moon" id="theme-icon"></i>
                </button>
                <?php if ($is_logged_in): ?>
                    <a href="index.php" class="text-white hover:opacity-80 transition-opacity text-[0.95rem] flex items-center gap-2" style="text-decoration: none; font-weight: 400; font-family: 'Prompt', sans-serif;"><i class="fa-solid fa-arrow-right-from-bracket"></i> ออกจากระบบ</a>
                <?php else: ?>
                    <a href="index.php?login=1" class="text-white hover:opacity-80 transition-opacity text-[0.95rem] flex items-center gap-2" style="text-decoration: none; font-weight: 400; font-family: 'Prompt', sans-serif;"><i class="fa-solid fa-arrow-right-to-bracket"></i> เข้าสู่ระบบ</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-2 text-center">
            <h1 id="pageTitle" class="text-2xl font-bold text-gray-900 dark:text-white transition-colors">ระบบแจ้งเรื่องร้องเรียน และติดตามสถานะ</h1>
        </div>

        <!-- Container สำหรับสลับหน้าจอ -->
        <div id="appContainer"></div>
    </main>

    <!-- Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <!-- เนื้อหา Modal จะถูกสร้างด้วย JavaScript -->
    </div>

    <!-- JavaScript Logic -->
    <script src="complaint.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>
