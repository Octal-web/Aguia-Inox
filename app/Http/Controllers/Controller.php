<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;

use Inertia\Inertia;

use App\Models\Idioma;
use App\Models\Pagina;
use App\Models\Conteudo;
use App\Models\DadosGerais;
use App\Models\Segmento;
use App\Models\PostCategoria;

use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

abstract class Controller
{
    public function __construct() {
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('Controller@', $controllerAction);

        if (app('request')->route()->getPrefix() == '/manager') {
            $idioma = request('lang', -1);

            $idiomas = Idioma::all();
    
            $idioma = Idioma::query()
                ->where(function ($query) use ($idioma) {
                    $query->orWhere([
                        'padrao' => true
                    ])
                    ->orWhere([
                        'codigo' => $idioma
                    ]);
                })
                ->orderBy('padrao', 'ASC')
                ->orderBy('id', 'DESC')
                ->first();

            $pagina = Pagina::query()
                ->where([
                    'controladora' => $controller,
                    'acao' => $action
                ])
                ->with([
                    'paginasIdiomas' => function($q) use ($idioma) {
                        $q->whereHas('idiomas', function($r) use ($idioma) {
                            $r->where([
                                'id' => $idioma->id,
                            ]);
                        })
                        ->with('idiomas');
                    },
                ])
                ->first();
            
            $conteudos = Conteudo::query()
                ->where([
                    'controladora' => $controller,
                    'acao' => $action
                ])
                ->with([
                    'conteudosIdiomas' => function($q) use ($idioma) {
                        $q->whereHas('idiomas', function($r) use ($idioma) {
                            $r->where([
                                'id' => $idioma->id,
                            ]);
                        })
                        ->with('idiomas');
                    },
                    'parametro'
                ])
                ->get()
                ->map(function($conteudo) {
                    return [
                        'id' => $conteudo->id,
                        'bloco' => $conteudo->parametro->descricao,
                        'titulo' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->titulo : null,
                        'habilitar_titulo' => $conteudo->parametro->habilitar_titulo ? true : false,
                        'subtitulo' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->subtitulo : null,
                        'habilitar_subtitulo' => $conteudo->parametro->habilitar_subtitulo ? true : false,
                        'texto' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->texto : null,
                        'habilitar_texto' => $conteudo->parametro->habilitar_texto ? true : false,
                        'texto_formatado' => $conteudo->parametro->texto_formatado ? true : false,
                        'imagem' => rafator('/content/display/' . $conteudo->imagem),
                        'habilitar_img' => $conteudo->parametro->habilitar_img ? true : false,
                        'largura_img' => $conteudo->parametro->largura_img,
                        'altura_img' => $conteudo->parametro->altura_img,
                        'recortar_img' => $conteudo->parametro->recortar_img ? true : false,
                        'imagem_mobile' => rafator('content/display/' . $conteudo->imagem_mobile),
                        'habilitar_img_mobile' => $conteudo->parametro->habilitar_img_mobile ? true : false,
                        'largura_img_mobile' => $conteudo->parametro->largura_img_mobile,
                        'altura_img_mobile' => $conteudo->parametro->altura_img_mobile,
                        'recortar_img_mobile' => $conteudo->parametro->recortar_img_mobile ? true : false,
                        'link' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->link : null,
                        'habilitar_link' => $conteudo->parametro->habilitar_link ? true : false,
                        'video' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->video : null,
                        'habilitar_video' => $conteudo->parametro->habilitar_video ? true : false,
                        'nova_aba' => $conteudo->conteudosIdiomas->isNotEmpty() && $conteudo->conteudosIdiomas[0]->nova_aba ? true : false,
                        'minimizavel' => $conteudo->parametro->minimizavel ? true : false,
                        'galeria' => $conteudo->parametro->galeria ? true : false,
                    ];
                });
            
            if ($pagina) {
                $pagina = [
                    'id' => $pagina->id,
                    'titulo' => $pagina->paginasIdiomas->isNotEmpty() ? $pagina->paginasIdiomas[0]->titulo : null,
                    'descricao' => $pagina->paginasIdiomas->isNotEmpty() ? $pagina->paginasIdiomas[0]->descricao : null,
                    'titulo_compartilhamento' => $pagina->paginasIdiomas->isNotEmpty() ? $pagina->paginasIdiomas[0]->titulo_compartilhamento : null,
                    'descricao_compartilhamento' => $pagina->paginasIdiomas->isNotEmpty() ? $pagina->paginasIdiomas[0]->descricao_compartilhamento : null,
                    'imagem' => rafator('/content/pages/' . $pagina->imagem),
                ];
            }

            $idiomas = Idioma::all()->map(function($linguagem) {
                return [
                    'nome' => $linguagem->nome,
                    'codigo' => $linguagem->codigo,
                    'padrao' => $linguagem->padrao ? true : false,
                ];
            });

            Inertia::share([
                'pagina' => $pagina,
                'conteudos' => $conteudos,
                'idioma' => $idioma,
                'idiomas' => $idiomas,
                'controller' => $controller,
                'action' => $action
            ]);
        } else {
            $idiomas = Idioma::query()
                ->orderBy('padrao', 'DESC')
                ->orderBy('id', 'DESC')
                ->get()
                ->map(function ($idioma) {
                    $idioma->url = LaravelLocalization::getLocalizedURL($idioma->codigo, null, [], true);
                    return $idioma;
                });
    
            $idioma = App::getLocale();

            $conteudos = Conteudo::query()
                ->where([
                    'excluido' => NULL,
                    'controladora' => $controller,
                    'acao' => $action
                ])
                ->with([
                    'conteudosIdiomas' => function ($q) use ($idioma) {
                        $q->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    },
                ])
                ->get()
                ->map(function($conteudo) {
                    return [
                        'id' => $conteudo->id,
                        'titulo' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->titulo : null,
                        'subtitulo' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->subtitulo : null,
                        'texto' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->texto : null,
                        'imagem' => rafator('/content/display/' . $conteudo->imagem),
                        'imagem_mobile' => rafator('/content/display/' . $conteudo->imagem_mobile),
                        'link' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->link : null,
                        'video' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->video : null,
                    ];
                });

            $pagina = Pagina::query()
                ->where([
                    'controladora' => $controller,
                    'acao' => $action
                ])
                ->with([
                    'paginasIdiomas' => function ($q) use ($idioma) {
                        $q->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    },
                ])
                ->first();

            $segmentosMenu = Segmento::query()
                ->where([
                    'excluido' => null,
                    'visivel' => true,
                ])
                ->with([
                    'segmentosIdiomas' => function ($q) use ($idioma) {
                        $q->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                              ->orWhere('padrao', true);
                        })->orderBy('idioma_id', 'DESC');
                    },
                    'produtosCategorias' => function ($q) use ($idioma) {
                        $q->where('excluido', null)
                          ->where('visivel', true)
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
                ->orderBy('ordem', 'ASC')
                ->get()
                ->map(function ($segmento) {
                    return [
                        'id' => $segmento->id,
                        'nome' => $segmento->segmentosIdiomas[0]->nome ?? null,
                        'descricao' => $segmento->segmentosIdiomas[0]->descricao ?? null,
                        'slug' => $segmento->slug,
                        'categorias' => $segmento->produtosCategorias->map(function ($categoria) {
                            return [
                                'id' => $categoria->id,
                                'nome' => $categoria->produtosCategoriasIdiomas[0]->nome ?? null,
                                'slug' => $categoria->slug,
                            ];
                        })
                    ];
                });
            
            $postsCategoriasMenu = PostCategoria::query()
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

            $dadosGerais = DadosGerais::first();

            $notifyCookie = array_key_exists('notify-cookies', $_COOKIE) ? true : false;

            if ($pagina) {
                list($width, $height, $type, $attr) = getimagesize(public_path('content/pages/' . $pagina->imagem));
            }

            Inertia::share([
                'pagina' => [
                    'titulo' => $pagina->paginasIdiomas->isNotEmpty() ? $pagina->paginasIdiomas[0]->titulo : null,
                    'descricao' => $pagina->paginasIdiomas->isNotEmpty() ? $pagina->paginasIdiomas[0]->descricao : null,
                    'tituloCompartilhamento' => $pagina->paginasIdiomas->isNotEmpty() ? $pagina->paginasIdiomas[0]->titulo_compartilhamento : null,
                    'descricaoCompartilhamento' => $pagina->paginasIdiomas->isNotEmpty() ? $pagina->paginasIdiomas[0]->descricao_compartilhamento : null,
                    'imagem' => [
                        'endereco' => '/content/pages/' . $pagina->imagem,
                        'tipo' => image_type_to_mime_type($type),
                        'largura' => $width,
                        'altura' => $height,
                    ],
                ],
                'dadosGerais' => $dadosGerais,
                'segmentosMenu' => $segmentosMenu,
                'postsCategoriasMenu' => $postsCategoriasMenu,
                'notifyCookie' => $notifyCookie,
                'controller' => $controller,
                'conteudos' => $conteudos,
                'idiomas' => $idiomas,
                'idioma' => $idioma,
            ]);
        }
    }
    
    protected function getLanguages($record, $translationModel, $language) {
        $idiomas = Idioma::query()
            ->orderByDesc('padrao')
            ->orderBy('codigo')
            ->pluck('id', 'codigo')
            ->toArray();

        $translationProperty = Str::snake($translationModel);

        if (!$language) {
            return reset($idiomas);
        } elseif (!$record->$translationProperty) {
            if (!array_key_exists($language, $idiomas)) {
                return false;
            }

            return $idiomas[$language];
        }

        return $record->$translationProperty[0]->idioma;
    }
}