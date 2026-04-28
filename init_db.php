<?php
require_once 'connect.php';

try {
    // Drop existing if running again for safety (Optional, but let's just create if not exists)
    $stmt = $conn->prepare("
        CREATE TABLE IF NOT EXISTS `faqs` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `question` text NOT NULL,
          `answer` text NOT NULL,
          `category` enum('course','admission','scholarship','activity','other') DEFAULT 'other',
          `created_at` timestamp DEFAULT current_timestamp(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    $stmt->execute();

    // Check if table is empty
    $check = $conn->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
    if ($check == 0) {
        // Insert sample data
        $insert = $conn->prepare("
            INSERT INTO `faqs` (`question`, `answer`, `category`) VALUES
            ('คณะวิทยาการจัดการเปิดสอนหลักสูตรอะไรบ้าง?', 'คณะวิทยาการจัดการเปิดสอนหลักสูตรระดับปริญญาตรี 7 หลักสูตร ได้แก่<br>1. สาขาวิชาการบัญชี (Accounting)<br>2. สาขาวิชาการตลาดดิจิทัล (Digital Marketing)<br>3. สาขาวิชานิเทศศาสตร์ (Communication Arts)<br>4. สาขาวิชาเศรษฐศาสตร์ การจัดการธุรกิจการค้าสมัยใหม่ (Managerial Economics of Modern Trade Business)<br>5. สาขาวิชาธุรกิจดิจิทัลและเทคโนโลยี (Digital Business and Technology)<br>6. สาขาวิชาการท่องเที่ยวและการโรงแรม (Tourism and Hotel Management)<br>7. สาขาวิชาการจัดการธุรกิจทรัพยากรมนุษย์และองค์การ (Business Human Resource Management and Organization Management)', 'course'),
            ('จบไปแล้วทำงานอะไรได้บ้าง?', 'สามารถทำงานได้หลากหลาย เช่น นักวิเคราะห์ระบบ, ผู้ดูแลระบบฐานข้อมูล, นักพัฒนาซอฟต์แวร์, นักการตลาดดิจิทัล และบุคลากรสายไอทีในองค์กรต่างๆ', 'course'),
            ('ต้องใช้คะแนนอะไรบ้างในการสมัคร?', 'ขึ้นอยู่กับรอบการรับสมัครครับ หากเป็นระบบ TCAS จะใช้คะแนน TGAT/TPAT และ/หรือ A-Level ตามเกณฑ์ที่กำหนดในแต่ละปีการศึกษา', 'admission'),
            ('มีทุน กยศ. หรือ กรอ. ให้กู้ยืมไหม?', 'มีครับ ทางมหาวิทยาลัยมีกองทุนเงินให้กู้ยืมเพื่อการศึกษา (กยศ.) ให้นักศึกษาที่มีคุณสมบัติตามเกณฑ์สามารถขอกู้ยืมได้', 'scholarship'),
            ('ปี 1 ต้องรับน้องไหม?', 'กิจกรรมต้อนรับนักศึกษาใหม่เป็นการเข้าร่วมโดยความสมัครใจ เน้นกิจกรรมเชิงสร้างสรรค์ ไม่มีการบังคับหรือการรับน้องที่ไม่เหมาะสมครับ', 'activity');
        ");
        $insert->execute();
        echo "✅ Table 'faqs' created and initial data inserted successfully.\n";
    } else {
        echo "ℹ️ Table 'faqs' already exists and contains data.\n";
    }
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
