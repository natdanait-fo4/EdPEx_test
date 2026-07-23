<?php
use App\Models\Pol;

$pols = [
    [
        'title' => 'POL 1',
        'description' => 'ยินดีต้อนรับสู่ระบบรับฟังเสียงลูกค้า ข้อมูลของท่านจะถูกนำไปพัฒนาคณะวิทยาการจัดการสู่ความเป็นเลิศ',
        'icon_class' => 'fa-solid fa-bullhorn',
        'order_index' => 1,
    ],
    [
        'title' => 'POL 2',
        'description' => 'มุ่งมั่นพัฒนาบริการ เพื่อความพึงพอใจสูงสุดของลูกค้าและผู้ใช้บริการทุกท่าน',
        'icon_class' => 'fa-regular fa-lightbulb',
        'order_index' => 2,
    ],
    [
        'title' => 'POL 3',
        'description' => 'เสียงของคุณคือพลังสำคัญในการขับเคลื่อนนวัตกรรมและบริการของเราให้ก้าวหน้า',
        'icon_class' => 'fa-solid fa-handshake-angle',
        'order_index' => 3,
    ],
    [
        'title' => 'POL 4',
        'description' => 'มุ่งสู่ความเป็นเลิศด้านการจัดการข้อมูล เพื่อยกระดับประสบการณ์ผู้ใช้งาน',
        'icon_class' => 'fa-solid fa-chart-line',
        'order_index' => 4,
    ],
    [
        'title' => 'POL 5',
        'description' => 'เชื่อมโยงทุกความต้องการของคุณเข้ากับการพัฒนาที่ไม่หยุดนิ่ง',
        'icon_class' => 'fa-solid fa-users',
        'order_index' => 5,
    ],
    [
        'title' => 'POL 6',
        'description' => 'เตรียมความพร้อมสู่อนาคตแห่งวิชาชีพและเสริมสร้างทักษะระดับมืออาชีพ',
        'icon_class' => 'fa-solid fa-briefcase',
        'order_index' => 6,
    ],
    [
        'title' => 'POL 7',
        'description' => 'สร้างเครือข่ายความร่วมมือและต่อยอดความรู้สู่ระดับสากลอย่างยั่งยืน',
        'icon_class' => 'fa-solid fa-globe',
        'order_index' => 7,
    ],
];

foreach ($pols as $polData) {
    Pol::create($polData);
}
echo "Seeded " . count($pols) . " POLs.\n";
