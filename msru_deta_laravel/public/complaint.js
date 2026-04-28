// --- Mock Data ---
        let tickets = [];

        // --- States ---
        let viewRole = 'user'; // 'user' หรือ 'admin'
        let adminFilter = 'ทั้งหมด';
        let selectedTicket = null;
        let uploadedFile = null;

        // --- Helpers ---
        function getStatusBadge(status) {
            switch (status) {
                case 'รอดำเนินการ':
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><span class="w-2 h-2 mr-1.5 bg-red-500 rounded-full"></span>รอดำเนินการ</span>`;
                case 'กำลังดำเนินการ':
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><span class="w-2 h-2 mr-1.5 bg-yellow-500 rounded-full"></span>กำลังดำเนินการ</span>`;
                case 'เสร็จสิ้น':
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full"></span>เสร็จสิ้น</span>`;
                default:
                    return '';
            }
        }

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        // --- Actions ---
        function handleFileSelect(event) {
            const file = event.target.files[0] || (event.dataTransfer && event.dataTransfer.files[0]);
            if (!file) return;
            
            uploadedFile = file;
            const dropText = document.getElementById('file-drop-text');
            const preview = document.getElementById('file-preview');

            dropText.classList.add('hidden');
            preview.classList.remove('hidden');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <button type="button" onclick="removeFile(event)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 z-10 shadow-sm transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                        <img src="${e.target.result}" class="max-h-28 object-contain rounded drop-shadow-sm" />
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `
                    <button type="button" onclick="removeFile(event)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 z-10 shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                    <div class="flex flex-col justify-center items-center text-[#7e059c]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span class="text-xs truncate max-w-[200px] text-center font-medium">${file.name}</span>
                        <span class="text-[10px] text-gray-500">${(file.size / 1024).toFixed(1)} KB</span>
                    </div>
                `;
            }
        }

        function removeFile(event) {
            event.preventDefault();
            event.stopPropagation();
            
            uploadedFile = null;
            const fileInput = document.getElementById('fileInput');
            if(fileInput) fileInput.value = '';
            
            const dropText = document.getElementById('file-drop-text');
            const preview = document.getElementById('file-preview');
            
            if(dropText && preview) {
                dropText.classList.remove('hidden');
                preview.classList.add('hidden');
                preview.innerHTML = '';
            }
        }

        function handleDragOver(event) {
            event.preventDefault();
            const dropArea = event.currentTarget;
            dropArea.classList.add('bg-gray-100', 'border-[#7e059c]', 'border-solid');
            dropArea.classList.remove('border-dashed', 'border-gray-300');
        }

        function handleDragLeave(event) {
            event.preventDefault();
            const dropArea = event.currentTarget;
            dropArea.classList.remove('bg-gray-100', 'border-[#7e059c]', 'border-solid');
            dropArea.classList.add('border-dashed', 'border-gray-300');
        }

    function handleDrop(event) {
        event.preventDefault();
        const dropArea = event.currentTarget;
        dropArea.classList.remove('bg-gray-100', 'border-[#7e059c]', 'border-solid', 'dark:bg-gray-700');
        dropArea.classList.add('border-dashed', 'border-gray-300', 'dark:border-gray-600');
        
        if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
            const fileInput = document.getElementById('fileInput');
            fileInput.files = event.dataTransfer.files; 
            handleFileSelect({ target: fileInput });
        }
    }

    let isDarkMode = false;
    function toggleDarkMode() {
        isDarkMode = !isDarkMode;
        const htmlParams = document.documentElement.classList;
        const themeIcon = document.getElementById('theme-icon');
        
        if (isDarkMode) {
            htmlParams.add('dark');
            if (themeIcon) {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        } else {
            htmlParams.remove('dark');
            if (themeIcon) {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        }
    }

        function switchRole(role) {
            viewRole = role;
            
            // อัปเดตสไตล์ปุ่ม Navbar
            const btnUser = document.getElementById('btnRoleUser');
            const btnAdmin = document.getElementById('btnRoleAdmin');
            
            if (role === 'user') {
                btnUser.className = 'px-4 py-1.5 rounded-md text-sm font-medium flex items-center transition-colors bg-white text-[#7e059c] shadow-sm';
                btnAdmin.className = 'px-4 py-1.5 rounded-md text-sm font-medium flex items-center transition-colors text-gray-500 hover:text-gray-900';
                document.getElementById('pageTitle').innerText = 'ระบบแจ้งเรื่องร้องเรียน และติดตามสถานะ';
                document.getElementById('pageSubtitle').innerText = 'ยินดีต้อนรับ สมชาย (นักศึกษา) คุณสามารถแจ้งปัญหาหรือเสนอแนะได้ที่นี่';
            } else {
                btnAdmin.className = 'px-4 py-1.5 rounded-md text-sm font-medium flex items-center transition-colors bg-gray-800 text-white shadow-sm';
                btnUser.className = 'px-4 py-1.5 rounded-md text-sm font-medium flex items-center transition-colors text-gray-500 hover:text-gray-900';
                document.getElementById('pageTitle').innerText = 'ระบบจัดการข้อร้องเรียน (ส่วนเจ้าหน้าที่)';
                document.getElementById('pageSubtitle').innerText = 'ภาพรวมระบบและการจัดการเรื่องร้องเรียนทั้งหมดของคณะวิทยาการจัดการ';
            }
            
            renderApp();
        }

        function handleUserSubmit(event) {
            event.preventDefault();
            const topic = document.getElementById('newTopic').value;
            const desc = document.getElementById('newDesc').value;

            if (!topic || !desc) return;

            const newTicket = {
                id: `T-00${tickets.length + 1}`,
                date: formatDate(new Date()),
                topic: topic,
                description: desc,
                status: 'รอดำเนินการ',
                user: 'สมชาย (นักศึกษา)',
                reply: ''
            };

            tickets.unshift(newTicket); // เพิ่มไปบนสุด
            uploadedFile = null; // รีเซ็ตไฟล์
            alert('ส่งเรื่องร้องเรียนเรียบร้อยแล้ว');
            renderApp();
        }

        function changeAdminFilter(event) {
            adminFilter = event.target.value;
            renderApp();
        }

        function openModal(ticketId) {
            selectedTicket = tickets.find(t => t.id === ticketId);
            renderModal();
        }

        function closeModal() {
            selectedTicket = null;
            const modal = document.getElementById('detailsModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function handleAdminUpdate(event) {
            event.preventDefault();
            const newStatus = document.getElementById('editStatus').value;
            const newReply = document.getElementById('editReply').value;

            // อัปเดตข้อมูลใน Array
            const ticketIndex = tickets.findIndex(t => t.id === selectedTicket.id);
            if (ticketIndex !== -1) {
                tickets[ticketIndex].status = newStatus;
                tickets[ticketIndex].reply = newReply;
            }

            closeModal();
            renderApp();
            alert('บันทึกข้อมูลเรียบร้อยแล้ว');
        }

        // --- Render Functions ---
        function renderApp() {
            const container = document.getElementById('appContainer');
            if (viewRole === 'user') {
                container.innerHTML = getUserDashboardHTML();
            } else {
                container.innerHTML = getAdminDashboardHTML();
            }
            // สร้างไอคอนใหม่ทุกครั้งที่วาด HTML ใหม่
            lucide.createIcons();
        }

        function getUserDashboardHTML() {
            const userTickets = tickets.filter(t => t.user.includes('สมชาย'));
            
            let tableRows = '';
            if (userTickets.length === 0) {
                tableRows = `<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">ไม่พบประวัติการแจ้งเรื่อง</td></tr>`;
            } else {
                tableRows = userTickets.map(ticket => `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">${ticket.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">${ticket.date}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">${ticket.topic}</div>
                            <div class="text-gray-500 dark:text-gray-400 text-xs truncate max-w-xs mt-1">${ticket.description}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">${getStatusBadge(ticket.status)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button onclick="openModal('${ticket.id}')" class="text-[#7e059c] dark:text-[#d35bff] hover:text-[#520365] dark:hover:text-[#f0a6ff] font-medium text-sm">ดูการตอบกลับ</button>
                        </td>
                    </tr>
                `).join('');
            }

            return `
                <div class="flex flex-col space-y-8 mt-6">
                    <!-- ส่วนที่ 1: ฟอร์มส่งข้อร้องเรียน (ตรงกลาง) -->
                    <div class="max-w-3xl mx-auto w-full space-y-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
                            <div class="bg-[#7e059c] px-5 py-4 flex items-center text-white">
                                <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                                <h2 class="text-lg font-semibold">แจ้งเรื่องร้องเรียน/เสนอแนะ</h2>
                            </div>
                            <form onsubmit="handleUserSubmit(event)" class="p-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">หัวข้อเรื่อง (Topic)</label>
                                    <input type="text" id="newTopic" placeholder="เช่น แอร์ไม่เย็น, เสนอแนะที่จอดรถ" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-transparent outline-none transition-all dark:bg-gray-700 dark:text-white" required />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รายละเอียด (Description)</label>
                                    <textarea id="newDesc" rows="4" placeholder="อธิบายปัญหาที่พบ หรือข้อเสนอแนะ..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-transparent outline-none transition-all resize-none dark:bg-gray-700 dark:text-white" required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">แนบรูปภาพ/ไฟล์ (ทางเลือก)</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label id="file-drop-area" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" class="relative flex flex-col items-center justify-center w-full h-36 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors overflow-hidden">
                                            <div id="file-drop-text" class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400 dark:text-gray-500">
                                                <i data-lucide="paperclip" class="w-6 h-6 mb-2"></i>
                                                <p class="text-xs">คลิกเพื่ออัปโหลด หรือลากไฟล์มาวาง</p>
                                            </div>
                                            <div id="file-preview" class="hidden flex flex-col items-center justify-center w-full h-full p-2 bg-white dark:bg-gray-800 relative transition-colors">
                                            </div>
                                            <input type="file" id="fileInput" class="hidden" onchange="handleFileSelect(event)" />
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[#7e059c] hover:bg-[#63047a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#7e059c] transition-colors">
                                    <i data-lucide="send" class="w-4 h-4 mr-2"></i> ส่งเรื่องร้องเรียน
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- ส่วนที่ 2: ตารางประวัติและติดตามสถานะ (ปิดไว้รอเชื่อมต่อฐานข้อมูล) -->
                </div>
            `;
        }

        function getAdminDashboardHTML() {
            const pendingCount = tickets.filter(t => t.status === 'รอดำเนินการ').length;
            const progressCount = tickets.filter(t => t.status === 'กำลังดำเนินการ').length;
            const resolvedCount = tickets.filter(t => t.status === 'เสร็จสิ้น').length;

            const filteredTickets = adminFilter === 'ทั้งหมด' ? tickets : tickets.filter(t => t.status === adminFilter);

            let tableRows = filteredTickets.map(ticket => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">${ticket.id}</div>
                        <div class="text-gray-500 text-xs">${ticket.date}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${ticket.user.split(' ')[0]}</div>
                        <div class="text-xs text-gray-500">${ticket.user.split(' ')[1]}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">${ticket.topic}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">${getStatusBadge(ticket.status)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <button onclick="openModal('${ticket.id}')" class="inline-flex items-center px-3 py-1.5 border border-[#7e059c] text-[#7e059c] rounded-md hover:bg-[#7e059c] hover:text-white transition-colors text-xs font-medium">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 mr-1"></i> จัดการ / ตอบกลับ
                        </button>
                    </td>
                </tr>
            `).join('');

            return `
                <div class="space-y-6 mt-6">
                    <!-- Dashboard Widgets -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                                <i data-lucide="alert-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">รอดำเนินการ</p>
                                <p class="text-2xl font-bold text-gray-900">${pendingCount} <span class="text-sm font-normal text-gray-500">เรื่อง</span></p>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                <i data-lucide="clock" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">กำลังดำเนินการ</p>
                                <p class="text-2xl font-bold text-gray-900">${progressCount} <span class="text-sm font-normal text-gray-500">เรื่อง</span></p>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">เสร็จสิ้นแล้ว (เดือนนี้)</p>
                                <p class="text-2xl font-bold text-gray-900">${resolvedCount} <span class="text-sm font-normal text-gray-500">เรื่อง</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- ตารางรวมข้อร้องเรียนทั้งหมด -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">จัดการข้อร้องเรียนทั้งหมด</h2>
                            <div class="flex items-center space-x-2 w-full sm:w-auto">
                                <i data-lucide="filter" class="w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                                <select onchange="changeAdminFilter(event)" class="text-sm border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#7e059c] focus:border-[#7e059c] outline-none py-1.5 px-3 border dark:bg-gray-700 dark:text-white">
                                    <option value="ทั้งหมด" ${adminFilter === 'ทั้งหมด' ? 'selected' : ''}>แสดงทั้งหมด</option>
                                    <option value="รอดำเนินการ" ${adminFilter === 'รอดำเนินการ' ? 'selected' : ''}>เฉพาะรอดำเนินการ</option>
                                    <option value="กำลังดำเนินการ" ${adminFilter === 'กำลังดำเนินการ' ? 'selected' : ''}>เฉพาะกำลังดำเนินการ</option>
                                    <option value="เสร็จสิ้น" ${adminFilter === 'เสร็จสิ้น' ? 'selected' : ''}>เฉพาะเสร็จสิ้น</option>
                                </select>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-medium">รหัส / วันที่</th>
                                        <th class="px-6 py-3 text-left font-medium">ผู้แจ้ง</th>
                                        <th class="px-6 py-3 text-left font-medium">หัวข้อ</th>
                                        <th class="px-6 py-3 text-left font-medium">สถานะ</th>
                                        <th class="px-6 py-3 text-center font-medium">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                    ${tableRows}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderModal() {
            if (!selectedTicket) return;
            const modal = document.getElementById('detailsModal');
            const isAdmin = viewRole === 'admin';

            let dynamicContent = '';

            if (isAdmin) {
                dynamicContent = `
                    <form id="admin-reply-form" onsubmit="handleAdminUpdate(event)" class="space-y-4 border-t pt-4">
                        <h4 class="font-medium text-gray-900 flex items-center">
                            <i data-lucide="shield-check" class="w-5 h-5 mr-2 text-[#7e059c]"></i> ส่วนการจัดการของเจ้าหน้าที่
                        </h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ปรับปรุงสถานะ</label>
                            <select id="editStatus" class="w-full sm:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#7e059c] focus:border-[#7e059c] outline-none">
                                <option value="รอดำเนินการ" ${selectedTicket.status === 'รอดำเนินการ' ? 'selected' : ''}>รอดำเนินการ</option>
                                <option value="กำลังดำเนินการ" ${selectedTicket.status === 'กำลังดำเนินการ' ? 'selected' : ''}>กำลังดำเนินการ</option>
                                <option value="เสร็จสิ้น" ${selectedTicket.status === 'เสร็จสิ้น' ? 'selected' : ''}>เสร็จสิ้น</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ข้อความตอบกลับผู้ใช้งาน</label>
                            <textarea id="editReply" rows="4" placeholder="พิมพ์ข้อความชี้แจงกลับไปยังผู้ใช้ เช่น ช่างอาคารได้เข้าไปซ่อมเรียบร้อยแล้ว..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#7e059c] focus:border-transparent outline-none transition-all resize-none">${selectedTicket.reply || ''}</textarea>
                        </div>
                    </form>
                `;
            } else {
                let replyContent = selectedTicket.reply 
                    ? `<div class="bg-green-50 border border-green-100 p-4 rounded-lg text-green-800">${selectedTicket.reply}</div>`
                    : `<div class="bg-gray-50 border border-gray-100 p-4 rounded-lg text-gray-500 italic text-sm text-center">ยังไม่มีการตอบกลับจากเจ้าหน้าที่ในขณะนี้</div>`;
                
                dynamicContent = `
                    <div class="space-y-2">
                        <h4 class="font-medium text-gray-900 flex items-center">
                            <i data-lucide="message-square" class="w-5 h-5 mr-2 text-[#7e059c]"></i> การตอบกลับจากเจ้าหน้าที่
                        </h4>
                        ${replyContent}
                    </div>
                `;
            }

            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
                    <div class="px-6 py-4 flex justify-between items-center ${isAdmin ? 'bg-gray-800' : 'bg-[#7e059c]'}">
                        <h3 class="text-lg font-semibold text-white">
                            ${isAdmin ? 'จัดการข้อร้องเรียน' : 'รายละเอียดการแจ้งเรื่อง'} #${selectedTicket.id}
                        </h3>
                        <button onclick="closeModal()" class="text-gray-300 hover:text-white">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    
                    <div class="p-6 overflow-y-auto flex-1">
                        <div class="space-y-6">
                            <!-- ข้อมูลการแจ้ง -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-lg">${selectedTicket.topic}</h4>
                                        <p class="text-sm text-gray-500 mt-1">แจ้งโดย: ${selectedTicket.user} | วันที่: ${selectedTicket.date}</p>
                                    </div>
                                    <div>${getStatusBadge(selectedTicket.status)}</div>
                                </div>
                                <div class="text-gray-700 whitespace-pre-wrap">${selectedTicket.description}</div>
                            </div>

                            <!-- เนื้อหาแบบไดนามิก (ตามบทบาท) -->
                            ${dynamicContent}
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">ปิดหน้าต่าง</button>
                        ${isAdmin ? `<button type="submit" form="admin-reply-form" class="px-4 py-2 text-sm font-medium text-white bg-[#7e059c] rounded-lg hover:bg-[#63047a] focus:ring-2 focus:ring-offset-2 focus:ring-[#7e059c]">บันทึกและส่งการแจ้งเตือน</button>` : ''}
                    </div>
                </div>
            `;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            lucide.createIcons();
        }

        // --- Initialize App ---
        document.addEventListener('DOMContentLoaded', () => {
            renderApp();
        });
