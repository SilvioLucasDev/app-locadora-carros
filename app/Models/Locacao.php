<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;
    protected $table = 'locacoes';
    protected $fillable = [
        'cliente_id',
        'carro_id',
        'data_inicio_periodo',
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final',
    ];

    public function rules($id = null)
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'carro_id' => 'required|exists:carros,id',
            'data_inicio_periodo' => 'required|date_format:Y/m/d',
            'data_final_previsto_periodo' => 'required|date_format:Y/m/d',
            'data_final_realizado_periodo' => 'date_format:Y/m/d',
            'valor_diaria' => 'required|decimal:2',
            'km_inicial' => 'required'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'cliente_id.exists' => 'Cliente não encontrado',
            'carro_id.exists' => 'Carro não encontrado',
            'integer' => 'O campo :attribute deve ser um número inteiro',
            'date_format' => 'O campo :attribute deve uma data válida',
            'decimal' => 'O campo :attribute deve ser um decimal (0.00)',
        ];
    }
}
