<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Produto extends Model {
    protected $table = 'produtos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function produtosIdiomas()
    {
        return $this->hasMany(ProdutoIdioma::class);
    }

    public function opcionais()
    {
        return $this->belongsToMany(Opcional::class, 'opcionais_produtos', 'produto_id', 'opcional_id');
    }

    public function imagensProdutos()
    {
        return $this->hasMany(ImagemProduto::class);
    }

    public function produtosCategorias()
    {
        return $this->belongsToMany(ProdutoCategoria::class, 'categorias_produtos', 'produto_id', 'produto_categoria_id');
    }
    
    public function downloads(): MorphMany
    {
        return $this->morphMany(Download::class, 'relacionavel');
    }
}