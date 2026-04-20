<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diferencial extends Model {
    protected $table = 'diferenciais';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function diferenciaisIdiomas()
    {
        return $this->hasMany(DiferencialIdioma::class);
    }
}