<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    public function __construct(private Carro $carro)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);
        $atributos_modelo = $request->has('atributos_modelo') ? "modelo:id,{$request->atributos_modelo}" : 'modelo';
        $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        if ($request->has('filtro')) $carroRepository->filtro($request->filtro);
        if ($request->has('atributos')) $carroRepository->selectAtributos($request->atributos);
        return $carroRepository->getResultado();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedback());
        $carro = $this->carro->create($request->all());
        return $carro;
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if (empty($carro)) return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        return $carro;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Int $id)
    {
        $carro = $this->carro->find($id);
        if (empty($carro)) return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        if ($request->isMethod('patch')) {
            $rules = array();
            foreach ($carro->rules($id) as $input => $regras) {
                if (array_key_exists($input, $request->all())) $rules[$input] = $regras;
            }
            $request->validate($rules, $this->carro->feedback());
        } else {
            $request->validate($this->carro->rules($id), $this->carro->feedback());
        }
        $carro->fill($request->all());
        $carro->save();
        return $carro;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $carro = $this->carro->find($id);
        if (empty($carro)) return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        $carro->delete();
        return ['msg' => 'O carro foi removido com sucesso!'];
    }
}
