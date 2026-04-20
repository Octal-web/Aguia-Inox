<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Segmento extends Model {
    protected $table = 'segmentos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function segmentosIdiomas()
    {
        return $this->hasMany(SegmentoIdioma::class);
    }

    public function produtosCategorias()
    {
        return $this->hasMany(ProdutoCategoria::class, 'segmento_id');
    }
    
    public function downloads(): MorphMany
    {
        return $this->morphMany(Download::class, 'relacionavel');
    }
}