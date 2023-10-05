<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(private Marca $marca)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);
        $atributos_modelos = $request->has('atributos_modelos') ? "modelos:id,{$request->atributos_modelos}" : 'modelos';
        $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        if ($request->has('filtro')) $marcaRepository->filtro($request->filtro);
        if ($request->has('atributos')) $marcaRepository->selectAtributos($request->atributos);
        return $marcaRepository->getResultadoPaginado(3);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/marcas', 'public');
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        return $marca;
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if (empty($marca)) return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Int $id)
    {
        $marca = $this->marca->find($id);
        if (empty($marca)) return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        if ($request->isMethod('patch')) {
            $rules = array();
            foreach ($marca->rules($id) as $input => $regras) {
                if (array_key_exists($input, $request->all())) $rules[$input] = $regras;
            }
            $request->validate($rules, $this->marca->feedback());
        } else {
            $request->validate($this->marca->rules($id), $this->marca->feedback());
        }
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens/marcas', 'public');
        }
        $marca->fill($request->all());
        $request->file('imagem') ? $marca->imagem = $imagem_urn : '';
        $marca->save();
        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $marca = $this->marca->find($id);
        if (empty($marca)) return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();
        return ['msg' => 'A marca foi removida com sucesso!'];
    }
}
