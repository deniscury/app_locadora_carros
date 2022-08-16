<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{
    protected $locacao;

    public function getLocacao(){
        return $this->locacao;
    }

    public function setLocacao(Locacao $locacao){
        $this->locacao = $locacao;
    }

    public function __construct(Locacao $locacao){
        $this->setLocacao($locacao);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacoes = array();
        $atributos_cliente = 'cliente';
        $atributos_carro = 'carro';
    
        $locacao_repository = new LocacaoRepository($this->getLocacao());

        if ($request->has('atributos_cliente')){
            $atributos_cliente = $atributos_cliente.':id,'.$request->atributos_cliente;
        }
        
        $locacao_repository->selectRelacionados($atributos_cliente);

        if ($request->has('atributos_carro')){
            $atributos_carro = $atributos_cliente.':id,'.$request->atributos_carro;
        }
        
        $locacao_repository->selectRelacionados($atributos_carro);

        if ($request->has('filtros')){
            $locacao_repository->filtrar($request->filtros);
        }

        if ($request->has('atributos')){
            $atributos = 'id,'.$request->atributos;
            $locacao_repository->selectAtributos($atributos);
        }

        $locacoes = $locacao_repository->getModel()->get();

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $locacoes
            ),
            200
        );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->getLocacao()->rules(), $this->getLocacao()->feedback());

        $params = array(
            'cliente_id' => $request->cliente_id, 
            'carro_id' => $request->carro_id, 
            'data_inicio_periodo' => $request->data_inicio_periodo, 
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo, 
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo, 
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final
        );

        $locacao = $this->getLocacao()->create($params);

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $locacao
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
        $locacao = $this->getLocacao()->find($id);

        if ($locacao === null){            
            return response()->json(
                array(
                    'erro' => true,
                    'msg' => 'Locação não encontrado.'
                ),
                404
            );
        }

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $locacao
            ),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocacaoRequest  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->getLocacao()->find($id);

        if ($locacao === null){
            return response()->json(
                array(
                    'erro' => true,
                    'msg' => 'Locação não encontrada.'
                ),
                404
            );
        }

        if ($request->method() === 'PATCH'){
            $regras = array();
            $regras_locacao = $locacao->rules();

            foreach($regras_locacao as $input => $regra){
                if (array_key_exists($input, $request->all())){
                    $regras[$input] = $regra;
                }
            }

            $request->validate($regras, $locacao->feedback());
        }
        else{
            $request->validate($locacao->rules(), $locacao->feedback());
        }
        
        $locacao->fill($request->all());
        $locacao->save();

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $locacao
            ),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->getLocacao()->find($id);

        if ($locacao === null){
            return response()->json(
                array(
                    'erro' => true,
                    'msg' => 'Locação não encontrada.'
                ),
                404
            );
        }
        
        $locacao->delete();
        
        return response()->json(
            array(
                'erro' => false,
                'retorno' => [
                    'msg' => 'Locação excluída com sucesso.'
                ]
            ),
            200
        );
    }
}
