<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_report_summary', function (Blueprint $table) {
            $table->bigIncrements('id_report_summary')->unsigned();
            $table->bigInteger('id_capex')->nullable()->unsigned();
            $table->string('project_desc', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('wbs_capex', 255)->nullable();
            $table->string('remark', 255)->nullable();
            $table->string('request_number', 255)->nullable();
            $table->string('requester', 255)->nullable();
            $table->string('capex_number', 255)->nullable();
            $table->bigInteger('amount_budget')->nullable();
            $table->bigInteger('budget_cos')->nullable();
            $table->bigInteger('total_budget')->nullable();
            $table->decimal('recost_rp', 15, 2)->nullable();
            $table->decimal('recost_usd', 15, 2)->nullable();
            $table->bigInteger('PO_release')->nullable();
            $table->decimal('realized', 15, 2)->nullable();
            $table->string('budget_type', 255)->nullable();
            $table->string('status_capex', 255)->nullable();
            $table->string('startup', 10)->nullable();
            $table->string('expected_completed', 10)->nullable();
            $table->integer('days_remaining')->nullable();
            $table->integer('days_late')->nullable();
            $table->string('revise_completion_date', 10)->nullable();
            $table->string('wbs_number', 255)->nullable();
            $table->string('cip_number', 255)->nullable();
            $table->timestamps(0);
            $table->integer('status')->nullable();
            $table->decimal('interest', 15, 2)->nullable();

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
        Schema::dropIfExists('t_report_summary');
    }
}
