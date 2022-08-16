<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function marca(){
        return $this->belongsTo('App\Models\Marca');
    }

    public function carros(){
        return $this->hasMany('App\Models\Carro');
    }

    public function rules(){
        return array(
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpg,jpeg',
            'numero_portas' => 'required|integer|between:1,5',
            'lugares' => 'required|integer|between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
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
            'nome.unique' => 'Modelo já existe no sistema.',
            'nome.min' => 'O nome do modelo precisa ter no mínimo 3 caracteres.',
            'imagem.mimes' => 'A imagem precisa ter um dos seguintes formatos: PNG, JPG, JPEG',
            'marca_id.exists' => 'A marca selecionada não existe',
            'numero_portas.between' => 'O número de portas deve estar entre 1 e 5.',
            'lugares.between' => 'O número de portas deve estar entre 1 e 20.'
	    );
    }
}
