<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Carro;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{
    protected $carro;

    public function getCarro(){
        return $this->carro;
    }

    public function setCarro(Carro $carro){
        $this->carro = $carro;
    }

    public function __construct(Carro $carro){
        $this->setCarro($carro);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carros = array();
        $atributos_modelo = 'modelo';
        $atributos_locacoes = 'locacoes';

        $carro_repository = new CarroRepository($this->getCarro());

        if ($request->has('atributos_modelo')){
            $atributos_modelo = $atributos_modelo.':id,'.$request->atributos_modelo;
        }
        
        $carro_repository->selectRelacionados($atributos_modelo);

        if ($request->has('atributos_locacoes')){
            $atributos_locacoes = $atributos_locacoes.':carro_id,'.$request->atributos_locacoes;
        }
        
        $carro_repository->selectRelacionados($atributos_locacoes);

        if ($request->has('filtros')){
            $carro_repository->filtrar($request->filtros);
        }

        if ($request->has('atributos')){
            $atributos = 'id,modelo_id,'.$request->atributos;
            $carro_repository->selectAtributos($atributos);
        }

        $carros = $carro_repository->getModel()->get();

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $carros
            ),
            200
        );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->getCarro()->rules(), $this->getCarro()->feedback());

        $params = array(
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel, 
            'km' => $request->km
        );

        $carro = $this->getCarro()->create($params);

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $carro
            ),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->getCarro()->with('modelo')->find($id);

        if ($carro === null){            
            return response()->json(
                array(
                    'erro' => true,
                    'msg' => 'Carro não encontrado.'
                ),
                404
            );
        }

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $carro
            ),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->getCarro()->find($id);

        if ($carro === null){
            return response()->json(
                array(
                    'erro' => true,
                    'msg' => 'Carro não encontrado.'
                ),
                404
            );
        }

        if ($request->method() === 'PATCH'){
            $regras = array();
            $regras_carro = $carro->rules();

            foreach($regras_carro as $input => $regra){
                if (array_key_exists($input, $request->all())){
                    $regras[$input] = $regra;
                }
            }

            $request->validate($regras, $carro->feedback());
        }
        else{
            $request->validate($carro->rules(), $carro->feedback());
        }
        
        $carro->fill($request->all());
        $carro->save();

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $carro
            ),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->getCarro()->find($id);

        if ($carro === null){
            return response()->json(
                array(
                    'erro' => true,
                    'msg' => 'Carro não encontrado.'
                ),
                404
            );
        }
        
        $carro->delete();
        
        return response()->json(
            array(
                'erro' => false,
                'retorno' => [
                    'msg' => 'Carro excluído com sucesso.'
                ]
            ),
            200
        );
    }
}
