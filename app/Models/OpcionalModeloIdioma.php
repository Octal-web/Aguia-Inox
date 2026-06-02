<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpcionalModeloIdioma extends Model {
    protected $table = 'opcionais_modelos_idiomas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function opcionalModelo()
    {
        return $this->belongsTo(OpcionalModelo::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}