@extends('layouts.admin')

@section('title', 'ผลการประเมิน | Admin Dashboard')
@section('header_title', 'รายละเอียดผลการประเมิน')


@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">ข้อมูลการส่งประเมินทั้งหมด</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">รายละเอียดคำตอบแยกตามหัวข้อที่ผู้ใช้บริการส่งเข้ามาในระบบ</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.assessment.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors border border-green-800 flex items-center shadow-sm">
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

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px] border border-gray-200 dark:border-gray-700">
            <thead>
                <tr class="bg-purple-50 dark:bg-purple-900/30 border-b border-purple-100 dark:border-purple-800 text-[#7e059c] dark:text-purple-300 text-[10px] uppercase tracking-wider font-bold">
                    <th class="px-4 py-2 text-center border-r border-purple-100 dark:border-purple-800" colspan="2">ข้อมูลการส่ง</th>
                    @foreach($questions->groupBy('category') as $category => $categoryQuestions)
                    <th class="px-2 py-2 text-center border-r border-purple-100 dark:border-purple-800" colspan="{{ $categoryQuestions->count() }}">
                        <i class="fa-solid fa-folder-open mr-1 text-[12px]"></i> {{ $category ?: 'ทั่วไป' }}
                    </th>
                    @endforeach
                    <th class="px-4 py-2 text-center" colspan="2">เพิ่มเติม</th>
                </tr>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-[10px] uppercase">
                    <th class="px-4 py-3 font-medium text-center border-r border-gray-200 dark:border-gray-700" width="60">ลำดับ</th>
                    <th class="px-4 py-3 font-medium border-r border-gray-200 dark:border-gray-700">วันที่ส่ง</th>
                    
                    @php $qIndex = 1; @endphp
                    @foreach($questions->groupBy('category') as $category => $categoryQuestions)
                        @foreach($categoryQuestions as $question)
                        <th class="px-2 py-3 font-medium text-center border-r border-gray-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50" style="min-width: 45px;" title="{{ $question->question_text }}">
                            Q{{ $qIndex++ }}
                        </th>
                        @endforeach
                    @endforeach
                    
                    <th class="px-4 py-3 font-medium border-r border-gray-200 dark:border-gray-700">ข้อเสนอแนะ</th>
                    <th class="px-4 py-3 font-medium text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 italic-score">
                @foreach($responses as $response)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors text-sm">
                    <td class="px-4 py-4 text-center text-gray-400 dark:text-gray-500 border-r border-gray-200 dark:border-gray-700">{{ $loop->iteration }}</td>
                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700 whitespace-nowrap">{{ $response->created_at->format('d/m/Y H:i') }}</td>
                    
                    @php 
                        $answerMap = $response->answers->pluck('score', 'question_id')->toArray();
                    @endphp

                    @foreach($questions->groupBy('category') as $category => $categoryQuestions)
                        @foreach($categoryQuestions as $question)
                        <td class="px-2 py-4 text-center border-r border-gray-200 dark:border-gray-700 group">
                            <span class="inline-block w-8 h-8 leading-8 rounded-full transition-all group-hover:scale-110 {{ isset($answerMap[$question->id]) ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 font-bold shadow-sm' : 'bg-gray-50 dark:bg-gray-700 text-gray-300 dark:text-gray-500' }}">
                                {{ $answerMap[$question->id] ?? '-' }}
                            </span>
                        </td>
                        @endforeach
                    @endforeach
                    
                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400 max-w-xs truncate border-r border-gray-200 dark:border-gray-700" title="{{ $response->suggestion }}">
                        {{ $response->suggestion ?: '-' }}
                    </td>
                    <td class="px-4 py-4 text-right">
                        <button onclick="openDeleteModal({{ $response->id }})" class="text-red-400 hover:text-red-600 p-2 rounded-lg transition-colors hover:bg-red-50 dark:hover:bg-red-900/30">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
                
                @if(count($responses) == 0)
                <tr>
                    <td colspan="{{ count($questions) + 4 }}" class="px-6 py-12 text-center text-gray-500">
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

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-sm overflow-hidden p-6 text-center border border-gray-200 dark:border-gray-700">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">ยืนยันการลบข้อมูล?</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">ข้อมูลการตอบนี้จะถูกลบออกจากระบบอย่างถาวร</p>
        <form id="deleteForm" method="POST" class="flex justify-center space-x-3">
            @csrf
            <button type="button" onclick="closeModals()" class="w-full px-4 py-2 text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-medium transition-colors">ยกเลิก</button>
            <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg">ยืนยันลบ</button>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(id) {
        let form = document.getElementById('deleteForm');
        form.action = "{{ route('admin.assessment.responses.destroy', 'REPLACE_ID') }}".replace('REPLACE_ID', id);
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeModals() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
