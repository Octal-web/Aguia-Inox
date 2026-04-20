<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionalCategoria extends Model {
    protected $table = 'opcionais_categorias';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function opcionaisCategoriasIdiomas()
    {
        return $this->hasMany(OpcionalCategoriaIdioma::class);
    }

    public function opcionais() {
        return $this->hasMany(Opcional::class);
    }
}