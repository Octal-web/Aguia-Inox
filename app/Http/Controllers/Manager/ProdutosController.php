<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Produto;
use App\Models\ProdutoIdioma;
use App\Models\ProdutoCategoria;
use App\Models\Opcional;
use App\Models\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostProductRequest;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $produtos = Produto::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'produtosIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->get()
            ->map(function($produto) {
                return [
                    'id' => $produto->id,
                    'visivel' => $produto->visivel,
                    'imagem' => rafator('content/products/thumbs/b/' . $produto->imagem),
                    'nome' => $produto->produtosIdiomas->isNotEmpty() ? $produto->produtosIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Manager/Produtos/index', [
            'produtos' => $produtos
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar() {
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');
        
        $produtosCategorias = ProdutoCategoria::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'produtosCategoriasIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->get()
            ->map(function($categoria) {
                return [
                    'value' => $categoria->id,
                    'label' => $categoria->produtosCategoriasIdiomas->isNotEmpty() ? $categoria->produtosCategoriasIdiomas[0]->nome : null,
                ];
            });
        
        $opcionais = Opcional::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'opcionaisIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->get()
            ->map(function($opcional) {
                return [
                    'value' => $opcional->id,
                    'label' => $opcional->opcionaisIdiomas->isNotEmpty() ? $opcional->opcionaisIdiomas[0]->titulo : null,
                ];
            });

        return Inertia::render('Manager/Produtos/adicionar', [
            'produtosCategorias' => $produtosCategorias,
            'opcionais' => $opcionais
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostProductRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $produto = new Produto;
            $produto_idioma = new ProdutoIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (Produto::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $produto->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            
            $produto->slug = $slug;
            $produto->video = $request->video;

            $response = $produto->save();
            
            if ($request->filled('produtos_categorias')) {
                $produto->produtosCategorias()->sync($request->input('produtos_categorias'));
            }
            
            if ($request->filled('opcionais')) {
                $produto->opcionais()->sync($request->input('opcionais'));
            }

            $produto_idioma->nome = $request->nome;
            $produto_idioma->descricao = $request->descricao;
            $produto_idioma->titulo_pagina = $request->titulo_pagina;
            $produto_idioma->descricao_pagina = $request->descricao_pagina;

            $produto_idioma->produto_id = $produto->id;
            $produto_idioma->idioma_id = $idioma->id;

            $response = $produto_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('content/products/thumbs/b/'), $produto->imagem);
                $image = $request->file('img_alt')->move(public_path('content/products/thumbs/s/'), $produto->imagem);

                return to_route('Manager.Produtos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id) {
        if (!$id) {
            return Inertia::location(route('Manager.Produtos.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $produto = Produto::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'produtosIdiomas' => function ($q) use ($idioma) {
                    $q->when($idioma, function ($r) use($idioma) {
                        $r->whereHas('idiomas', function($query) use($idioma) {
                            $query->where('codigo', $idioma);
                        });
                    })
                    ->when(!$idioma, function ($r) {
                        $r->whereHas('idiomas', function($query) {
                            $query->where('padrao', true);
                        });
                    });
                },
                'produtosCategorias' => function ($q) {
                    $q->where([
                        'excluido' => null,
                        'visivel' => true
                    ])
                    ->orderBy('ordem', 'DESC')
                    ->orderBy('id', 'DESC');
                },
                'opcionais' => function ($q) {
                    $q->where([
                        'excluido' => null,
                        'visivel' => true
                    ])
                    ->orderBy('ordem', 'DESC')
                    ->orderBy('id', 'DESC');
                }
            ])
            ->first();

        if(!$produto) {
            return Inertia::location(route('Manager.Produtos.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $produtoData = [
            'id' => $produto->id,
            'imagem' => asset('content/products/thumbs/b/' . $produto->imagem),
            'nome' => count($produto->produtosIdiomas) ? $produto->produtosIdiomas[0]->nome : null,
            'descricao' => count($produto->produtosIdiomas) ? $produto->produtosIdiomas[0]->descricao : null,
            'video' => $produto->video,
            'titulo_pagina' => count($produto->produtosIdiomas) ? $produto->produtosIdiomas[0]->titulo_pagina : null,
            'descricao_pagina' => count($produto->produtosIdiomas) ? $produto->produtosIdiomas[0]->descricao_pagina : null,
            'produtos_categorias' => $produto->produtosCategorias->pluck('id'),
            'opcionais' => $produto->opcionais->pluck('id'),
        ];
        
        $produtosCategorias = ProdutoCategoria::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->with([
                'segmento' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'segmentosIdiomas' => function ($qi) use ($idioma) {
                            $qi->whereHas('idiomas', function ($ri) use ($idioma) {
                                $ri->where('codigo', $idioma)
                                   ->orWhere('padrao', true);
                            })->orderBy('idioma_id', 'DESC');
                        }
                    ]);
                },
                'produtosCategoriasIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
            ])
            ->get()
            ->groupBy(function($categoria) {
                return $categoria->segmento ? $categoria->segmento->segmentosIdiomas[0]?->nome : 'Sem segmento';
            })
            ->map(function($categoriasPorSegmento, $segmentoNome) {
                return [
                    'label' => $segmentoNome,
                    'options' => $categoriasPorSegmento->map(function($categoria) {
                        return [
                            'value' => $categoria->id,
                            'label' => $categoria->produtosCategoriasIdiomas->isNotEmpty() ? $categoria->produtosCategoriasIdiomas[0]->nome : null,
                        ];
                    })->values(),
                ];
            })
            ->values();
            
        $opcionais = Opcional::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'opcionaisIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->get()
            ->map(function($opcional) {
                return [
                    'value' => $opcional->id,
                    'label' => $opcional->opcionaisIdiomas->isNotEmpty() ? $opcional->opcionaisIdiomas[0]->titulo : null,
                ];
            });

        return Inertia::render('Manager/Produtos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'produto' => $produtoData,
            'produtosCategorias' => $produtosCategorias,
            'opcionais' => $opcionais
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostProductRequest $request, $id) {
        if($request->ajax()){
            $produto = Produto::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $produto_idioma = ProdutoIdioma::query()
                ->where([
                    'excluido' => null,
                    'produto_id' => $produto->id
                ])
                ->when($idioma, function ($q) use($idioma) {
                    $q->whereHas('idiomas', function($query) use($idioma) {
                        $query->where('codigo', $idioma);
                    });
                })
                ->when(!$idioma, function ($q) {
                    $q->whereHas('idiomas', function($query) {
                        $query->where('padrao', true);
                    });
                })
                ->first();

            if (!$produto) {
                return to_route('Manager.Produtos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($produto, 'produtosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Produtos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Produtos.index'));
            }

            if (!$produto_idioma) {
                $produto_idioma = new ProdutoIdioma;

                $produto_idioma->produto_id = $produto->id;
                $produto_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $produtoOriginal = $copier->copy($produto);
            }

            $slug = $produto->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $produto_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (Produto::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $produto->slug = $slug;
            $produto->video = $request->video;

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $produto->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $produto_idioma->nome = $request->nome;
            $produto_idioma->descricao = $request->descricao;
            $produto_idioma->titulo_pagina = $request->titulo_pagina;
            $produto_idioma->descricao_pagina = $request->descricao_pagina;

            $response = $produto->save();
            $response = $produto_idioma->save();

            $produto->produtosCategorias()->sync($request->input('produtos_categorias', []));
            
            $produto->opcionais()->sync($request->input('opcionais', []));

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($produto->imagem && isset($produtoOriginal) && File::exists('content/products/thumbs/b/' . $produtoOriginal->imagem)) {
                        File::delete('content/products/thumbs/b/' . $produtoOriginal->imagem);
                    }
                    
                    if ($produto->imagem && isset($produtoOriginal) && File::exists('content/products/thumbs/s/' . $produtoOriginal->imagem)) {
                        File::delete('content/products/thumbs/s/' . $produtoOriginal->imagem);
                    }                    

                    $image = $request->file('img')->move(public_path('content/products/thumbs/b/'), $produto->imagem);
                    $image = $request->file('img_alt')->move(public_path('content/products/thumbs/s/'), $produto->imagem);
                }

                return to_route('Manager.Produtos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Produtos.index')->with('error', ['type' => 'success', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
    }

    /**
     * Set the specified resource as deleted.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function excluir(Request $request, $id) {
        if ($request->ajax()){
            if (!$id) {
                return $request->header('referer');
            }

            $exclusao = Produto::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $id
                ])
                ->update([
                    'excluido' => Carbon::now()
                ]);

            if ($exclusao == true) {
                return redirect()->back()->with('message', ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.']);
            } else {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Não foi possível excluir o registro.']);
            }
        }
    }

    /**
     * Set the specified resource to visible/invisible.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visibilidade(Request $request, $id) {
        if ($request->ajax()){
            if (!$id) {
                return redirect()->back()->with(['type' => 'error', 'message' => 'Registro não encontrado!']);
            }

            $response = Produto::query()
                ->where([
                    'id' => $id,
                    'excluido' => NULL
                ])
                ->first();

            if (!$response) {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
            }
    
            $response->visivel = 1 - $response->visivel;
            $response->save();
    
            if ($response) {
                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Visibilidade alterada com sucesso!']);
            }
            else {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Visibilidade não alterada!']);
            }
        }

        return $request->header('referer');
    }

    /**
     * Update the order of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ordenar(Request $request) {
        if ($request->ajax()){
            $erros = [];

            if ($request->odr && is_array($request->odr)) {
                foreach ($request->odr as $key => $value) {
                    $registro = Produto::query()
                        ->where([
                            'excluido' => NULL,
                            'id' => $value
                        ])
                        ->update([
                            'ordem' => $key,
                        ]);

                    $errors[] = $registro;
                }
            }

            if (!count($erros)) {
                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registros reordenados com sucesso!']);
            } else {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registros não reordenados, tente novamente mais tarde!']);
            }
        }

        return redirect()->back();
    }
};