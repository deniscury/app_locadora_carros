<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;

class ModeloController extends Controller
{
    protected $modelo;

    public function getModelo(){
        return $this->modelo;
    }

    public function setModelo(Modelo $modelo){
        $this->modelo = $modelo;
    }

    public function __construct(Modelo $modelo){
        $this->setModelo($modelo);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelos = array();
        $atributos_marca = 'marca';
        $atributos_carros = 'carros';

        $modelo_repository = new ModeloRepository($this->getModelo());

        if ($request->has('atributos_marca')){
            $atributos_marca = $atributos_marca.':id,'.$request->atributos_marca;
        }
        
        $modelo_repository->selectRelacionados($atributos_marca);

        if ($request->has('atributos_carros')){
            $atributos_carros = $atributos_carros.':modelo_id,'.$request->atributos_carros;
        }
        
        $modelo_repository->selectRelacionados($atributos_carros);

        if ($request->has('filtros')){
            $modelo_repository->filtrar($request->filtros);
        }

        if ($request->has('atributos')){
            $atributos = 'id,marca_id,'.$request->atributos;
            $modelo_repository->selectAtributos($atributos);
        }

        return response()->json($modelo_repository->getResultadoPaginado(3), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->getModelo()->rules(), $this->getModelo()->feedback());

        $img = $request->file('imagem');
        $imagem_urn = $img->store('imagens/modelos', 'public');

        $params = array(
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas, 
            'lugares' => $request->lugares, 
            'air_bag' => $request->air_bag, 
            'abs' => $request->abs
        );

        $modelo = $this->getModelo()->create($params);
        
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->getModelo()->with('marca')->with('carros')->find($id);
        
        if ($modelo === null){            
            return response()->json(
                array(
                    'erro' => 'Modelo não encontrado.'
                ), 
            404);
        }
        
        return response()->json($modelo, 200);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->getModelo()->find($id);
        
        if ($modelo === null){            
            return response()->json(
                array(
                    'erro' => 'Modelo não encontrado.'
                ), 
            404);
        }

        if ($request->method() === 'PATCH'){
            $regras = array();
            $regras_modelo = $modelo->rules();

            foreach($regras_modelo as $input => $regra){
                if (array_key_exists($input, $request->all())){
                    $regras[$input] = $regra;
                }
            }

            $request->validate($regras, $modelo->feedback());
        }
        else{
            $request->validate($modelo->rules(), $modelo->feedback());
        }
        
        $img = $request->file('imagem');

        if ($img !== null){
            Storage::disk('public')->delete($modelo->imagem);

            $imagem_urn = $img->store('imagens/modelos', 'public');
        }
        
        $modelo->fill($request->all());
        $modelo->imagem = isset($imagem_urn)?$imagem_urn:$modelo->imagem;

        $modelo->save();
        
        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->getModelo()->find($id);
        
        if ($modelo === null){            
            return response()->json(
                array(
                    'erro' => 'Modelo não encontrado.'
                ), 
            404);
        }

        Storage::disk('public')->delete($modelo->imagem);
        
        $modelo->delete();
        
        return response()->json(
            array(
                'msg' => 'Modelo excluído com sucesso.'
            ),
        200);
    }
}
