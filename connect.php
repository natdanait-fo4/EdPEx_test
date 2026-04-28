<?php
$host = "localhost";
// Username พื้นฐานของ XAMPP คือ root
$username = "root";
// Password พื้นฐานของ XAMPP คือ ค่าว่าง
$password = ""; 
// ชื่อฐานข้อมูลตามที่เราตั้งไว้
$database = "msru_deta_best_db";

try {
    // กำหนดการเชื่อมต่อผ่าน PDO และตั้งค่าให้รองรับภาษาไทย (utf8mb4) อย่างสมบูรณ์
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    
    // ตั้งค่า Error Mode ให้แสดง Exception เมื่อเกิดข้อผิดพลาดในการรัน SQL
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ตั้งค่า Default Fetch Mode ให้ดึงข้อมูลมาเป็น Object (เข้าถึงง่ายขึ้นด้วย $row->id)
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    
} catch(PDOException $e) {
    echo "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
    // หยุดการทำงานถ้าต่อฐานข้อมูลไม่ได้
    exit();
}
?>
