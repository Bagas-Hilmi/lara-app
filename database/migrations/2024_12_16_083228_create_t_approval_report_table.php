<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('t_approval_report', function (Blueprint $table) {
            $table->id('id_approve');  
            $table->string('approver_name'); // Nama yang menyetujui
            $table->string('status'); // Status persetujuan (misalnya: 'approved', 'rejected', 'pending')
            $table->text('comments')->nullable(); // Komentar jika ada
            $table->timestamp('approved_at')->nullable(); // Waktu persetujuan
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_approval_report');
    }
};
