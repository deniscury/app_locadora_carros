<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    protected $marca;

    public function getMarca(){
        return $this->marca;
    }

    public function setMarca(Marca $marca){
        $this->marca = $marca;
    }

    public function __construct(Marca $marca){
        $this->setMarca($marca);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marca = $this->getMarca()->all();

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $marca
            ),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->getMarca()->rules(), $this->getMarca()->feedback());
        
        $marca = $this->getMarca()->create($request->all());

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $marca
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
        $marca = $this->getMarca()->find($id);

        if ($marca === null){            
            return response()->json(
                array(
                    'erro' => true,
                    'msg' => 'Marca não encontrada.'
                ),
                404
            );
        }

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $marca
            ),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->getMarca()->find($id);

        if ($marca === null){
            return response()->json(
                array(
                    'erro' => true,
                    'msg' => 'Marca não encontrada.'
                ),
                404
            );
        }

        if ($request->method() === 'PATCH'){
            $regras = array();
            $regras_marca = $marca->rules();

            foreach($regras_marca as $input => $regra){
                if (array_key_exists($input, $request->all())){
                    $regras[$input] = $regra;
                }
            }

            $request->validate($regras, $marca->feedback());
        }
        else{
            $request->validate($marca->rules(), $marca->feedback());
        }

        $marca->update($request->all());

        return response()->json(
            array(
                'erro' => false,
                'retorno' => $marca
            ),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->getMarca()->find($id);

        if ($marca === null){
            return response()->json(
                array(
                    'erro' => true,
                    'msg' => 'Marca não encontrada.'
                ),
                404
            );
        }
        
        $marca->delete();
        
        return response()->json(
            array(
                'erro' => false,
                'retorno' => [
                    'msg' => 'Marca excluída com sucesso.'
                ]
            ),
            200
        );
    }
}
