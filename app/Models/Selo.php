<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selo extends Model {
    protected $table = 'selos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function selosIdiomas()
    {
        return $this->hasMany(SeloIdioma::class);
    }
}