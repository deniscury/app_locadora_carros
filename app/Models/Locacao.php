<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';

    public function cliente(){
        return $this->belongsTo('App\Models\Cliente');
    } 

    public function carro(){
        return $this->belongsTo('App\Models\Carro');
    } 

    protected $fillable = array(
        'cliente_id', 
        'carro_id', 
        'data_inicio_periodo', 
        'data_final_previsto_periodo', 
        'data_final_realizado_periodo', 
        'valor_diaria',
        'km_inicial',
        'km_final'
    );

    public function rules(){
        return array(
            'cliente_id' => 'exists:clientes,id',
            'carro_id' => 'exists:carros,id',
            'data_inicio_periodo' => 'required|date_format:Y-m-d|after:yesterday',
            'data_final_previsto_periodo' => 'required|date_format:Y-m-d|after:data_inicio_periodo',
            'data_final_realizado_periodo' => 'required|date_format:Y-m-d|after:data_inicio_periodo',
            'valor_diaria' => 'required|numeric',
            'km_inicial' => 'required|integer',
            'km_final' => 'required|integer',
        );
        /**
         * Parâmetros regras UNIQUE
         * 
         * 1) Tabela
         * 2) Nome da Coluna Pesquisada
         * 3) ID do registro desconsiderado da validação
         * 
         */
    }

    public function feedback(){
        return array(
            'required' => 'O campo :attribute é obrigatório',
            'integer' => 'O campo :attribute precisa ser inteiro',
            'numeric' => 'O campo :attribute precisa ser numérico',
            'date_format' => 'A data do campo :attribute é inválida',
            'carro_id.exists' => 'O carro selecionado não existe',
            'cliente_id.exists' => 'O cliente selecionado não existe',
            'data_inicio_periodo.after' => 'Você não pode cadastrar datas anteriores a hoje no campo :attribute',
            'data_final_previsto_periodo.after' => 'Você não pode cadastrar datas anteriores ao dia de ínicio do período',
            'data_final_realizado_periodo.after' => 'Você não pode cadastrar datas anteriores ao dia de ínicio do período',
	    );
    }
}
