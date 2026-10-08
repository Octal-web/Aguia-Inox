<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\OpcionalCategoria;
use App\Models\Opcional;
use App\Models\OpcionalIdioma;
use App\Models\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostOptionalRequest;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class OpcionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $categorias = OpcionalCategoria::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'opcionaisCategoriasIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($opcional) {
                return [
                    'id' => $opcional->id,
                    'visivel' => $opcional->visivel,
                    'nome' => $opcional->opcionaisCategoriasIdiomas->isNotEmpty() ? $opcional->opcionaisCategoriasIdiomas[0]->nome : null
                ];
            });

        $opcionais = Opcional::query()
            ->where([
                'excluido' => NULL
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
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($opcional) {
                return [
                    'id' => $opcional->id,
                    'visivel' => $opcional->visivel,
                    'titulo' => $opcional->opcionaisIdiomas->isNotEmpty() ? $opcional->opcionaisIdiomas[0]->titulo : null
                ];
            });

        return Inertia::render('Manager/Opcionais/index', [
            'categorias' => $categorias,
            'opcionais' => $opcionais,
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

        $categorias = OpcionalCategoria::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'opcionaisCategoriasIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($opcional) {
                return [
                    'value' => $opcional->id,
                    'label' => $opcional->opcionaisCategoriasIdiomas->isNotEmpty() ? $opcional->opcionaisCategoriasIdiomas[0]->nome : null
                ];
            });

        return Inertia::render('Manager/Opcionais/adicionar', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostOptionalRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $opcional = new Opcional;
            $opcional_idioma = new OpcionalIdioma;

            $slugBase = Str::slug($request['titulo']);
            $slug = $slugBase;

            $count = 1;

            while (Opcional::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $opcional->slug = $slug;
            $opcional->opcional_categoria_id = $request->opcional_categoria_id;

            $response = $opcional->save();

            $opcional_idioma->titulo = $request->titulo;

            $opcional_idioma->opcional_id = $opcional->id;
            $opcional_idioma->idioma_id = $idioma->id;

            $response = $opcional_idioma->save();

            if ($response) {
                return to_route('Manager.Opcionais.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Manager.Opcionais.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $opcional = Opcional::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'opcionaisIdiomas' => function ($q) use ($idioma) {
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

        if(!$opcional) {
            return Inertia::location(route('Manager.Opcionais.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $opcional = [
            'id' => $opcional->id,
            'opcional_categoria_id' => $opcional->opcional_categoria_id,
            'titulo' => count($opcional->opcionaisIdiomas) ? $opcional->opcionaisIdiomas[0]->titulo : null,
        ];

        $categorias = OpcionalCategoria::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'opcionaisCategoriasIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($opcional) {
                return [
                    'value' => $opcional->id,
                    'label' => $opcional->opcionaisCategoriasIdiomas->isNotEmpty() ? $opcional->opcionaisCategoriasIdiomas[0]->nome : null
                ];
            });

        return Inertia::render('Manager/Opcionais/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'opcional' => $opcional,
            'categorias' => $categorias
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostOptionalRequest $request, $id) {
        if($request->ajax()){
            $opcional = opcional::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $opcional_idioma = OpcionalIdioma::query()
                ->where([
                    'excluido' => null,
                    'opcional_id' => $opcional->id
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

            if (!$opcional) {
                return to_route('Manager.Opcionais.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($opcional, 'opcionaisIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Opcionais.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Opcionais.index'));
            }

            if (!$opcional_idioma) {
                $opcional_idioma = new OpcionalIdioma;

                $opcional_idioma->opcional_id = $opcional->id;
                $opcional_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $opcionalOriginal = $copier->copy($opcional);
            }
            
            $slug = $opcional->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $opcional_idioma->titulo) {
                    $slugBase = Str::slug($request['titulo']);
                    $slug = $slugBase;
                    $count = 1;

                    while (Opcional::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $opcional->slug = $slug;
            $opcional->opcional_categoria_id = $request->opcional_categoria_id;

            $opcional_idioma->titulo = $request->titulo;

            $response = $opcional->save();
            $response = $opcional_idioma->save();

            if ($response) {
                return to_route('Manager.Opcionais.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Opcionais.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Opcional::query()
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

            $response = Opcional::query()
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
                    $registro = Opcional::query()
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
}