<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartamentoEmail extends Model {
    protected $table = 'departamentos_emails';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = [
        'endereco',
        'excluido',
        'departamento_id',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}