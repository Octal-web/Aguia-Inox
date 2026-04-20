<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model {
    protected $table = 'departamentos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function departamentosIdiomas()
    {
        return $this->hasMany(DepartamentoIdioma::class);
    }

    public function departamentosEmails()
    {
        return $this->hasMany(DepartamentoEmail::class);
    }
}