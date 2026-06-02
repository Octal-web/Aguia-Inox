<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Segmento;
use App\Models\SegmentoIdioma;
use App\Models\ProdutoCategoria;
use App\Models\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostSegmentRequest;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class SegmentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $segmentos = Segmento::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'segmentosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->get()
            ->map(function($segmento) {
                return [
                    'id' => $segmento->id,
                    'visivel' => $segmento->visivel,
                    'imagem' => rafator('content/sectors/thumbs/' . $segmento->imagem),
                    'nome' => $segmento->segmentosIdiomas->isNotEmpty() ? $segmento->segmentosIdiomas[0]->nome : null,
                ];
            });

        $produtosCategorias = ProdutoCategoria::query()
            ->where([
                'excluido' => NULL
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
                    'id' => $categoria->id,
                    'visivel' => $categoria->visivel,
                    'nome' => $categoria->produtosCategoriasIdiomas->isNotEmpty() ? $categoria->produtosCategoriasIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Manager/Segmentos/index', [
            'segmentos' => $segmentos,
            'produtosCategorias' => $produtosCategorias
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

        return Inertia::render('Manager/Segmentos/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostSegmentRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $segmento = new Segmento;
            $segmento_idioma = new SegmentoIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (Segmento::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $segmento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            $segmento->banner = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img_banner')->extension());
            $segmento->slug = $slug;
            $segmento->destaque = $request->destaque ? true : false;

            $response = $segmento->save();

            $segmento_idioma->nome = $request->nome;
            $segmento_idioma->descricao = $request->descricao;
            $segmento_idioma->titulo_pagina = $request->titulo_pagina;
            $segmento_idioma->descricao_pagina = $request->descricao_pagina;

            $segmento_idioma->segmento_id = $segmento->id;
            $segmento_idioma->idioma_id = $idioma->id;

            $response = $segmento_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('content/sectors/thumbs/'), $segmento->imagem);
                
                $image = $request->file('img_banner')->move(public_path('content/sectors/banner/'), $segmento->banner);

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

        $segmento = Segmento::query()
            ->where([
                'excluido' => null,
                'id' => $id
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
            ->first();

        if(!$segmento) {
            return Inertia::location(route('Manager.Segmentos.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $segmento = [
            'id' => $segmento->id,
            'imagem' => asset('content/sectors/thumbs/' . $segmento->imagem),
            'banner' => asset('content/sectors/banner/' . $segmento->banner),
            'nome' => count($segmento->segmentosIdiomas) ? $segmento->segmentosIdiomas[0]->nome : null,
            'descricao' => count($segmento->segmentosIdiomas) ? $segmento->segmentosIdiomas[0]->descricao : null,
            'destaque' => $segmento->destaque ? true : false,
            'titulo_pagina' => count($segmento->segmentosIdiomas) ? $segmento->segmentosIdiomas[0]->titulo_pagina : null,
            'descricao_pagina' => count($segmento->segmentosIdiomas) ? $segmento->segmentosIdiomas[0]->descricao_pagina : null,
        ];

        return Inertia::render('Manager/Segmentos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'segmento' => $segmento
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostSegmentRequest $request, $id) {
        if($request->ajax()){
            $segmento = Segmento::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $segmento_idioma = SegmentoIdioma::query()
                ->where([
                    'excluido' => null,
                    'segmento_id' => $segmento->id
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

            if (!$segmento) {
                return to_route('Manager.Segmentos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($segmento, 'segmentosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Segmentos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Segmentos.index'));
            }

            if (!$segmento_idioma) {
                $segmento_idioma = new SegmentoIdioma;

                $segmento_idioma->segmento_id = $segmento->id;
                $segmento_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $segmentoOriginal = $copier->copy($segmento);
            }

            $slug = $segmento->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $segmento_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (Segmento::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $segmento->slug = $slug;
            $segmento->destaque = $request->destaque ? true : false;

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $segmento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }
            
            if ($request->file('img_banner') && $request->file('img_banner')->getError() == 0) {
                $segmento->banner = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img_banner')->extension());
            }

            $segmento_idioma->nome = $request->nome;
            $segmento_idioma->descricao = $request->descricao;
            $segmento_idioma->titulo_pagina = $request->titulo_pagina;
            $segmento_idioma->descricao_pagina = $request->descricao_pagina;

            $response = $segmento->save();
            $response = $segmento_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($segmento->imagem && isset($segmentoOriginal) && File::exists('content/sectors/thumbs/' . $segmentoOriginal->imagem)) {
                        File::delete('content/sectors/thumbs/' . $segmentoOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('content/sectors/thumbs/'), $segmento->imagem);
                }

                if ($request->file('img_banner') && $request->file('img_banner')->getError() == 0) {
                    if ($segmento->banner && isset($segmentoOriginal) && File::exists('content/sectors/banner/' . $segmentoOriginal->banner)) {
                        File::delete('content/sectors/banner/' . $segmentoOriginal->banner);
                    }

                    $image = $request->file('img_banner')->move(public_path('content/sectors/banner/'), $segmento->banner);
                }

                return to_route('Manager.Segmentos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Segmentos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Segmento::query()
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

            $response = Segmento::query()
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
                    $registro = Segmento::query()
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