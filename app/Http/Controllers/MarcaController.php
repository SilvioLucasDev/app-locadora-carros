<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

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
        $marca = $this->marca->create($request->all());
        return $marca;
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        $marca = $this->marca->find($id);
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Int $id)
    {
        $marca = $this->marca->find($id);
        $marca->update($request->all());
        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $marca = $this->marca->find($id);
        $marca->delete();
        return ['msg' => 'A marca foi removida com sucesso!'];
    }
}
