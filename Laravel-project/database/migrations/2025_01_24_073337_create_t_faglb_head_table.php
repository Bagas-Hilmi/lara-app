<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaglbHeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_faglb_head', function (Blueprint $table) {
            $table->bigIncrements('id_head')->unsigned();
            $table->bigInteger('id_ccb')->unsigned();
            $table->bigInteger('id_capex')->nullable()->unsigned();
            $table->string('period', 7);
            $table->tinyInteger('report_status')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps(0); // Automatically adds 'created_at' and 'updated_at'
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('faglb_filename', 50)->nullable();
            $table->string('zlis1_filename', 50)->nullable();

            // Foreign key constraints
            $table->foreign('id_ccb')
                ->references('id_ccb')
                ->on('t_cip_cum_bal')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('id_capex')
                ->references('id_capex')
                ->on('t_master_capex')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('t_faglb_head');
    }
}
