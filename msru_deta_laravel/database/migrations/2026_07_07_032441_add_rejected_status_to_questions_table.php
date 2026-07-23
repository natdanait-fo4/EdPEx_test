<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ปรับปรุงฟิลด์ status ในตาราง questions ให้รองรับตัวเลือก 'rejected' เพิ่มเติม
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE questions MODIFY COLUMN status ENUM('waiting', 'answered', 'rejected') DEFAULT 'waiting'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // กรณีถอยการทำงานกลับ ให้ปรับเหลือเพียง 'waiting' และ 'answered' ตามเดิม
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE questions MODIFY COLUMN status ENUM('waiting', 'answered') DEFAULT 'waiting'");
    }
};
