<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportCipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_report_cip', function (Blueprint $table) {
            $table->bigIncrements('id_report_cip')->unsigned();
            $table->bigInteger('id_capex')->nullable()->unsigned();
            $table->bigInteger('id_head')->nullable()->unsigned();
            $table->bigInteger('fa_doc')->nullable();
            $table->string('date', 10)->nullable();
            $table->string('settle_doc', 50)->nullable();
            $table->string('material', 50)->nullable();
            $table->text('description')->nullable();
            $table->decimal('qty', 10, 2)->nullable();
            $table->char('uom', 50)->nullable();
            $table->decimal('amount_rp', 15, 2)->nullable();
            $table->decimal('amount_us', 15, 2)->default(0.00);
            $table->tinyInteger('status')->default(1)->nullable();
            $table->timestamps(0);

            // Foreign key constraints
            $table->foreign('id_head')
                ->references('id_head')
                ->on('t_faglb_head')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('id_capex')
                ->references('id_capex')
                ->on('t_master_capex')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_report_cip');
    }
}
