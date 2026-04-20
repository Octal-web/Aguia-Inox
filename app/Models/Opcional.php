<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcional extends Model {
    protected $table = 'opcionais';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function opcionaisIdiomas()
    {
        return $this->hasMany(OpcionalIdioma::class);
    }

    public function categoria() {
        return $this->belongsTo(OpcionalCategoria::class);
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'opcionais_produtos', 'opcional_id', 'produto_id');
    }

    public function opcionaisModelos()
    {
        return $this->hasMany(OpcionalModelo::class);
    }
}