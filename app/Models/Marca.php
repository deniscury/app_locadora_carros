<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    public function modelos(){
        return $this->hasMany('App\Models\Modelo');        
    }

    public function rules(){
        return array(
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpg,jpeg'
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
            'nome.min' => 'O nome da marca precisa ter no mínimo 3 caracteres.',
            'imagem.mimes' => 'A imagem precisa ter um dos seguintes formatos: PNG, JPG, JPEG'
	    );
    }
}
