<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{
    protected $cliente;

    public function getCliente(){
        return $this->cliente;
    }

    public function setCliente(Cliente $cliente){
        $this->cliente = $cliente;
    }

    public function __construct(Cliente $cliente){
        $this->setCliente($cliente);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientes = array();
        $atributos_locacoes = 'locacoes';
    
        $cliente_repository = new ClienteRepository($this->getCliente());

        if ($request->has('atributos_locacoes')){
            $atributos_locacoes = $atributos_locacoes.':cliente_id,'.$request->atributos_locacoes;
        }
        
        $cliente_repository->selectRelacionados($atributos_locacoes);

        if ($request->has('filtros')){
            $cliente_repository->filtrar($request->filtros);
        }

        if ($request->has('atributos')){
            $atributos = 'id,'.$request->atributos;
            $cliente_repository->selectAtributos($atributos);
        }

        return response()->json($cliente_repository->getResultadoPaginado(3), 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->getCliente()->rules(), $this->getCliente()->feedback());

        $params = array(
            'nome' => $request->nome,
        );

        $cliente = $this->getCliente()->create($params);

        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->getCliente()->find($id);

        if ($cliente === null){            
            return response()->json(
                array(
                    'erro' => 'Cliente não encontrado.'
                ), 
            404);
        }

        return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->getCliente()->find($id);

        if ($cliente === null){            
            return response()->json(
                array(
                    'erro' => 'Cliente não encontrado.'
                ), 
            404);
        }

        if ($request->method() === 'PATCH'){
            $regras = array();
            $regras_cliente = $cliente->rules();

            foreach($regras_cliente as $input => $regra){
                if (array_key_exists($input, $request->all())){
                    $regras[$input] = $regra;
                }
            }

            $request->validate($regras, $cliente->feedback());
        }
        else{
            $request->validate($cliente->rules(), $cliente->feedback());
        }
        
        $cliente->fill($request->all());
        $cliente->save();

        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->getCliente()->find($id);

        if ($cliente === null){            
            return response()->json(
                array(
                    'erro' => 'Cliente não encontrado.'
                ), 
            404);
        }
        
        $cliente->delete();
        
        return response()->json(
            array(
                'msg' => 'Cliente excluído com sucesso.'
            ),
        200);
    }
}
