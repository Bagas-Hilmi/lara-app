<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCapexEngineerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_capex_engineer', function (Blueprint $table) {
            $table->id('id_engineer');
            $table->unsignedBigInteger('id_capex')->nullable()->default(null);
            $table->string('nama', 50)->nullable()->default(null);
            $table->timestamps(0); // created_at dan updated_at
            $table->unsignedBigInteger('created_by')->nullable()->default(null);
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);

            $table->primary('id_engineer');
            $table->index('id_capex');

            // Foreign Key Constraints
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
        Schema::dropIfExists('t_capex_engineer');
    }
}
