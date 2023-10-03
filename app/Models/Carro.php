<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function rules($id = null)
    {
        return [
            'modelo_id' => 'required|exists:modelos,id',
            'placa' => "required|unique:carros,placa,{$id}",
            'disponivel' => 'required|boolean',
            'km' => 'required',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'modelo_id.exists' => 'Marca não encontrada',
            'boolean' => 'O campo :attribute deve ser um boolean',
            'placa.unique' => 'A placa informada já existe',
        ];
    }

    public function modelo() {
        return $this->belongsTo('App\Models\Modelo');
    }
}
