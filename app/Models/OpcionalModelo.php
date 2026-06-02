<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionalModelo extends Model {
    protected $table = 'opcionais_modelos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function opcionaisModelosIdiomas()
    {
        return $this->hasMany(OpcionalModeloIdioma::class);
    }

    public function opcional() {
        return $this->belongsTo(Opcional::class);
    }
}