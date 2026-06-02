<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model {
    protected $table = 'parceiros';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = [
        'nome',
        'email',
        'cnpj',
        'telefone',
        'cargo',
        'assunto',
        'mensagem',
    ];

    protected $guarded = ['id'];
}