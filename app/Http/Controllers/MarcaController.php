<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(public Marca $marca)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = $this->marca->all();
        return $marcas;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
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
        $marca = $this->marca->find($id);
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
        if (!empty($request->file('imagem'))) Storage::disk('public')->delete($marca->imagem);
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
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
