<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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
        $marca = $this->getMarca()->with('modelos')->get();
        
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

        // MIME TYPE TEXT
        //dd($request->nome);
        //dd($request->get('nome');
        //dd($request->input('nome'));

        // MIME TYPE FILE
        //dd($request->imagem));
        //dd($request->file('imagem'));

        $img = $request->file('imagem');
        $imagem_urn = $img->store('imagens/marcas', 'public');

        $params = array(
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        );

        $marca = $this->getMarca()->create($params);

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
        $marca = $this->getMarca()->with('modelos')->find($id);

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
        
        $img = $request->file('imagem');

        if ($img !== null){
            Storage::disk('public')->delete($marca->imagem);

            $imagem_urn = $img->store('imagens/marcas', 'public');
        }

        $marca->fill($request->all());
        $marca->imagem = isset($imagem_urn)?$imagem_urn:$marca->imagem;

        $marca->save();

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

        Storage::disk('public')->delete($marca->imagem);
        
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
