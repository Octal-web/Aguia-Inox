<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Produto;

class PesquisarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()  {
        if (request('q')) {
            $idioma = inertia()->getShared('idioma');

            $produtos = Produto::query()
                ->where([
                    'visivel' => true,
                    'excluido' => NULL,
                ])
                ->whereHas('produtosIdiomas', function ($q) use ($idioma) {
                    $q->where(function ($query) {
                        $search = request('q');
                        $query->where('nome', 'LIKE', "%$search%")
                              ->orWhere('descricao', 'LIKE', "%$search%");
                    })
                    ->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                })
                ->orderBy('ordem', 'ASC')
                ->orderBy('id', 'DESC')
                ->get()
                ->map(function($outroProduto) {
                    return [
                        'id' => $outroProduto->id,
                        'segmento_slug' => $outroProduto->produtosCategorias[0]->segmento->slug ?? null,
                        'nome' => $outroProduto->produtosIdiomas->first()?->nome,
                        'descricao' => $outroProduto->produtosIdiomas->first()?->descricao,
                        'imagem' => rafator('content/products/thumbs/b/' . $outroProduto->imagem),
                        'slug' => $outroProduto->slug
                    ];
                });

            return Inertia::render('Resultados', [
                'produtos' => $produtos,
                'q' => request('q')
            ]);
        } else {
            return Inertia::location(route('Home.index'));
        }
    }
}
