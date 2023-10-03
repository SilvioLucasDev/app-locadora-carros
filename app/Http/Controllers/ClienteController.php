<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(private Cliente $cliente)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);
        if ($request->has('filtro')) $clienteRepository->filtro($request->filtro);
        if ($request->has('atributos')) $clienteRepository->selectAtributos($request->atributos);
        return $clienteRepository->getResultado();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());
        $cliente = $this->cliente->create($request->all());
        return $cliente;
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        $cliente = $this->cliente->find($id);
        if (empty($cliente)) return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        return $cliente;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Int $id)
    {
        $cliente = $this->cliente->find($id);
        if (empty($cliente)) return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        if ($request->isMethod('patch')) {
            $rules = array();
            foreach ($cliente->rules($id) as $input => $regras) {
                if (array_key_exists($input, $request->all())) $rules[$input] = $regras;
            }
            $request->validate($rules, $this->cliente->feedback());
        } else {
            $request->validate($this->cliente->rules($id), $this->cliente->feedback());
        }
        $cliente->fill($request->all());
        $cliente->save();
        return $cliente;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $cliente = $this->cliente->find($id);
        if (empty($cliente)) return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        $cliente->delete();
        return ['msg' => 'O cliente foi removido com sucesso!'];
    }
}
