<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_report_category', function (Blueprint $table) {
            $table->bigIncrements('id_report_category')->unsigned();
            $table->bigInteger('id_capex')->nullable()->unsigned();
            $table->char('category', 50)->nullable();
            $table->string('project', 255)->nullable();
            $table->string('number', 50)->nullable();
            $table->decimal('carried_over', 15, 2)->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('actual_ytd', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->timestamps(0);
            $table->integer('budget')->nullable();
            $table->integer('unbudget')->nullable();

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
        Schema::dropIfExists('t_report_category');
    }
}
