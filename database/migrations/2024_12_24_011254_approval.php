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
        Schema::table('t_approval_report', function (Blueprint $table) {
            $table->string('wbs_type')->nullable();
            $table->boolean('engineering_production')->default(false);
            $table->boolean('maintenance')->default(false);
            $table->boolean('outstanding_inventory')->default(false);
            $table->boolean('material')->default(false);
            $table->boolean('jasa')->default(false);
            $table->boolean('etc')->default(false);
            $table->boolean('warehouse_received')->default(false);
            $table->boolean('user_received')->default(false);
            $table->boolean('berita_acara')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('t_approval_report', function (Blueprint $table) {
            $table->dropColumn([
                'wbs_type',
                'engineering_production',
                'maintenance',
                'outstanding_inventory',
                'material',
                'jasa',
                'etc',
                'warehouse_received',
                'user_received',
                'berita_acara'
            ]);
        });
    }
};
