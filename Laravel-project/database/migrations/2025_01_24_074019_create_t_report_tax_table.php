<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_report_tax', function (Blueprint $table) {
            $table->bigIncrements('id_report_tax')->unsigned();
            $table->bigInteger('id_capex')->nullable()->unsigned();
            $table->string('project_desc', 255)->nullable();
            $table->string('capex_number', 255)->nullable();
            $table->string('cip_number', 255)->nullable();
            $table->string('wbs_number', 255)->nullable();
            $table->string('material', 50)->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('total_budget')->nullable();
            $table->decimal('qty', 10, 2)->nullable();
            $table->char('uom', 50)->nullable();
            $table->decimal('amount_rp', 15, 2)->nullable();
            $table->decimal('amount_us', 15, 2)->default(0.00);
            $table->string('date', 10)->nullable();
            $table->string('settle_doc', 50)->nullable();
            $table->bigInteger('fa_doc')->nullable();
            $table->string('status_capex', 255)->nullable();
            $table->string('cap_date', 50)->nullable();
            $table->string('cap_doc', 255)->nullable();
            $table->longText('no_asset')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps(0);

            // Foreign key constraint
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
        Schema::dropIfExists('t_report_tax');
    }
}
