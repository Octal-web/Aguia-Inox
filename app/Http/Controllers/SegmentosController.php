<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Segmento;
use App\Models\Pagina;

use Carbon\Carbon;

class SegmentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function segmento($slug = null) {
        if(!$slug) {
            return Inertia::location(route('Home.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $segmento = Segmento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'slug' => $slug
            ])
            ->with([
                'segmentosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'produtosCategorias.produtos' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'produtosIdiomas' => function ($qi) use ($idioma) {
                            $qi->whereHas('idiomas', function ($ri) use ($idioma) {
                                $ri->where('codigo', $idioma)
                                   ->orWhere('padrao', true);
                            })->orderBy('idioma_id', 'DESC');
                        }
                    ])
                    ->orderBy('ordem', 'ASC');
                },
                'produtosCategorias' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'produtosCategoriasIdiomas' => function ($qi) use ($idioma) {
                            $qi->whereHas('idiomas', function ($ri) use ($idioma) {
                                $ri->where('codigo', $idioma)
                                   ->orWhere('padrao', true);
                            })->orderBy('idioma_id', 'DESC');
                        }
                    ])
                    ->orderBy('ordem', 'ASC');
                }
            ])
            ->first();

        if(!$segmento) {
            return Inertia::location(route('Home.index'));
        }

        $pagina = new Pagina;

        $pagina->titulo = $segmento->segmentosIdiomas[0]->titulo_pagina . ' - Águia Inox';
        $pagina->descricao = $segmento->segmentosIdiomas[0]->descricao_pagina . ' - Águia Inox';
        $pagina->tituloCompartilhamento = $segmento->segmentosIdiomas[0]->titulo_pagina . ' - Águia Inox';
        $pagina->descricaoCompartilhamento = $segmento->segmentosIdiomas[0]->descricao_pagina . ' - Águia Inox';

        list($width, $height, $type, $attr) = getimagesize(public_path('/content/sectors/thumbs/' . $segmento->imagem));

        $pagina->imagem = [
            'endereco' => '/content/sectors/thumbs/' . $segmento->imagem,
            'tipo' => image_type_to_mime_type($type),
            'largura' => $width,
            'altura' => $height,
        ];

        $segmentoData = [
            'id' => $segmento->id,
            'nome' => $segmento->segmentosIdiomas->first()?->nome,
            'slug' => $segmento->slug,
            'descricao' => $segmento->segmentosIdiomas->first()?->descricao,
            'categorias' => $segmento->produtosCategorias->map(function ($categoria) {
                return [
                    'id' => $categoria->id,
                    'slug' => $categoria->slug,
                    'nome' => $categoria->produtosCategoriasIdiomas->first()?->nome,
                    'produtos' => $categoria->produtos->map(function ($produto) {
                        return [
                            'id' => $produto->id,
                            'slug' => $produto->slug,
                            'nome' => $produto->produtosIdiomas->first()?->nome,
                            'imagem' => rafator('content/products/thumbs/s/' . $produto->imagem),
                        ];
                    })
                ];
            })
        ];

        return Inertia::render('Segmento', [
            'pagina' => $pagina,
            'segmento' => $segmentoData
        ]);
    }
};