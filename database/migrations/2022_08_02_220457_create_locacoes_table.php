<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('carro_id');
            $table->date('data_inicio_periodo');
            $table->date('data_final_previsto_periodo');
            $table->date('data_final_realizado_periodo');
            $table->float('valor_diaria', 8, 2);
            $table->integer('km_inicial');
            $table->string('km_final');
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('carro_id')->references('id')->on('carros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locacoes', function(Blueprint $table){
            $table->dropForeign('locacoes_cliente_id_foreign');
            $table->dropForeign('locacoes_carro_id_foreign');
        });

        Schema::dropIfExists('locacoes');
    }
}
