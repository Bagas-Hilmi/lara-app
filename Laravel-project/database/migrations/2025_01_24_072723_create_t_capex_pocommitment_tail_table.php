<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCapexPocommitmentTailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_capex_pocommitment_tail', function (Blueprint $table) {
            $table->id('id_capex_pocommitment');
            $table->unsignedBigInteger('id_capex_porelease')->nullable()->default(null);
            $table->unsignedBigInteger('id_capex')->nullable()->default(null);
            $table->string('purchasing_doc', 255)->nullable()->default(null);
            $table->string('reference_item', 255)->nullable()->default(null);
            $table->date('doc_date')->nullable()->default(null);
            $table->year('fiscal_year')->nullable()->default(null);
            $table->string('no_material', 255)->nullable()->default(null);
            $table->string('material_desc', 255)->nullable()->default(null);
            $table->string('qty', 255)->nullable()->default(null);
            $table->string('uom', 255)->nullable()->default(null);
            $table->bigInteger('value_trancurr')->nullable()->default(null);
            $table->char('tcurr', 50)->nullable()->default(null);
            $table->decimal('valuein_obj', 15, 2)->nullable()->default(null);
            $table->string('cost_element', 255)->nullable()->default(null);
            $table->string('wbs', 50)->nullable()->default(null);
            $table->tinyInteger('status')->nullable()->default(1);
            $table->timestamps(0); // created_at dan updated_at
            $table->unsignedBigInteger('created_by')->nullable()->default(null);
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);

            $table->primary('id_capex_pocommitment');
            $table->index('id_capex_porelease');
            $table->index('id_capex');
            $table->index('created_by');
            $table->index('updated_by');

            // Foreign Key Constraints
            $table->foreign('id_capex')
                ->references('id_capex')
                ->on('t_master_capex')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreign('id_capex_porelease')
                ->references('id_capex_porelease')
                ->on('t_capex_porelease')
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
        Schema::dropIfExists('t_capex_pocommitment_tail');
    }
}
