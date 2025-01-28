<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaglbTailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_faglb_tail', function (Blueprint $table) {
            $table->bigIncrements('id_tail')->unsigned();
            $table->bigInteger('id_head')->unsigned();
            $table->string('asset', 255)->nullable();
            $table->integer('sub_number', 11)->nullable();
            $table->date('posting_date')->nullable();
            $table->string('document_number', 255)->nullable();
            $table->bigInteger('reference_key', 255)->nullable();
            $table->string('material', 255)->nullable();
            $table->string('business_area', 255)->nullable();
            $table->integer('quantity', 11)->nullable();
            $table->string('base_unit_of_measure', 10)->nullable();
            $table->string('document_type', 5)->nullable();
            $table->integer('posting_key', 11)->nullable();
            $table->string('document_currency', 5)->nullable();
            $table->decimal('amount_in_doc_curr', 15, 2)->nullable();
            $table->string('local_currency', 5)->nullable();
            $table->decimal('amount_in_lc', 15, 2)->nullable();
            $table->string('local_currency_2', 5)->nullable();
            $table->decimal('amount_in_loc_curr_2', 15, 2)->nullable();
            $table->text('text')->nullable();
            $table->bigInteger('assignment', 20)->nullable();
            $table->string('profit_center', 255)->nullable();
            $table->string('wbs_element', 255)->nullable();
            $table->tinyInteger('status')->nullable()->default(1);

            // Foreign key constraint
            $table->foreign('id_head')
                ->references('id_head')
                ->on('t_faglb_head')
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
        Schema::dropIfExists('t_faglb_tail');
    }
}
