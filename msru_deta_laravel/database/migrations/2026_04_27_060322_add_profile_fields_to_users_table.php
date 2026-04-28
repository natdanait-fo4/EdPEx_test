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
        Schema::table('users', function (Blueprint $table) {
            $table->string('fullname')->nullable()->after('username');
            $table->string('student_id')->nullable()->after('fullname');
            $table->string('major')->nullable()->after('student_id');
            $table->string('phone')->nullable()->after('major');
            $table->text('address')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fullname', 'student_id', 'major', 'phone', 'address']);
        });
    }
};
