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
        Schema::create('t_cip_cum_bal', function (Blueprint $table) {
            $table->bigIncrements('id_ccb');
            $table->string('period_cip', 7);
            $table->decimal('bal_usd', 15, 2)->nullable();
            $table->decimal('bal_rp', 15, 2)->nullable();
            $table->decimal('cumbal_usd', 15, 2)->nullable();
            $table->decimal('cumbal_rp', 15, 2)->nullable();
            $table->tinyInteger('report_status')->default(0); // 0: belum diexport, 1: sudah diexport
            $table->tinyInteger('status')->default(1); // 1: aktif, 0: dihapus
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_cip_cum_bal');
    }
};