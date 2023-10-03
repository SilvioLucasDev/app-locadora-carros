<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    public function rules($id = null)
    {
        return [
            'nome' => "required|min:3|unique:marcas,nome,{$id}",
            'imagem' => 'required|file|mimes:png'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome informado já existe',
            'nome.min' => 'O nome deve ter no mínimo :min caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG',
        ];
    }

    public function modelos() {
        return $this->hasMany('App\Models\Modelo');
    }
}
