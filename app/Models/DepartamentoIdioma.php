<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartamentoIdioma extends Model {
    protected $table = 'departamentos_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}