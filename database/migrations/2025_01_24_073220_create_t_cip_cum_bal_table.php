<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCipCumBalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_cip_cum_bal', function (Blueprint $table) {
            $table->bigIncrements('id_ccb')->unsigned();
            $table->string('period_cip', 7);
            $table->decimal('bal_usd', 15, 2)->nullable();
            $table->decimal('bal_rp', 15, 2)->nullable();
            $table->decimal('cumbal_usd', 15, 2)->nullable();
            $table->decimal('cumbal_rp', 15, 2)->nullable();
            $table->tinyInteger('report_status')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps(0); // Automatically adds 'created_at' and 'updated_at'
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->timestamp('deleted_at')->nullable();

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
        Schema::dropIfExists('t_cip_cum_bal');
    }
}
