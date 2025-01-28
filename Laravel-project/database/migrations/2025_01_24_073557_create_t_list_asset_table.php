<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_list_asset', function (Blueprint $table) {
            $table->bigIncrements('id_asset')->unsigned();
            $table->bigInteger('id_capex')->nullable()->unsigned();
            $table->bigInteger('no')->nullable()->unsigned();
            $table->string('cost_center', 255)->nullable();
            $table->string('tag_number', 255)->nullable();
            $table->string('asset_number', 255)->nullable();
            $table->string('asset_number_2', 255)->nullable();
            $table->char('asset_class_id', 1)->nullable();
            $table->char('asset_class_name', 1)->nullable();
            $table->integer('life_k', 11)->nullable();
            $table->integer('life_f', 11)->nullable();
            $table->string('asset_name', 255)->nullable();
            $table->char('plant', 50)->nullable();
            $table->char('qty', 50)->nullable();
            $table->char('uom', 50)->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->timestamps(0);

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
        Schema::dropIfExists('t_list_asset');
    }
}
