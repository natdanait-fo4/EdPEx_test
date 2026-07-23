@extends('layouts.admin')

@section('title', 'ผลการประเมิน | Admin Dashboard')
@section('header_title', 'รายละเอียดผลการประเมิน')

@section('content')
<style>
/* ===== Custom Scrollbar Styling ===== */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}
.overflow-x-auto::-webkit-scrollbar-track {
    background: #f3e8ff;
    border-radius: 999px;
}
.overflow-x-auto::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, #7e059c, #a855f7);
    border-radius: 999px;
    transition: background 0.3s ease;
}
.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(90deg, #6b0485, #9333ea);
}
/* Dark mode scrollbar */
.dark .overflow-x-auto::-webkit-scrollbar-track {
    background: #1e1b4b;
}
.dark .overflow-x-auto::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, #7e059c, #a855f7);
}
.dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(90deg, #9d07c2, #c084fc);
}

@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    /* Hide layout wrapper elements when printing */
    #skeleton-loader, .sidebar, .top-navbar, .no-print, header, aside {
        display: none !important;
    }
    /* Reset main wrapper styles for printing layout */
    .main-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    .content-area {
        padding: 0 !important;
        margin: 0 !important;
    }
    /* Hide everything inside content-area except the modal */
    .content-area > *:not(#detailModal) {
        display: none !important;
    }
    /* Style detailModal specifically for printing */
    #detailModal {
        position: relative !important;
        display: block !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
    }
    #detailModal * {
        visibility: visible !important;
    }
    @page {
        size: A4;
        margin: 15mm 20mm;
    }
}
/* Modal Animation */
@keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.95) translateY(-10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal {
    animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes backdropFadeIn {
    from { opacity: 0; backdrop-filter: blur(0px); }
    to { opacity: 1; backdrop-filter: blur(4px); }
}
.animate-backdrop {
    animation: backdropFadeIn 0.3s ease-out forwards;
}
</style>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">ข้อมูลการส่งประเมินทั้งหมด</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">รายละเอียดคำตอบและสรุปความพึงพอใจแยกตามหมวดหมู่หลัก</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.assessment.export', ['major' => $selectedMajor]) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors border border-green-800 flex items-center shadow-sm">
                <i class="fa-solid fa-file-excel mr-2"></i> ส่งออก Excel
            </a>
            <a href="{{ route('admin.assessment.index') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium transition-colors border border-gray-300 dark:border-gray-600 flex items-center">
                <i class="fa-solid fa-gear mr-2"></i> จัดการหัวข้อประเมิน
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.assessment.responses') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <label for="majorFilter" class="text-sm font-bold text-gray-700 dark:text-gray-300">
                <i class="fa-solid fa-filter text-purple-500 mr-1"></i> ตัวกรองสาขา:
            </label>
            <select id="majorFilter" name="major" onchange="this.form.submit()" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-sm text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">-- แสดงทุกสาขา --</option>
                @foreach($majors as $major)
                    <option value="{{ $major }}" {{ $selectedMajor === $major ? 'selected' : '' }}>{{ $major }}</option>
                @endforeach
            </select>
            @if($selectedMajor)
                <a href="{{ route('admin.assessment.responses') }}" class="text-sm text-red-500 hover:underline flex items-center ml-2">
                    <i class="fa-solid fa-circle-xmark mr-1"></i> ล้างตัวกรอง
                </a>
            @endif
        </form>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            พบข้อมูลการประเมิน <strong class="text-purple-600 dark:text-purple-400">{{ count($responses) }}</strong> รายการ
        </div>
    </div>

    <!-- Charts Section -->
    <div class="mb-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Assessment Chart -->
        <div class="lg:col-span-2 p-4 bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm flex flex-col">
            <h3 class="text-md font-bold text-gray-700 dark:text-gray-200 mb-4 flex items-center shrink-0">
                <i class="fa-solid fa-chart-column text-purple-500 mr-2"></i> สรุปคะแนนความพึงพอใจเฉลี่ย
            </h3>
            <div class="relative h-64 md:h-80 w-full grow">
                <canvas id="assessmentChart"></canvas>
            </div>
        </div>

        <!-- Major Pie Chart -->
        <div class="p-4 bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm flex flex-col">
            <h3 class="text-md font-bold text-gray-700 dark:text-gray-200 mb-4 flex items-center shrink-0">
                <i class="fa-solid fa-chart-pie text-purple-500 mr-2"></i> สัดส่วนผู้ประเมิน
            </h3>
            <div class="relative h-64 md:h-80 w-full grow flex items-center justify-center">
                <canvas id="majorPieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Compact Table -->
    <div class="overflow-x-auto">
        @php
            $shortNames = [
                'สำนักงานคณะวิทยาการจัดการ' => 'คณะวิทยาการฯ',
                'กองพัฒนานักศึกษา' => 'กองพัฒนาฯ',
                'งานกองทุนกู้ยืมเพื่อการศึกษา' => 'กองทุน กยศ.',
                'ศูนย์พยาบาลและส่งเสริมสุขภาพ' => 'ศูนย์พยาบาลฯ',
                'สำนักวิทยบริการและเทคโนโลยีสารสนเทศ' => 'วิทยบริการฯ',
                'สำนักส่งเสริมวิชาการและงานทะเบียน' => 'วิชาการ & ทะเบียน',
                'สำนักสงเสริมวิชาการและงานทะเบียน' => 'วิชาการ & ทะเบียน'
            ];
            
            // Calculate category averages for Chart
            $chartLabels = [];
            $chartData = [];
            $chartColors = [
                'rgba(147, 51, 234, 0.7)',  // Purple
                'rgba(59, 130, 246, 0.7)',  // Blue
                'rgba(16, 185, 129, 0.7)',  // Emerald
                'rgba(245, 158, 11, 0.7)',  // Amber
                'rgba(239, 68, 68, 0.7)',   // Red
                'rgba(236, 72, 153, 0.7)',  // Pink
                'rgba(14, 165, 233, 0.7)'   // Sky
            ];
            $chartBorderColors = [
                'rgb(147, 51, 234)',
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)',
                'rgb(239, 68, 68)',
                'rgb(236, 72, 153)',
                'rgb(14, 165, 233)'
            ];

            // Calculate Pie Chart Data (Majors)
            $pieLabels = [];
            $pieData = [];
            $pieColors = [];
            $pieBorderColors = [];
            
            $majorCounts = [];
            foreach($responses as $response) {
                $major = $response->major ?: 'อื่นๆ';
                if (!isset($majorCounts[$major])) {
                    $majorCounts[$major] = 0;
                }
                $majorCounts[$major]++;
            }
            arsort($majorCounts);
            
            $i = 0;
            foreach($majorCounts as $major => $count) {
                $pieLabels[] = $major;
                $pieData[] = $count;
                $pieColors[] = $chartColors[$i % count($chartColors)];
                $pieBorderColors[] = $chartBorderColors[$i % count($chartBorderColors)];
                $i++;
            }

            $categoryScores = [];
            $categoryDetails = [];
            $questionScores = [];

            foreach($questions->groupBy('category') as $category => $categoryQuestions) {
                $catName = $shortNames[$category] ?? ($category ?: 'ทั่วไป');
                $chartLabels[] = $catName;
                $categoryScores[$catName] = [];
                $questionScores[$catName] = [];
                foreach($categoryQuestions as $q) {
                    $questionScores[$catName][$q->id] = ['sum' => 0, 'count' => 0, 'text' => $q->question_text];
                }
            }

            foreach($responses as $response) {
                $answerMap = $response->answers->pluck('score', 'question_id')->toArray();
                foreach($questions->groupBy('category') as $category => $categoryQuestions) {
                    $catName = $shortNames[$category] ?? ($category ?: 'ทั่วไป');
                    $catSum = 0;
                    $catCount = 0;
                    foreach ($categoryQuestions as $q) {
                        if (isset($answerMap[$q->id])) {
                            $score = $answerMap[$q->id];
                            $catSum += $score;
                            $catCount++;
                            $questionScores[$catName][$q->id]['sum'] += $score;
                            $questionScores[$catName][$q->id]['count']++;
                        }
                    }
                    if ($catCount > 0) {
                        $categoryScores[$catName][] = $catSum / $catCount;
                    }
                }
            }

            foreach($chartLabels as $label) {
                $scores = $categoryScores[$label];
                $chartData[] = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0;
                
                $details = [];
                foreach($questionScores[$label] as $qId => $qData) {
                    $avg = $qData['count'] > 0 ? round($qData['sum'] / $qData['count'], 2) : 0;
                    $details[] = [
                        'text' => $qData['text'],
                        'avg' => $avg
                    ];
                }
                $categoryDetails[$label] = $details;
            }
        @endphp
        <table class="w-full text-left border-collapse min-w-[1000px] border border-gray-200 dark:border-gray-700">
            <thead>
                <tr class="bg-purple-50 dark:bg-purple-900/30 border-b border-purple-100 dark:border-purple-800 text-[#7e059c] dark:text-purple-300 text-[11px] uppercase tracking-wider font-bold">
                    <th class="px-4 py-3 text-center border-r border-gray-200 dark:border-gray-700" width="60">ID</th>
                    <th class="px-4 py-3 border-r border-gray-200 dark:border-gray-700" width="120">วันที่ส่ง</th>
                    <th class="px-4 py-3 border-r border-gray-200 dark:border-gray-700" style="min-width: 160px;">ผู้ส่ง</th>
                    <th class="px-4 py-3 border-r border-gray-200 dark:border-gray-700" style="min-width: 140px;">สาขา</th>
                    <th class="px-4 py-3 border-r border-gray-200 dark:border-gray-700" width="120">IP Address</th>
                    @foreach($questions->groupBy('category') as $category => $categoryQuestions)
                    <th class="px-3 py-3 text-center border-r border-gray-200 dark:border-gray-700" style="min-width: 95px;" title="{{ $category ?: 'ทั่วไป' }}">
                        <i class="fa-solid fa-folder-open mr-1 opacity-70"></i> {{ $shortNames[$category] ?? ($category ?: 'ทั่วไป') }}
                    </th>
                    @endforeach
                    <th class="px-4 py-3 border-r border-gray-200 dark:border-gray-700" style="min-width: 150px;">ข้อเสนอแนะ</th>
                    <th class="px-4 py-3 text-right" width="110">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 italic-score">
                @foreach($responses as $response)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors text-sm">
                    <td class="px-4 py-4 text-center text-gray-400 dark:text-gray-500 border-r border-gray-200 dark:border-gray-700 font-medium">{{ $response->id }}</td>
                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700 whitespace-nowrap">{{ $response->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-4 border-r border-gray-200 dark:border-gray-700">
                        @if($response->user)
                            <div class="font-semibold text-gray-900 dark:text-gray-200">{{ $response->user->fullname }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $response->user->email }}</div>
                        @else
                            <span class="text-gray-400 dark:text-gray-500 font-medium italic">ผู้เยี่ยมชม (Guest)</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700 font-semibold">{{ $response->major ?: '-' }}</td>
                    <td class="px-4 py-4 text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700 font-mono text-xs">{{ $response->ip_address ?: '-' }}</td>
                    
                    @php 
                        $answerMap = $response->answers->pluck('score', 'question_id')->toArray();
                    @endphp

                    @foreach($questions->groupBy('category') as $category => $categoryQuestions)
                        @php
                            $scores = [];
                            foreach ($categoryQuestions as $q) {
                                if (isset($answerMap[$q->id])) {
                                    $scores[] = $answerMap[$q->id];
                                }
                            }
                            $avg = count($scores) > 0 ? array_sum($scores) / count($scores) : null;
                        @endphp
                        <td class="px-3 py-4 text-center border-r border-gray-200 dark:border-gray-700">
                            @if($avg !== null)
                                @php
                                    if ($avg >= 4.0) {
                                        $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-300 dark:border-emerald-900/50';
                                    } elseif ($avg >= 3.0) {
                                        $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-300 dark:border-amber-900/50';
                                    } else {
                                        $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/20 dark:text-rose-300 dark:border-rose-900/50';
                                    }
                                @endphp
                                <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full border {{ $badgeClass }}">
                                    {{ number_format($avg, 2) }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500 font-medium">-</span>
                            @endif
                        </td>
                    @endforeach
                    
                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400 max-w-xs truncate border-r border-gray-200 dark:border-gray-700" title="{{ $response->suggestion }}">
                        {{ $response->suggestion ?: '-' }}
                    </td>
                    <td class="px-4 py-4 text-right whitespace-nowrap">
                        <!-- Details Button (A4 format) -->
                        <button type="button" 
                                onclick="openDetailModal({{ $response->id }}, '{{ $response->created_at->format('d/m/Y H:i') }}', '{{ $response->major ?: '-' }}', '{{ $response->ip_address ?: '-' }}', '{{ addslashes($response->suggestion ?: '') }}', '{{ $response->user ? addslashes($response->user->fullname . ' (' . $response->user->email . ')') : 'ผู้เยี่ยมชม (Guest)' }}', {{ json_encode($answerMap) }})"
                                class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 p-2 rounded-lg transition-colors hover:bg-purple-50 dark:hover:bg-purple-950/30 mr-1 cursor-pointer"
                                title="ดูรายงานฉบับ A4">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <!-- Delete Button -->
                        <button onclick="openDeleteModal({{ $response->id }})" class="text-red-400 hover:text-red-600 p-2 rounded-lg transition-colors hover:bg-red-50 dark:hover:bg-red-900/30 cursor-pointer">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
                
                @if(count($responses) == 0)
                <tr>
                    <td colspan="{{ count($questions->groupBy('category')) + 7 }}" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-inbox text-4xl mb-2 text-gray-200"></i>
                            <p>ยังไม่มีผู้ส่งข้อมูลการประเมิน</p>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
</div>

<!-- Category Detail Modal -->
<div id="categoryModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 animate-backdrop">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl overflow-hidden p-6 border border-gray-200 dark:border-gray-700 max-h-[90vh] flex flex-col animate-modal">
        <div class="flex justify-between items-center mb-4 shrink-0">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                <i class="fa-solid fa-chart-bar text-purple-500 mr-2"></i> 
                <span id="categoryModalTitle">รายละเอียดหมวดหมู่</span>
            </h3>
            <button onclick="closeCategoryModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="overflow-y-auto shrink overflow-x-hidden pr-2">
            <table class="w-full text-left border-collapse border border-gray-200 dark:border-gray-700">
                <thead>
                    <tr class="bg-purple-50 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-sm font-bold">
                        <th class="px-4 py-3 border border-gray-200 dark:border-gray-700">ข้อคำถามย่อย</th>
                        <th class="px-4 py-3 border border-gray-200 dark:border-gray-700 text-center w-32">คะแนนเฉลี่ย</th>
                    </tr>
                </thead>
                <tbody id="categoryModalBody" class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                    <!-- Dynamic content -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-sm overflow-hidden p-6 text-center border border-gray-200 dark:border-gray-700">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">ยืนยันการลบข้อมูล?</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">ข้อมูลการตอบนี้จะถูกลบออกจากระบบอย่างถาวร</p>
        <form id="deleteForm" method="POST" class="flex justify-center space-x-3">
            @csrf
            <button type="button" onclick="closeModals()" class="w-full px-4 py-2 text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-medium transition-colors cursor-pointer">ยกเลิก</button>
            <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg cursor-pointer">ยืนยันลบ</button>
        </form>
    </div>
</div>

<!-- Detail Modal (Premium A4 Layout) -->
<div id="detailModal" class="fixed inset-0 z-50 hidden bg-gray-900/70 backdrop-blur-sm flex flex-col justify-start overflow-y-auto p-4 md:p-8">
    <!-- Control buttons row (no-print) -->
    <div class="max-w-[210mm] w-full mx-auto mb-4 flex justify-between items-center no-print shrink-0">
        <button onclick="window.print()" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-bold text-sm transition-colors border border-purple-800 flex items-center shadow-lg cursor-pointer">
            <i class="fa-solid fa-print mr-2"></i> พิมพ์รายงาน (A4)
        </button>
        <button onclick="closeModals()" class="bg-white hover:bg-gray-100 text-gray-800 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white px-5 py-2.5 rounded-lg font-bold text-sm transition-all border border-gray-300 dark:border-gray-600 flex items-center shadow-lg cursor-pointer">
            <i class="fa-solid fa-xmark mr-2"></i> ปิดหน้าต่าง
        </button>
    </div>
    
    <!-- A4 Page Container -->
    <div class="bg-white mx-auto p-12 md:p-16 shadow-2xl border border-gray-300 rounded-sm w-full relative shrink-0" style="max-width: 210mm; min-height: 297mm; box-sizing: border-box; background-color: #ffffff !important; color: #1f2937 !important; opacity: 1 !important; flex-shrink: 0;">
        <!-- Official Header -->
        <div style="border-bottom: 3px double #7e059c; margin-bottom: 25px; padding-bottom: 15px; text-align: center;">
            <h2 class="text-xl font-bold text-gray-800 uppercase tracking-wide">รายงานผลการตอบแบบประเมินความพึงพอใจ</h2>
            <p class="text-sm text-purple-700 font-medium">โครงการพัฒนาระบบเทคโนโลยีสารสนเทศเพื่อการบริหารจัดการข้อมูล (DETA SHIP)</p>
            <p class="text-xs text-gray-500 mt-1 font-semibold">คณะวิทยาการจัดการ มหาวิทยาลัยราชภัฏนครสวรรค์</p>
        </div>
        
        <!-- Metadata Grid -->
        <div class="mb-6 grid grid-cols-2 gap-y-2 gap-x-6 text-sm text-gray-700 p-5 rounded-xl border border-gray-200" style="background-color: #f9fafb !important;">
            <div><strong>รหัสรายการ:</strong> <span id="modalResponseId" class="font-mono text-purple-700 font-bold"></span></div>
            <div><strong>วันที่ส่งประเมิน:</strong> <span id="modalDate" class="font-medium"></span></div>
            <div class="col-span-2"><strong>ผู้ส่งแบบประเมิน:</strong> <span id="modalSender" class="font-semibold text-purple-700"></span></div>
            <div class="col-span-2"><strong>สาขา:</strong> <span id="modalMajor" class="font-semibold"></span></div>
            <div class="col-span-2"><strong>IP Address:</strong> <span id="modalIp" class="font-mono text-xs"></span></div>
        </div>

        <!-- Score Table -->
        <div class="mb-6 overflow-x-auto">
            <table class="w-full text-left border-collapse border border-gray-300 text-[11px]" style="background-color: #ffffff !important;">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-300 text-gray-800 font-bold uppercase tracking-wider" style="background-color: #f9fafb !important;">
                        <th class="px-4 py-2.5 border-r border-gray-300 text-center" width="8%">ข้อ</th>
                        <th class="px-4 py-2.5 border-r border-gray-300">หัวข้อการประเมิน</th>
                        <th class="px-4 py-2.5 border-r border-gray-300 text-center" width="12%">คะแนน</th>
                        <th class="px-4 py-2.5 text-center" width="22%">ผลการประเมิน</th>
                    </tr>
                </thead>
                <tbody id="modalQuestionsTableBody" class="divide-y divide-gray-200" style="background-color: #ffffff !important;">
                    <!-- Dynamically filled by Javascript -->
                </tbody>
            </table>
        </div>

        <!-- Suggestions Box -->
        <div class="p-5 rounded-xl border border-purple-100" style="background-color: #faf5ff !important;">
            <h4 class="text-xs font-bold text-purple-700 mb-2 uppercase tracking-wide flex items-center gap-1.5">
                <i class="fa-solid fa-comment-dots"></i>
                <span>ข้อเสนอแนะเพิ่มเติม</span>
            </h4>
            <p id="modalSuggestion" class="text-xs text-gray-600 whitespace-pre-line leading-relaxed italic"></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const questions = @json($questions->values());
    
    // Initialize Chart.js
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('assessmentChart');
        if (!ctx) return;
        
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);
        
        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'คะแนนเฉลี่ย (เต็ม 5)',
                    data: chartData,
                    backgroundColor: @json($chartColors),
                    borderColor: @json($chartBorderColors),
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6
                }]
            },
            options: {
                onClick: (e, activeElements) => {
                    if (activeElements.length > 0) {
                        const index = activeElements[0].index;
                        const label = chartLabels[index];
                        const details = categoryDetails[label];
                        openCategoryModal(label, details);
                    }
                },
                onHover: (event, chartElement) => {
                    event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                },
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' คะแนนเฉลี่ย: ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Sarabun', 'Inter', sans-serif",
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Initialize Pie Chart
        const pieCtx = document.getElementById('majorPieChart');
        if (pieCtx) {
            const pieLabels = @json($pieLabels);
            const pieData = @json($pieData);
            
            new Chart(pieCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: pieLabels,
                    datasets: [{
                        data: pieData,
                        backgroundColor: @json($pieColors),
                        borderColor: @json($pieBorderColors),
                        borderWidth: 1,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    family: "'Prompt', sans-serif",
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed + ' คน';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    cutout: '50%'
                }
            });
        }
    });
    
    function openDeleteModal(id) {
        let form = document.getElementById('deleteForm');
        form.action = "{{ route('admin.assessment.responses.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function openDetailModal(id, date, major, ip, suggestion, sender, answers) {
        document.getElementById('modalResponseId').textContent = id;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalMajor').textContent = major;
        document.getElementById('modalIp').textContent = ip;
        document.getElementById('modalSender').textContent = sender;
        document.getElementById('modalSuggestion').textContent = suggestion || 'ไม่มีข้อเสนอแนะเพิ่มเติม';
        
        let html = '';
        let qIndex = 1;
        let currentCategory = '';
        
        questions.forEach((q) => {
            if (q.category !== currentCategory) {
                currentCategory = q.category;
                html += `
                    <tr class="font-bold text-[#7e059c]" style="background-color: #f3e8ff !important;">
                        <td colspan="4" class="px-4 py-2 text-xs uppercase tracking-wider font-bold">
                            <i class="fa-solid fa-folder-open mr-1.5 text-purple-500"></i> ${currentCategory || 'ทั่วไป'}
                        </td>
                    </tr>
                `;
            }
            
            const score = answers[q.id] !== undefined ? answers[q.id] : '-';
            let starsHtml = '';
            if (score !== '-') {
                for (let i = 1; i <= 5; i++) {
                    if (i <= score) {
                        starsHtml += '<i class="fa-solid fa-star" style="color: #eab308; font-size: 12px; margin-right: 2px;"></i>';
                    } else {
                        starsHtml += '<i class="fa-regular fa-star" style="color: #d1d5db; font-size: 12px; margin-right: 2px;"></i>';
                    }
                }
            } else {
                starsHtml = '<span class="text-gray-400 font-bold">-</span>';
            }
            
            html += `
                <tr class="hover:bg-gray-50/50 transition-colors text-[11px] text-gray-700" style="background-color: #ffffff !important; color: #374151 !important;">
                    <td class="px-4 py-2 text-center border-r border-gray-200 font-mono">${qIndex++}</td>
                    <td class="px-4 py-2 border-r border-gray-200 whitespace-normal leading-relaxed">${q.question_text}</td>
                    <td class="px-4 py-2 text-center border-r border-gray-200 font-bold text-purple-700 font-mono" style="background-color: #faf5ff !important;">${score}</td>
                    <td class="px-4 py-2 text-center">
                        <div class="flex justify-center gap-0.5">${starsHtml}</div>
                    </td>
                </tr>
            `;
        });
        
        document.getElementById('modalQuestionsTableBody').innerHTML = html;
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeModals() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('detailModal').classList.add('hidden');
    }

    const categoryDetails = @json($categoryDetails);
    
    function openCategoryModal(title, details) {
        document.getElementById('categoryModalTitle').textContent = 'ผลประเมินรายข้อ: ' + title;
        let html = '';
        if (details && details.length > 0) {
            details.forEach((item, index) => {
                html += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 text-gray-700 dark:text-gray-300 transition-colors">
                        <td class="px-4 py-3 border border-gray-200 dark:border-gray-700"><span class="font-mono text-gray-400 mr-2">${index + 1}.</span> ${item.text}</td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-gray-700 text-center font-bold text-purple-600 dark:text-purple-400 font-mono">${item.avg.toFixed(2)}</td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="2" class="px-4 py-8 text-center text-gray-500">ไม่มีข้อมูลการประเมินในหมวดหมู่นี้</td></tr>';
        }
        document.getElementById('categoryModalBody').innerHTML = html;
        document.getElementById('categoryModal').classList.remove('hidden');
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.add('hidden');
    }

    // Close modals on clicking outside modal content
    document.addEventListener('click', function(event) {
        const deleteModal = document.getElementById('deleteModal');
        const detailModal = document.getElementById('detailModal');
        const categoryModal = document.getElementById('categoryModal');
        
        if (deleteModal && !deleteModal.classList.contains('hidden') && event.target === deleteModal) {
            closeModals();
        }
        if (detailModal && !detailModal.classList.contains('hidden') && event.target === detailModal) {
            closeModals();
        }
        if (categoryModal && !categoryModal.classList.contains('hidden') && event.target === categoryModal) {
            closeCategoryModal();
        }
    });
</script>
@endsection
