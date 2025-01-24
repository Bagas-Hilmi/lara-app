<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapexPoreleaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_capex_porelease', function (Blueprint $table) {
            $table->bigIncrements('id_capex_porelease')->unsigned();
            $table->bigInteger('id_capex')->nullable()->unsigned();
            $table->text('description')->nullable();
            $table->bigInteger('PO_release')->nullable()->unsigned();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->timestamps(0);  // Automatically adds 'created_at' and 'updated_at'
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();

            // Foreign keys
            $table->foreign('id_capex')
                ->references('id_capex')
                ->on('t_master_capex')
                ->onUpdate('restrict')
                ->onDelete('restrict');

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
        Schema::dropIfExists('t_capex_porelease');
    }
}
