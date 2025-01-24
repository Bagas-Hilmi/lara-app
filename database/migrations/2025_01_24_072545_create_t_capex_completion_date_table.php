<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCapexCompletionDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_capex_completion_date', function (Blueprint $table) {
            $table->id('id_capex_completion');
            $table->unsignedBigInteger('id_capex_budget')->nullable()->default(null);
            $table->unsignedBigInteger('id_capex')->nullable()->default(null);
            $table->text('date')->nullable()->default(null);
            $table->tinyInteger('status')->nullable()->default(1);
            $table->timestamps(0); // created_at dan updated_at
            $table->unsignedBigInteger('created_by')->nullable()->default(null);
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);

            $table->primary('id_capex_completion');
            $table->index('id_capex');
            $table->index('id_capex_budget');

            // Foreign Key Constraints
            $table->foreign('id_capex')
                ->references('id_capex')
                ->on('t_master_capex')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('id_capex_budget')
                ->references('id_capex_budget')
                ->on('t_capex_budget')
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
        Schema::dropIfExists('t_capex_completion_date');
    }
}
