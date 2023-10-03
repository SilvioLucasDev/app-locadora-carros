<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    public function __construct(private Locacao $locacao)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);
        if ($request->has('filtro')) $locacaoRepository->filtro($request->filtro);
        if ($request->has('atributos')) $locacaoRepository->selectAtributos($request->atributos);
        return $locacaoRepository->getResultado();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules(), $this->locacao->feedback());
        $locacao = $this->locacao->create($request->all());
        return $locacao;
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        $locacao = $this->locacao->find($id);
        if (empty($locacao)) return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        return $locacao;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Int $id)
    {
        $locacao = $this->locacao->find($id);
        if (empty($locacao)) return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        if ($request->isMethod('patch')) {
            $rules = array();
            foreach ($locacao->rules($id) as $input => $regras) {
                if (array_key_exists($input, $request->all())) $rules[$input] = $regras;
            }
            $request->validate($rules, $this->locacao->feedback());
        } else {
            $request->validate($this->locacao->rules($id), $this->locacao->feedback());
        }
        $locacao->fill($request->all());
        $locacao->save();
        return $locacao;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $locacao = $this->locacao->find($id);
        if (empty($locacao)) return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        $locacao->delete();
        return ['msg' => 'A locação foi removido com sucesso!'];
    }
}
