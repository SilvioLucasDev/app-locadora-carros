<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function __construct(public Modelo $modelo)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->modelo->with('marca')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');
        return $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if (empty($modelo)) return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        return $modelo;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Int $id)
    {
        $modelo = $this->modelo->find($id);
        if (empty($modelo)) return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        if ($request->isMethod('patch')) {
            $rules = array();
            foreach ($modelo->rules($id) as $input => $regras) {
                if (array_key_exists($input, $request->all())) $rules[$input] = $regras;
            }
            $request->validate($rules, $this->modelo->feedback());
        } else {
            $request->validate($this->modelo->rules($id), $this->modelo->feedback());
        }
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens/modelos', 'public');
        }
        $modelo->fill($request->all());
        $request->file('imagem') ? $modelo->imagem = $imagem_urn : '';
        $modelo->save();
        return $modelo;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $modelo = $this->modelo->find($id);
        if (empty($modelo)) return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();
        return ['msg' => 'O modelo foi removido com sucesso!'];
    }
}
