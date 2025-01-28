<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZlis1TailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_zlis1_tail', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('id_head')->unsigned();
            $table->string('wbs_element', 50)->nullable();
            $table->string('network', 20)->nullable();
            $table->string('document_number', 20)->nullable();
            $table->string('company_code', 10)->nullable();
            $table->year('fiscal_year')->nullable();
            $table->integer('item', false, true)->nullable();
            $table->string('material_document', 20)->nullable();
            $table->integer('material_doc_year', false, true)->nullable();
            $table->string('material', 20)->nullable();
            $table->string('description', 255)->nullable();
            $table->decimal('quantity', 15, 3)->nullable();
            $table->string('base_unit_of_measure', 10)->nullable();
            $table->decimal('value_tran_curr_1', 15, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->decimal('value_tran_curr_2', 15, 2)->nullable();
            $table->string('currency_2', 10)->nullable();
            $table->decimal('value_tran_curr_3', 15, 2)->nullable();
            $table->string('currency_3', 10)->nullable();
            $table->date('document_date')->nullable();
            $table->date('posting_date')->nullable();
            $table->string('purchasing_document', 20)->nullable();
            $table->string('supplier', 20)->nullable();
            $table->string('name_1', 255)->nullable();
            $table->string('asset', 20)->nullable();
            $table->string('sub_number', 10)->nullable();
            $table->string('cost_center', 20)->nullable();
            $table->string('gl_account', 20)->nullable();
            $table->string('document_number_2', 20)->nullable();
            $table->string('company_code_2', 10)->nullable();
            $table->string('fiscal_year_2', 50)->nullable();
            $table->date('document_date_2')->nullable();
            $table->date('posting_date_2')->nullable();
            $table->string('user_name', 50)->nullable();
            $table->string('reversed_with', 50)->nullable();
            $table->string('wbs_level_2', 50)->nullable();
            $table->string('wbs_element_2', 50)->nullable();
            $table->tinyInteger('status')->default(1);

            // Foreign key constraint
            $table->foreign('id_head')
                ->references('id_head')
                ->on('t_faglb_head')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_zlis1_tail');
    }
}
