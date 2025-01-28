<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTApprovalReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_approval_report', function (Blueprint $table) {
            $table->id('id_approve')->unsignedBigInteger()->autoIncrement();
            $table->unsignedBigInteger('id_capex')->nullable()->default(null);
            $table->string('requester');
            $table->string('project_desc', 50)->nullable()->default(null);
            $table->string('wbs_capex')->nullable()->default(null);
            $table->string('status_capex', 50)->nullable()->default(null);
            $table->string('file_pdf')->nullable()->default(null);
            $table->tinyInteger('status')->default(1);
            $table->string('upload_by')->nullable()->default(null);
            $table->timestamp('upload_date')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('signature_detail_file')->nullable()->default(null);
            $table->string('signature_closing_file')->nullable()->default(null);
            $table->string('signature_acceptance')->nullable()->default(null);
            $table->tinyInteger('status_approve_1')->nullable()->default(0);
            $table->tinyInteger('status_approve_2')->nullable()->default(0);
            $table->tinyInteger('status_approve_3')->nullable()->default(0);
            $table->tinyInteger('status_approve_4')->nullable()->default(0);
            $table->string('approved_by_admin_1')->nullable()->default(null);
            $table->timestamp('approved_at_admin_1')->nullable()->default(null);
            $table->string('approved_by_admin_2')->nullable()->default(null);
            $table->timestamp('approved_at_admin_2')->nullable()->default(null);
            $table->string('approved_by_user')->nullable()->default(null);
            $table->timestamp('approved_at_user')->nullable()->default(null);
            $table->string('approved_by_engineer')->nullable()->default(null);
            $table->timestamp('approved_at_engineer')->nullable()->default(null);
            $table->string('capex_number')->nullable()->default(null);
            $table->string('wbs_number')->nullable()->default(null);
            $table->string('expected_completed', 50)->nullable()->default(null);
            $table->string('startup', 50)->nullable()->default(null);
            $table->string('cip_number')->nullable()->default(null);
            $table->string('file_sap')->nullable()->default(null);
            $table->string('wbs_type')->nullable()->default(null);
            $table->string('remark')->nullable()->default(null);
            $table->tinyInteger('engineering_production')->default(0);
            $table->tinyInteger('maintenance')->default(0);
            $table->tinyInteger('outstanding_inventory')->default(0);
            $table->tinyInteger('material')->default(0);
            $table->tinyInteger('jasa')->default(0);
            $table->tinyInteger('etc')->default(0);
            $table->tinyInteger('warehouse_received')->default(0);
            $table->tinyInteger('user_received')->default(0);
            $table->tinyInteger('berita_acara')->default(0);
            $table->unsignedBigInteger('total_budget')->nullable()->default(null);
            $table->unsignedBigInteger('amount_budget')->nullable()->default(null);
            $table->string('budget_type')->nullable()->default(null);
            $table->integer('time_delay')->nullable()->default(null);
            $table->string('reason')->nullable()->default(null);

            $table->primary('id_approve');
            $table->foreign('id_capex')->references('id_capex')->on('t_master_capex')
                ->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_approval_report');
    }
}
