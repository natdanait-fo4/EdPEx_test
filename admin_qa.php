<?php
require_once 'connect.php';

// ปกติต้องมีการเช็คสิทธิ์ (Authentication & Authorization) ว่าเป็น admin หรือไม่
// แต่สำหรับ prototype อนุญาตให้เข้าได้เพื่อความสะดวกในการสาธิต
$is_admin = true;

// จัดการการส่งฟอร์ม (Add, Update, Delete)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add') {
            $question = $_POST['question'] ?? '';
            $answer = $_POST['answer'] ?? '';
            $category = $_POST['category'] ?? 'other';

            $stmt = $conn->prepare("INSERT INTO faqs (question, answer, category) VALUES (?, ?, ?)");
            $stmt->execute([$question, $answer, $category]);
            $msg = "เพิ่มคำถามเรียบร้อยแล้ว";
            $msgType = "success";

        }
        elseif ($action === 'edit') {
            $id = $_POST['id'] ?? 0;
            $question = $_POST['question'] ?? '';
            $answer = $_POST['answer'] ?? '';
            $category = $_POST['category'] ?? 'other';

            $stmt = $conn->prepare("UPDATE faqs SET question=?, answer=?, category=? WHERE id=?");
            $stmt->execute([$question, $answer, $category, $id]);
            $msg = "อัปเดตคำถามเรียบร้อยแล้ว";
            $msgType = "success";

        }
        elseif ($action === 'delete') {
            $id = $_POST['id'] ?? 0;
            $stmt = $conn->prepare("DELETE FROM faqs WHERE id=?");
            $stmt->execute([$id]);
            $msg = "ลบคำถามเรียบร้อยแล้ว";
            $msgType = "success";
        }
    }
    catch (PDOException $e) {
        $msg = "เกิดข้อผิดพลาด: " . $e->getMessage();
        $msgType = "error";
    }
}

// Fetch FAQs
$faqs = [];
try {
    $stmt = $conn->query("SELECT * FROM faqs ORDER BY id DESC");
    if ($stmt) {
        $faqs = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
catch (PDOException $e) {
}

// Fetch User Questions
$user_questions = [];
try {
    $stmt = $conn->query("SELECT * FROM questions ORDER BY created_at DESC");
    if ($stmt) {
        $user_questions = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
catch (PDOException $e) {
}

// Handle Status Update for User Questions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $q_id = $_POST['q_id'] ?? 0;
        $new_status = $_POST['status'] ?? 'waiting';
        try {
            $stmt = $conn->prepare("UPDATE questions SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $q_id]);
            $msg = "อัปเดตสถานะคำถามเรียบร้อยแล้ว";
            $msgType = "success";
        }
        catch (PDOException $e) {
            $msg = "เกิดข้อผิดพลาด: " . $e->getMessage();
            $msgType = "error";
        }
    }

    // Handle Reply (Save Answer)
    if ($_POST['action'] === 'reply_question') {
        $q_id = $_POST['q_id'] ?? 0;
        $answer = $_POST['answer'] ?? '';
        try {
            $stmt = $conn->prepare("UPDATE questions SET answer = ?, status = 'answered' WHERE id = ?");
            $stmt->execute([$answer, $q_id]);
            $msg = "บันทึกคำตอบเรียบร้อยแล้ว";
            $msgType = "success";
        }
        catch (PDOException $e) {
            $msg = "เกิดข้อผิดพลาด: " . $e->getMessage();
            $msgType = "error";
        }
    }

    // Handle Delete Question
    if ($_POST['action'] === 'delete_question') {
        $id = $_POST['id'] ?? 0;
        try {
            $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
            if ($stmt->execute([$id])) {
                $msg = "ลบคำถามเรียบร้อยแล้ว";
                $msgType = "success";
            }
        } catch (PDOException $e) {
            $msg = "เกิดข้อผิดพลาด: " . $e->getMessage();
            $msgType = "error";
        }
    }

    if (!isset($msgType) || $msgType === 'success') {
        header("Location: admin_qa.php");
        exit();
    }
}

$msg = $_GET['msg'] ?? '';
$msgType = $_GET['type'] ?? '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคำถาม Q&A (Admin) | NSRU-MS</title>
    <!-- Tailwind CSS สำหรับความรวดเร็วในการจัดหน้าแอดมิน -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Prompt', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    
    <nav class="bg-[#7e059c] text-white p-4 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-semibold"><i class="fa-solid fa-user-shield mr-2"></i> ระบบจัดการ (Admin) - FAQ</h1>
            <a href="qa.php" class="hover:text-yellow-300 transition-colors"><i class="fa-solid fa-arrow-left"></i> กลับไปหน้า Q&A</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6">
        


        <!-- Form Add FAQ -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-800"><i class="fa-solid fa-plus-circle text-[#7e059c]"></i> เพิ่มคำถามใหม่</h2>
            <form action="admin_qa.php" method="POST">
                <input type="hidden" name="action" value="add">
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">คำถาม</label>
                        <input type="text" name="question" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#7e059c] focus:border-[#7e059c]" placeholder="เช่น ต้องใช้เอกสารอะไรบ้างตอนมอบตัว?">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#7e059c] focus:border-[#7e059c]">
                            <option value="course">หลักสูตร</option>
                            <option value="admission">การรับสมัคร</option>
                            <option value="scholarship">ทุนกู้ยืม</option>
                            <option value="activity">กิจกรรม</option>
                            <option value="other">อื่นๆ</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">คำตอบ (สามารถใช้ HTML เช่น &lt;br&gt; เพื่อขึ้นบรรทัดใหม่ได้)</label>
                    <textarea name="answer" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#7e059c] focus:border-[#7e059c]" placeholder="ใส่คำตอบที่นี่..."></textarea>
                </div>
                
                <div class="text-right">
                    <button type="submit" class="bg-[#7e059c] hover:bg-[#5c0373] text-white px-6 py-2 rounded-md transition-colors shadow-sm">
                        <i class="fa-solid fa-save mr-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>

        <!-- General FAQ List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-purple-50">
                <h2 class="text-lg font-semibold text-gray-800"><i class="fa-solid fa-list-ul text-[#7e059c]"></i> รายการ FAQ ทั้งหมด (<?php echo count($faqs); ?> รายการ)</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[80px]">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[120px]">หมวดหมู่</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">คำถาม / คำตอบ</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[120px]">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($faqs)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">ยังไม่มีข้อมูล FAQ ในขณะนี้</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($faqs as $f): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#<?php echo $f->id; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <?php 
                                            $cat_map = ['course'=>'หลักสูตร', 'admission'=>'การรับสมัคร', 'scholarship'=>'ทุนกู้ยืม', 'activity'=>'กิจกรรม', 'other'=>'อื่นๆ'];
                                            echo $cat_map[$f->category] ?? $f->category;
                                        ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($f->question); ?></div>
                                    <div class="text-xs text-gray-500 line-clamp-2"><?php echo strip_tags($f->answer); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick='editFaq(<?php echo json_encode($f); ?>)' class="text-indigo-600 hover:text-indigo-900 p-2 bg-indigo-50 rounded-full transition-colors mr-1" title="แก้ไข">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    
                                    <button type="button" onclick="openDeleteModal('delete', <?php echo $f->id; ?>)" class="text-red-600 hover:text-red-900 p-2 bg-red-50 rounded-full transition-colors" title="ลบ">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Questions List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-purple-50">
                <h2 class="text-lg font-semibold text-gray-800"><i class="fa-solid fa-envelope-open-text text-[#7e059c]"></i> คำถามจากผู้ใช้ (<?php echo count($user_questions); ?> รายการ)</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[100px]">วันที่</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[120px]">สถานะ</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เรื่อง / รายละเอียด</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($user_questions)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                ยังไม่มีคำถามจากผู้ใช้ในขณะนี้
                            </td>
                        </tr>
                        <?php
else: ?>
                            <?php foreach ($user_questions as $q): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    <?php echo date('d/m/Y H:i', strtotime($q->created_at)); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo $q->status == 'answered' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800'; ?>">
                                        <?php echo $q->status == 'answered' ? 'ตอบแล้ว' : 'รอการตอบ'; ?>
                                    </span>
                                    <br>
                                    <span class="text-[10px] text-gray-400">
                                        <i class="fa-solid <?php echo $q->privacy == 'public' ? 'fa-globe' : 'fa-lock'; ?>"></i> 
                                        <?php echo $q->privacy == 'public' ? 'สาธารณะ' : 'ส่วนตัว'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($q->title); ?></div>
                                    <div class="text-xs text-gray-600 mb-2"><?php echo nl2br(htmlspecialchars($q->details)); ?></div>
                                    <?php if ($q->answer): ?>
                                        <div class="bg-blue-50 p-2 rounded border border-blue-100">
                                            <div class="text-[10px] font-bold text-blue-600 uppercase mb-1">คำตอบจากแอดมิน:</div>
                                            <div class="text-xs text-blue-800 italic"><?php echo nl2br(htmlspecialchars($q->answer)); ?></div>
                                        </div>
                                    <?php
        endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <!-- ปุ่มตอบคำถาม -->
                                    <button onclick="openReplyModal(<?php echo htmlspecialchars(json_encode($q)); ?>)" class="text-blue-600 hover:text-blue-900 p-2 bg-blue-50 rounded-full transition-colors mr-1" title="ตอบกลับ">
                                        <i class="fa-solid fa-reply"></i>
                                    </button>
                                    
                                    <form action="admin_qa.php" method="POST" class="inline-block">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="q_id" value="<?php echo $q->id; ?>">
                                        <?php if ($q->status == 'waiting'): ?>
                                            <input type="hidden" name="status" value="answered">
                                            <button type="submit" class="text-green-600 hover:text-green-900 p-2 bg-green-50 rounded-full transition-colors" title="ทำเครื่องหมายว่าตอบแล้ว">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        <?php
        else: ?>
                                            <input type="hidden" name="status" value="waiting">
                                            <button type="submit" class="text-orange-600 hover:text-orange-900 p-2 bg-orange-50 rounded-full transition-colors" title="เปลี่ยนสถานะเป็นรอการตอบ">
                                                <i class="fa-solid fa-undo"></i>
                                            </button>
                                        <?php
        endif; ?>
                                    </form>
                                    <!-- ปุ่มลบคำถามผู้ใช้ -->
                                    <button type="button" onclick="openDeleteModal('delete_question', <?php echo $q->id; ?>)" class="text-red-400 hover:text-red-600 p-2 bg-red-50 rounded-full transition-colors ml-1" title="ลบคำถามนี้">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
    endforeach; ?>
                        <?php
endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden">
            <div class="bg-[#7e059c] text-white px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold">แก้ไขคำถาม</h3>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200"><i class="fa-solid fa-xmark fa-lg"></i></button>
            </div>
            <form action="admin_qa.php" method="POST" class="p-6">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">คำถาม</label>
                    <input type="text" name="question" id="edit_question" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#7e059c] focus:border-[#7e059c]">
                </div>
                
                <div class="mb-4">
                    <
                    <select name="category" id="edit_category" class="w-full px-4 py-2 borderlabel class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label> border-gray-300 rounded-md focus:ring-[#7e059c] focus:border-[#7e059c]">
                        <option value="course">หลักสูตร</option>
                        <option value="admission">การรับสมัคร</option>
                        <option value="scholarship">ทุนกู้ยืม</option>
                        <option value="activity">กิจกรรม</option>
                        <option value="other">อื่นๆ</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">คำตอบ</label>
                    <textarea name="answer" id="edit_answer" required rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#7e059c] focus:border-[#7e059c]"></textarea>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md transition-colors">ยกเลิก</button>
                    <button type="submit" class="bg-[#7e059c] hover:bg-[#5c0373] text-white px-6 py-2 rounded-md transition-colors">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editFaq(faq) {
            document.getElementById('edit_id').value = faq.id;
            document.getElementById('edit_question').value = faq.question;
            document.getElementById('edit_answer').value = faq.answer;
            document.getElementById('edit_category').value = faq.category;
            
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }
    </script>

    <!-- Reply Modal -->
    <div id="replyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden animate-slide-up">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-[#7e059c]">
                <h3 class="text-lg font-bold text-white"><i class="fa-solid fa-reply mr-2"></i> ตอบคำถามผู้ใช้</h3>
                <button onclick="closeReplyModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="admin_qa.php" method="POST" class="p-6">
                <input type="hidden" name="action" value="reply_question">
                <input type="hidden" name="q_id" id="reply_q_id">
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">คำถามจากผู้ใช้:</label>
                    <div id="reply_q_title" class="text-sm font-bold text-gray-800 mb-1"></div>
                    <div id="reply_q_details" class="text-xs text-gray-600 bg-gray-50 p-3 rounded italic border-l-4 border-gray-200"></div>
                </div>

                <div class="mb-6">
                    <label for="reply_answer" class="block text-xs font-bold text-gray-500 uppercase mb-2">คำตอบของคุณ:</label>
                    <textarea name="answer" id="reply_answer" rows="5" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#7e059c] focus:border-transparent outline-none transition-all text-sm" placeholder="พิมพ์คำตอบที่นี่..." required></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeReplyModal()" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-all">ยกเลิก</button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-[#7e059c] text-white rounded-lg font-semibold hover:bg-[#6a0485] shadow-lg shadow-purple-200 transition-all">ส่งคำตอบ</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReplyModal(q) {
            document.getElementById('reply_q_id').value = q.id;
            document.getElementById('reply_q_title').textContent = q.title;
            document.getElementById('reply_q_details').textContent = q.details;
            document.getElementById('reply_answer').value = q.answer || '';
            document.getElementById('replyModal').classList.remove('hidden');
            document.getElementById('replyModal').classList.add('flex');
        }

        function closeReplyModal() {
            document.getElementById('replyModal').classList.add('hidden');
            document.getElementById('replyModal').classList.remove('flex');
        }
    </script>
    
    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[110] items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full overflow-hidden animate-slide-up">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-red-50">
                <h3 class="text-lg font-bold text-red-600"><i class="fa-solid fa-triangle-exclamation mr-2"></i> ยืนยันการลบข้อมูล</h3>
                <button type="button" onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="admin_qa.php" method="POST" class="p-6">
                <input type="hidden" name="action" id="delete_modal_action" value="delete_question">
                <input type="hidden" name="id" id="delete_modal_id">
                
                <div class="text-center mb-6">
                    <i class="fa-regular fa-trash-can text-5xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-700 text-sm">คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?<br><span class="text-red-500 font-medium">หากลบแล้วข้อมูลจะหายไปอย่างถาวร</span></p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-all">ยกเลิก</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 shadow-md transition-all">ลบข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(actionType, itemId) {
            document.getElementById('delete_modal_action').value = actionType;
            document.getElementById('delete_modal_id').value = itemId;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
</body>
</html>
