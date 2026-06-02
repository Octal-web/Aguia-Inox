<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Slide;
use App\Models\Segmento;
use App\Models\Cliente; 
use App\Models\Post;

use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $slides = Slide::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'slidesIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($slide) {
                return [
                    'id' => $slide->id,
                    'tipo' => $slide->tipo,
                    'imagem' => $slide->tipo == 'imagem' ? rafator('content/slides/d/' . $slide->imagem) : null,
                    'imagem_mobile' => $slide->tipo == 'imagem' ? rafator('content/slides/m/' . $slide->imagem_mobile) : null,
                    'video' => $slide->tipo == 'video' ? rafator('content/slides/videos/d/' . $slide->video) : null,
                    'video_mobile' => $slide->tipo == 'video' ? rafator('content/slides/videos/m/' . $slide->video_mobile) : null,
                    'titulo' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->titulo : null,
                    'descricao' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->descricao : null,
                    'link' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->link : null,
                    'texto_botao' => $slide->slidesIdiomas->isNotEmpty() ? $slide->slidesIdiomas[0]->texto_botao : null,
                ];
            });

        $casesClientes = Post::query()
            ->where([
                'excluido' => NULL,
                'case_destaque' => true
            ])
            ->where(function($q) {
                $q->where('visivel', true)
                  ->orWhere('publicado', '>=', Carbon::now());
            })
            ->whereHas('postCategoria', function ($q) {
                $q->where('slug', 'nossos-cases');
            })
            ->with([
                'postsIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'postCategoria' => function ($q) use ($idioma) {
                    $q->where('excluido', null)
                      ->where('visivel', true)
                      ->with([
                          'postsCategoriasIdiomas' => function ($qi) use ($idioma) {
                              $qi->whereHas('idiomas', function ($ri) use ($idioma) {
                                  $ri->where('codigo', $idioma)
                                     ->orWhere('padrao', true);
                              })->orderBy('idioma_id', 'DESC');
                          }
                      ])
                      ->orderBy('ordem', 'ASC');
                }
            ])
            ->orderBy('publicado', 'DESC')
            ->orderBy('ordem', 'ASC')
            ->limit(6)
            ->get()
            ->map(function($post) {
                return [
                    'id' => $post->id,
                    'imagem' => rafator('content/posts/thumbs/' . $post->imagem),
                    'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
                    'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
                    'categoria' => $post->postCategoria?->postsCategoriasIdiomas?->isNotEmpty() ? $post->postCategoria?->postsCategoriasIdiomas[0]->nome : null,
                    'categoria_slug' => $post->postCategoria->slug,
                    'slug' => $post->slug
                ];
            });
            
        $segmentos = Segmento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'destaque' => true
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
                    ->orderBy('ordem', 'ASC');
                },
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($segmento) {
                $produto = $segmento->produtosCategorias
                    ->map(fn($cat) => $cat->produtos->first())
                    ->filter()
                    ->first();

                return [
                    'id' => $segmento->id,
                    'banner' => rafator('content/sectors/banner/' . $segmento->banner),
                    'nome' => $segmento->segmentosIdiomas->isNotEmpty() ? $segmento->segmentosIdiomas[0]->nome : null,
                    'descricao' => $segmento->segmentosIdiomas->isNotEmpty() ? $segmento->segmentosIdiomas[0]->descricao_pagina : null,
                    'produto_destaque' => $produto ? rafator('content/products/thumbs/s/' . $produto->imagem) : null, 
                    'slug' => $segmento->slug,
                ];
            });
            
        $clientes = Cliente::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($cliente) {
                return [
                    'id' => $cliente->id,
                    'logo' => rafator('content/brands/thumbs/' . $cliente->logo),
                    'nome' => $cliente->nome,
                    'link' => $cliente->link,
                ];
            });

        $posts = Post::query()
            ->where([
                'excluido' => NULL,
            ])
            ->where(function($q) {
                $q->where('visivel', true)
                  ->orWhere('publicado', '>=', Carbon::now());
            })
            ->whereHas('postCategoria', function ($q) {
                $q->where('slug', '!=', 'nossos-cases');
            })
            ->with([
                'postsIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'postCategoria' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'postsCategoriasIdiomas' => function ($qi) use ($idioma) {
                            $qi->whereHas('idiomas', function ($ri) use ($idioma) {
                                $ri->where('codigo', $idioma)
                                   ->orWhere('padrao', true);
                            })->orderBy('idioma_id', 'DESC');
                        }
                    ])
                  ->orderBy('ordem', 'ASC');
                }
            ])
            ->orderBy('publicado', 'DESC')
            ->orderBy('ordem', 'ASC')
            ->limit(6)
            ->get()
            ->map(function($post) {
                return [
                    'id' => $post->id,
                    'imagem' => rafator('content/posts/thumbs/' . $post->imagem),
                    'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
                    'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
                    'categoria' => $post->postCategoria?->postsCategoriasIdiomas?->isNotEmpty() ? $post->postCategoria?->postsCategoriasIdiomas[0]->nome : null,
                    'categoria_slug' => $post->postCategoria?->slug,
                    'slug' => $post->slug
                ];
            });

        return Inertia::render('Home', [
            'slides' => $slides,
            'segmentos' => $segmentos,
            'casesClientes' => $casesClientes,
            'clientes' => $clientes,
            'posts' => $posts
        ]);
    }
};