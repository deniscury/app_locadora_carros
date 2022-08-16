<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function modelo(){
        return $this->belongsTo('App\Models\Modelo');
    } 

    public function locacoes(){
        return $this->hasMany('App\Models\Locacao');
    } 

    public function rules(){
        return array(
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required|unique:carros,placa,'.$this->id,
            'km' => 'required|integer',
            'disponivel' => 'required|boolean',
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
            'boolean' => 'O campo :attribute precisa ser TRUE ou FALSE',
            'placa.unique' => 'Placa já existe no sistema.',
            'modelo_id.exists' => 'O modelo selecionado não existe',
	    );
    }
}
