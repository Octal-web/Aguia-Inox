<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Segmento;
use App\Models\ProdutoCategoria;
use App\Models\ProdutoCategoriaIdioma;
use App\Models\Idioma;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostProductCategoryRequest;

use Carbon\Carbon;

class ProdutosCategoriasController extends Controller
{
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
        
        $segmentos = Segmento::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->with([
                'segmentosIdiomas' => function ($q) use ($idioma) {
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
            ])
            ->get()
            ->map(function($segmento) {
                return [
                    'value' => $segmento->id,
                    'label' => $segmento->segmentosIdiomas->isNotEmpty() ? $segmento->segmentosIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Manager/Produtos/Categorias/adicionar', [
            'segmentos' => $segmentos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostProductCategoryRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $categoria = new ProdutoCategoria;
            $categoria_idioma = new ProdutoCategoriaIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (ProdutoCategoria::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $categoria->slug = $slug;
            $categoria->segmento_id = $request->segmento_id;
            
            $response = $categoria->save();

            $categoria_idioma->nome = $request->nome;

            $categoria_idioma->produto_categoria_id = $categoria->id;
            $categoria_idioma->idioma_id = $idioma->id;

            $response = $categoria_idioma->save();

            if ($response) {
                return to_route('Manager.Segmentos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Manager.Segmentos.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $categoria = ProdutoCategoria::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'produtosCategoriasIdiomas' => function ($q) use ($idioma) {
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
            ])
            ->first();

        if(!$categoria) {
            return Inertia::location(route('Manager.Segmentos.index'));
        }

        $segmentos = Segmento::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->with([
                'segmentosIdiomas' => function ($q) use ($idioma) {
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
            ])
            ->get()
            ->map(function($segmento) {
                return [
                    'value' => $segmento->id,
                    'label' => $segmento->segmentosIdiomas->isNotEmpty() ? $segmento->segmentosIdiomas[0]->nome : null,
                ];
            });


        $idioma = inertia()->getShared('idioma');

        $categoria = [
            'id' => $categoria->id,
            'segmento_id' => $categoria->segmento_id,
            'nome' => count($categoria->produtosCategoriasIdiomas) ? $categoria->produtosCategoriasIdiomas[0]->nome : null
        ];

        return Inertia::render('Manager/Produtos/Categorias/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'categoria' => $categoria,
            'segmentos' => $segmentos
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostProductCategoryRequest $request, $id) {
        if($request->ajax()){
            $categoria = ProdutoCategoria::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $categoria_idioma = ProdutoCategoriaIdioma::query()
                ->where([
                    'excluido' => null,
                    'produto_categoria_id' => $categoria->id
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

            if (!$categoria) {
                return to_route('Manager.Segmentos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($categoria, 'produtosCategoriasIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Segmentos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Segmentos.index'));
            }

            if (!$categoria_idioma) {
                $categoria_idioma = new ProdutoCategoriaIdioma;

                $categoria_idioma->produto_categoria_id = $categoria->id;
                $categoria_idioma->idioma_id = $idioma;
            }

            $slug = $categoria->slug;
            $categoria->segmento_id = $request->segmento_id;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $categoria_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (ProdutoCategoria::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $categoria->slug = $slug;

            $categoria_idioma->nome = $request->nome;

            $response = $categoria->save();
            $response = $categoria_idioma->save();

            if ($response) {
                return to_route('Manager.Segmentos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Segmentos.index')->with('error', ['type' => 'success', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = ProdutoCategoria::query()
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

            $response = ProdutoCategoria::query()
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
                    $registro = ProdutoCategoria::query()
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