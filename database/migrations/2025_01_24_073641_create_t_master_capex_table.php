<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterCapexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_master_capex', function (Blueprint $table) {
            $table->bigIncrements('id_capex')->unsigned();
            $table->string('project_desc', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('wbs_capex', 255)->nullable();
            $table->string('remark', 255)->nullable();
            $table->string('request_number', 255)->nullable();
            $table->string('requester', 255)->nullable();
            $table->string('capex_number', 255)->nullable();
            $table->bigInteger('amount_budget')->nullable()->unsigned();
            $table->bigInteger('budget_cos')->nullable()->unsigned();
            $table->bigInteger('total_budget')->nullable()->unsigned();
            $table->bigInteger('PO_release')->nullable()->unsigned();
            $table->string('budget_type', 255)->nullable();
            $table->string('status_capex', 255)->nullable();
            $table->string('startup', 10)->nullable();
            $table->string('expected_completed', 10)->nullable();
            $table->integer('days_remaining', 11)->nullable();
            $table->integer('days_late', 11)->nullable();
            $table->string('revise_completion_date', 10)->nullable();
            $table->string('wbs_number', 255)->nullable();
            $table->string('cip_number', 255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('file_asset', 255)->nullable();
            $table->string('cap_date', 255)->nullable();
            $table->string('cap_doc', 255)->nullable();
            $table->longText('no_asset')->nullable();
            $table->timestamps(0);

            // Foreign key constraints
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_master_capex');
    }
}
