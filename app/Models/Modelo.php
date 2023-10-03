<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules($id = null)
    {
        return [
            'marca_id' => 'required|exists:marcas,id',
            'nome' => "required|min:3|unique:modelos,nome,{$id}",
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|between:1,5',
            'lugares' => 'required|integer|between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'marca_id.exists' => 'Marca não encontrada',
            'nome.min' => 'O nome deve ter no mínimo :min caracteres',
            'nome.unique' => 'O nome informado já existe',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG, JPEG ou JPG',
            'integer' => 'O campo :attribute deve ser um número inteiro',
            'between' => 'O campo :attribute deve ter entre :min e :max dígitos',
            'boolean' => 'O campo :attribute deve ser um boolean',
        ];
    }

    public function marca() {
        return $this->belongsTo('App\Models\Marca');
    }
}
