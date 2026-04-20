<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProdutoCategoria extends Model {
    protected $table = 'produtos_categorias';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function produtosCategoriasIdiomas()
    {
        return $this->hasMany(ProdutoCategoriaIdioma::class);
    }

    public function segmento()
    {
        return $this->belongsTo(Segmento::class);
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'categorias_produtos', 'produto_categoria_id', 'produto_id');
    }

    public function downloads(): MorphMany
    {
        return $this->morphMany(Download::class, 'relacionavel');
    }
}