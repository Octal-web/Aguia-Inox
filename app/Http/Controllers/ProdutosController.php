<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Produto;
use App\Models\Segmento;
use App\Models\Pagina;

use Illuminate\Support\Collection;
use Carbon\Carbon;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function produto($segmento = null, $slug = null) {
        if(!$slug || !$segmento) {
            return Inertia::location(route('Home.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $segmentoItem = Segmento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'slug' => $segmento
            ])
            ->first();

        if(!$segmento) {
            return Inertia::location(route('Home.index'));
        }

        $produto = Produto::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'slug' => $slug
            ])
            ->whereHas('produtosCategorias.segmento', function ($q) use ($segmento) {
                $q->where([
                    'excluido' => NULL,
                    'visivel' => true,
                    'slug' => $segmento
                ]);
            })
            ->with([
                'produtosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'opcionais' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'opcionaisIdiomas' => function ($qi) use ($idioma) {
                            $qi->whereHas('idiomas', function ($ri) use ($idioma) {
                                $ri->where('codigo', $idioma)
                                   ->orWhere('padrao', true);
                            })->orderBy('idioma_id', 'DESC');
                        }
                    ])
                    ->orderBy('ordem', 'ASC');
                },
                'downloads' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->orderBy('ordem', 'ASC');
                },
                'produtosCategorias.segmento.downloads' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->orderBy('ordem', 'ASC');
                },
                'produtosCategorias.downloads' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->orderBy('ordem', 'ASC');
                },
                'imagensProdutos' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'imagensProdutosIdiomas' => function ($qi) use ($idioma) {
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

        if(!$produto) {
            return Inertia::location(route('Home.index'));
        }

        $pagina = new Pagina;

        $pagina->titulo = $produto->produtosIdiomas[0]->titulo_pagina . ' | Águia Inox';
        $pagina->descricao = $produto->produtosIdiomas[0]->descricao_pagina . ' | Águia Inox';
        $pagina->titulo_compartilhamento = $produto->produtosIdiomas[0]->titulo_pagina . ' | Águia Inox';
        $pagina->descricao_compartilhamento = $produto->produtosIdiomas[0]->descricao_pagina . ' | Águia Inox';

        list($width, $height, $type, $attr) = getimagesize(public_path('/content/products/thumbs/b/' . $produto->imagem));

        $pagina->imagem = [
            'endereco' => '/content/products/thumbs/b/' . $produto->imagem,
            'tipo' => image_type_to_mime_type($type),
            'largura' => $width,
            'altura' => $height,
        ];

        $outrosProdutos = Produto::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                ['slug', '!=', $slug]
            ])
            ->whereHas('produtosCategorias.segmento', function ($q) use ($segmento) {
                $q->where([
                    'excluido' => NULL,
                    'visivel' => true,
                    'slug' => $segmento
                ]);
            })
            ->with([
                'produtosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'produtosCategorias' => function ($q) use ($idioma, $segmento) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->whereHas('segmento', function ($query) use ($segmento) {
                        $query->where([
                            'excluido' => NULL,
                            'visivel' => true,
                            'slug' => $segmento
                        ]);
                    })
                    ->with('produtosCategoriasIdiomas', function ($query) use ($idioma) {
                        $query->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                              ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    });
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($outroProduto) {
                return [
                    'id' => $outroProduto->id,
                    'segmento' => $outroProduto->produtosCategorias[0]->segmento->slug,
                    'nome' => $outroProduto->produtosIdiomas->first()?->nome,
                    'descricao' => $outroProduto->produtosIdiomas->first()?->descricao,
                    'imagem' => rafator('content/products/thumbs/b/' . $outroProduto->imagem),
                    'slug' => $outroProduto->slug
                ];
            });

        $downloadsProduto = $produto->downloads;

        $downloadsCategorias = $produto->produtosCategorias->flatMap(function ($categoria) {
            return $categoria->downloads;
        });

        $downloadsSegmentos = $produto->produtosCategorias->flatMap(function ($categoria) {
            return optional($categoria->segmento)->downloads ?? collect();
        });

        $downloadsCombinados = collect()
            ->merge($downloadsProduto)
            ->merge($downloadsCategorias)
            ->merge($downloadsSegmentos)
            ->unique('id'); 

        $produtoData = [
            'id' => $produto->id,
            'slug' => $produto->slug,
            'segmento' => $produto->produtosCategorias[0]->segmento->slug,
            'nome' => $produto->produtosIdiomas->first()?->nome,
            'categoria' => $produto->produtosCategorias->first()?->produtosCategoriasIdiomas->first()?->nome,
            'descricao' => $produto->produtosIdiomas->first()?->descricao,
            'imagem' => rafator('content/products/thumbs/b/' . $produto->imagem),
            'video' => $produto->video ? getEmbedUrl($produto->video) : null,
            'opcionais' => $produto->opcionais->map(function ($opcional) {
                return [
                    'id' => $opcional->id,
                    'slug' => $opcional->slug,
                    'titulo' => $opcional->opcionaisIdiomas->first()?->titulo,
                    'categoria_slug' => $opcional->categoria?->slug,
                ];
            }),
            'imagens' => $produto->imagensProdutos->map(function ($imagem) {
                return [
                    'id' => $imagem->id,
                    'titulo' => $imagem->imagensProdutosIdiomas->first()?->titulo,
                    'texto' => $imagem->imagensProdutosIdiomas->first()?->texto,
                    'imagem' => rafator('content/products/gallery/' . $imagem->imagem),
                ];
            }),
            'downloads' => $downloadsCombinados->map(function ($download) {
                return [
                    'id' => $download->id,
                    'titulo' => $download->titulo,
                    'imagem' => $download->imagem ? asset('content/downloads/preview/' . $download->imagem) : null,
                ];
            })
        ];

        return Inertia::render('Produto', [
            'produto' => $produtoData,
            'outrosProdutos' => $outrosProdutos,
            'pagina' => $pagina
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function opcionais($segmento = null, $slug = null) {
        if(!$slug || !$segmento) {
            return Inertia::location(route('Home.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $segmentoItem = Segmento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'slug' => $segmento
            ])
            ->first();

        if(!$segmento) {
            return Inertia::location(route('Home.index'));
        }

        $produto = Produto::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'slug' => $slug
            ])
            ->whereHas('produtosCategorias.segmento', function ($q) use ($segmento) {
                $q->where([
                    'excluido' => NULL,
                    'visivel' => true,
                    'slug' => $segmento
                ]);
            })
            ->with([
                'produtosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'opcionais' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'opcionaisIdiomas' => function ($qi) use ($idioma) {
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

        if(!$produto) {
            return Inertia::location(route('Home.index'));
        }

        $pagina = new Pagina;

        $pagina->titulo = $produto->produtosIdiomas[0]->titulo_pagina . ' | Águia Inox';
        $pagina->descricao = $produto->produtosIdiomas[0]->descricao_pagina . ' | Águia Inox';
        $pagina->titulo_compartilhamento = $produto->produtosIdiomas[0]->titulo_pagina . ' | Águia Inox';
        $pagina->descricao_compartilhamento = $produto->produtosIdiomas[0]->descricao_pagina . ' | Águia Inox';

        list($width, $height, $type, $attr) = getimagesize(public_path('/content/products/thumbs/b/' . $produto->imagem));

        $pagina->imagem = [
            'endereco' => '/content/products/thumbs/b/' . $produto->imagem,
            'tipo' => image_type_to_mime_type($type),
            'largura' => $width,
            'altura' => $height,
        ];

        $outrosProdutos = Produto::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                ['slug', '!=', $slug]
            ])
            // ->whereHas('produtosCategorias.segmento', function ($q) use ($segmento) {
            //     $q->where([
            //         'excluido' => NULL,
            //         'visivel' => true,
            //         'slug' => $segmento
            //     ]);
            // })
            ->with([
                'produtosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'produtosCategorias' => function ($q) use ($idioma, $segmento) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->whereHas('segmento', function ($query) use ($segmento) {
                        $query->where([
                            'excluido' => NULL,
                            'visivel' => true,
                            'slug' => $segmento
                        ]);
                    })
                    ->with('produtosCategoriasIdiomas', function ($query) use ($idioma) {
                        $query->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                              ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    });
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($outroProduto) {
                return [
                    'id' => $outroProduto->id,
                    'segmento' => $outroProduto->produtosCategorias[0]->segmento->slug ?? null,
                    'nome' => $outroProduto->produtosIdiomas->first()?->nome,
                    'descricao' => $outroProduto->produtosIdiomas->first()?->descricao,
                    'imagem' => rafator('content/products/thumbs/b/' . $outroProduto->imagem),
                    'slug' => $outroProduto->slug
                ];
            });

        $produtoData = [
            'id' => $produto->id,
            'slug' => $produto->slug,
            'segmento' => $produto->produtosCategorias[0]->segmento->slug,
            'nome' => $produto->produtosIdiomas->first()?->nome,
            'categoria' => $produto->produtosCategorias->first()?->produtosCategoriasIdiomas->first()?->nome,
            'descricao' => $produto->produtosIdiomas->first()?->descricao,
            'imagem' => rafator('content/products/thumbs/b/' . $produto->imagem),
            'opcionais' => $produto->opcionais->map(function ($opcional) {
                return [
                    'id' => $opcional->id,
                    'slug' => $opcional->slug,
                    'titulo' => $opcional->opcionaisIdiomas->first()?->titulo,
                    'texto' => $opcional->opcionaisIdiomas->first()?->texto,
                ];
            })
        ];

        return Inertia::render('Opcionais', [
            'produto' => $produtoData,
            'outrosProdutos' => $outrosProdutos,
            'pagina' => $pagina
        ]);
    }
    
    /**
     * Download the specified file.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadArquivo($segmento = null, $slug = null, $id = null)
    {
        if(!$slug || !$segmento || $id) {
            return Inertia::location(route('Home.index'));
        }

        $download = Download::findOrFail($id);
        
        if ($segmento && $slug) {
            $produto = Produto::where('slug', $slug)
                ->whereHas('categorias.segmento', function($query) use ($segmento) {
                    $query->where('slug', $segmento);
                })
                ->firstOrFail();
                
            $isValid = false;
            
            $extension = pathinfo($download->arquivo, PATHINFO_EXTENSION);

            switch ($download->relacionavel_type) {
                case 'App\Models\Produto':
                    $isValid = $download->relacionavel_id == $produto->id;
                    break;
                    
                case 'App\Models\ProdutoCategoria':
                    $isValid = $produto->categorias->contains('id', $download->relacionavel_id);
                    break;
                    
                case 'App\Models\Segmento':
                    $isValid = $produto->categorias->contains('segmento_id', $download->relacionavel_id);
                    break;
            }
            
            if (!$isValid) {
                abort(404, 'Download não disponível para este produto.');
            }
        }
        
        $filePath = public_path('content/downloads/' . $download->arquivo);
        
        if (!file_exists($filePath)) {
            abort(404, 'Arquivo não encontrado.');
        }
        
        return response()->download($filePath, $download->titulo . '.' . $extension);
    }
};