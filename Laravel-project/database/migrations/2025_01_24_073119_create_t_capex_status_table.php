<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapexStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_capex_status', function (Blueprint $table) {
            $table->bigIncrements('id_capex_status')->unsigned();
            $table->bigInteger('id_capex')->nullable()->unsigned();
            $table->string('status', 50)->nullable();
            $table->timestamps(0);  // Automatically adds 'created_at' and 'updated_at'
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();

            // Foreign key
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
        Schema::dropIfExists('t_capex_status');
    }
}
