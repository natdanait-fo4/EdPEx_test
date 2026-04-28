-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS `msru_deta_best_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `msru_deta_best_db`;

-- 1. ตารางผู้ใช้งาน (users)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- เพิ่มข้อมูลแอดมินจำลองสำหรับการเทสต์ (password: password)
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); 

-- 2. ตารางแจ้งความต้องการ (requests)
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `urgency` varchar(50) NOT NULL,
  `details` text NOT NULL,
  `status` enum('pending','resolved') DEFAULT 'pending',
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. ตารางแบบประเมิน (assessments)
CREATE TABLE IF NOT EXISTS `assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `rating_service` int(1) NOT NULL,
  `rating_system` int(1) NOT NULL,
  `suggestion` text DEFAULT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. ตารางประวัติคำถาม Q&A (questions)
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `privacy` enum('public','private') DEFAULT 'public',
  `notify_email` tinyint(1) DEFAULT 0,
  `status` enum('waiting','answered') DEFAULT 'waiting',
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. ตารางคำถามที่พบบ่อย (FAQs) สำหรับดึงไปแสดงหน้า Q&A
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `category` enum('course','admission','scholarship','activity','other') DEFAULT 'other',
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
