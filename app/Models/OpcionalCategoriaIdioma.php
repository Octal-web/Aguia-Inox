<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpcionalCategoriaIdioma extends Model {
    protected $table = 'opcionais_categorias_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function opcionalCategoria()
    {
        return $this->belongsTo(OpcionalCategoria::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}