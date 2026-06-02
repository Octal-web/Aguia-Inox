<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Models\Pagina;
use App\Models\Post;
use App\Models\PostCategoria;

use Carbon\Carbon;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $postDestaquePrincipal = Post::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'destaque' => 'principal'
            ])
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
                        'excluido' => null,
                        'visivel' => true
                    ])
                    ->with([
                        'postsCategoriasIdiomas' => function ($sub) use ($idioma) {
                            $sub->whereHas('idiomas', function ($r) use ($idioma) {
                                $r->where('codigo', $idioma)
                                  ->orWhere('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        }
                    ]);
                }
            ])
            ->orderBy('publicado', 'DESC')
            ->orderBy('ordem', 'ASC')
            ->limit(1)
            ->get()
            ->map(function($post) {
                return [
                    'id' => $post->id,
                    'imagem' => asset('content/posts/thumbs/' . $post->imagem),
                    'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
                    'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
                    'slug' => $post->slug,
                    'categoria' => $post->postCategoria->postsCategoriasIdiomas->isNotEmpty() ? $post->postCategoria->postsCategoriasIdiomas[0]->nome : null,
                    'categoria_slug' => $post->postCategoria->slug,
                ];
            });

        $postsDestaquesSecundarios = Post::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'destaque' => 'secundario'
            ])
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
                        'excluido' => null,
                        'visivel' => true
                    ])
                    ->with([
                        'postsCategoriasIdiomas' => function ($sub) use ($idioma) {
                            $sub->whereHas('idiomas', function ($r) use ($idioma) {
                                $r->where('codigo', $idioma)
                                  ->orWhere('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        }
                    ]);
                }
            ])
            ->orderBy('publicado', 'DESC')
            ->orderBy('ordem', 'ASC')
            ->limit(3)
            ->get()
            ->map(function($post) {
                return [
                    'id' => $post->id,
                    'imagem' => asset('content/posts/thumbs/' . $post->imagem),
                    'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
                    'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
                    'slug' => $post->slug,
                    'categoria' => $post->postCategoria->postsCategoriasIdiomas->isNotEmpty() ? $post->postCategoria->postsCategoriasIdiomas[0]->nome : null,
                    'categoria_slug' => $post->postCategoria->slug,
                ];
            });

        $posts = Post::query()
            ->where([
                'excluido' => NULL,
            ])
            ->when(request()->has('categoria'), function ($q) {
                $q->whereHas('postCategoria', function ($query) {
                    $query->where('slug', request('categoria'));
                });
            })
            ->where(function($q) {
                $q->where('visivel', true)
                  ->orWhere('publicado', '>=', Carbon::now());
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
                        'excluido' => null,
                        'visivel' => true
                    ])
                    ->with([
                        'postsCategoriasIdiomas' => function ($sub) use ($idioma) {
                            $sub->whereHas('idiomas', function ($r) use ($idioma) {
                                $r->where('codigo', $idioma)
                                  ->orWhere('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        }
                    ]);
                }
            ])
            ->orderBy('publicado', 'DESC')
            ->orderBy('ordem', 'ASC')
            ->paginate(12);

        $posts->getCollection()->transform(function($post) {
            return [
                'id' => $post->id,
                'imagem' => asset('content/posts/thumbs/' . $post->imagem),
                'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
                'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
                'categoria' => $post->postCategoria->postsCategoriasIdiomas->isNotEmpty() ? $post->postCategoria->postsCategoriasIdiomas[0]->nome : null,
                'categoria_slug' => $post->postCategoria->slug,
                'slug' => $post->slug,
            ];
        });

        $casesClientes = Post::query()
            ->where([
                'excluido' => NULL,
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
            ->get()
            ->map(function($post) {
                return [
                    'id' => $post->id,
                    'imagem' => rafator('content/posts/thumbs/' . $post->imagem),
                    'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
                    'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
                    'categoria' => $post->postCategoria->postsCategoriasIdiomas->isNotEmpty() ? $post->postCategoria->postsCategoriasIdiomas[0]->nome : null,
                    'categoria_slug' => $post->postCategoria->slug,
                    'slug' => $post->slug
                ];
            });
            
        $postsCategorias = PostCategoria::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'postsCategoriasIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($categoria) {
                return [
                    'id' => $categoria->id,
                    'nome' => $categoria->postsCategoriasIdiomas->isNotEmpty() ? $categoria->postsCategoriasIdiomas[0]->nome : null,
                    'slug' => $categoria->slug
                ];
            });

        return Inertia::render('News', [
            'postDestaquePrincipal' => $postDestaquePrincipal,
            'postsDestaquesSecundarios' => $postsDestaquesSecundarios,
            'posts' => $posts,
            'casesClientes' => $casesClientes,
            'postsCategorias' => $postsCategorias
        ]);
    }

    /**
     * Show the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function post(?string $categoria = null, ?string $slug = null) {
        if (!$categoria || !$slug) {
            return Inertia::location(route('News.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $post = Post::query()
            ->where([
                'excluido' => null,
                'slug' => $slug
            ])
            ->where(function($q) {
                $q->where('visivel', true)
                  ->orWhere('publicado', '>=', Carbon::now());
            })
            ->with([
                'postsIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->first();

        if(!$post) {
            return Inertia::location(route('News.index'));
        }

        $pagina = new Pagina;

        $pagina->titulo = $post->postsIdiomas[0]->titulo_pagina . ' - Águia Inox';
        $pagina->descricao = $post->postsIdiomas[0]->descricao_pagina . ' - Águia Inox';
        $pagina->titulo_compartilhamento = $post->postsIdiomas[0]->titulo_pagina . ' - Águia Inox';
        $pagina->descricao_compartilhamento = $post->postsIdiomas[0]->descricao_pagina . ' - Águia Inox';

        list($width, $height, $type, $attr) = getimagesize(public_path('/content/posts/thumbs/' . $post->imagem));

        $pagina->imagem = [
            'endereco' => '/content/posts/thumbs/' . $post->imagem,
            'tipo' => image_type_to_mime_type($type),
            'largura' => $width,
            'altura' => $height,
        ];

        $postData = [
            'id' => $post->id,
            'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
            'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
            'imagem' => asset('content/posts/thumbs/' . $post->imagem),
            'conteudo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->conteudo : null,
            'categoria' => $post->postCategoria->postsCategoriasIdiomas->isNotEmpty() ? $post->postCategoria->postsCategoriasIdiomas[0]->nome : null,
            'categoria_slug' => $post->postCategoria->slug,
            'publicado' => $post->publicado ? $post->publicado->translatedFormat('j \d\e F \d\e Y') : null,
            'slug' => $post->slug,
        ];

        $posts = Post::query()
            ->where([
                'excluido' => NULL,
                ['slug', '!=', $slug]
            ])
            ->where(function($q) {
                $q->where('visivel', true)
                  ->orWhere('publicado', '>=', Carbon::now());
            })
            ->inRandomOrder()
            ->limit(3)
            ->get()
            ->map(function($post) {
                return [
                    'id' => $post->id,
                    'imagem' => asset('content/posts/thumbs/' . $post->imagem),
                    'titulo' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->titulo : null,
                    'previa' => $post->postsIdiomas->isNotEmpty() ? $post->postsIdiomas[0]->previa : null,
                    'categoria_slug' => $post->postCategoria->slug,
                    'categoria' => $post->postCategoria->postsCategoriasIdiomas->isNotEmpty() ? $post->postCategoria->postsCategoriasIdiomas[0]->nome : null,
                    'slug' => $post->slug
                ];
            });

        return Inertia::render('NewsPost', [
            'pagina' => $pagina,
            'post' => $postData,
            'posts' => $posts
        ]);
    }

}