<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function locacoes(){
        return $this->hasMany('App\Models\Locacao');
    } 

    public function rules(){
        return array(
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3'
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
            'nome.unique' => 'Marca já existe no sistema.',
            'nome.min' => 'O nome da marca precisa ter no mínimo 3 caracteres.'
	    );
    }

}
