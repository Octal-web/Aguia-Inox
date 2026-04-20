<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Opcional;
use App\Models\OpcionalModelo;
use App\Models\OpcionalModeloIdioma;
use App\Models\Idioma;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostModelRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class OpcionaisModelosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id) {
        if (!$id) {
            return Inertia::location(route('Manager.Opcionais.index'));
        }

        $opcional = Opcional::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->with([
                'opcionaisIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'opcionaisModelos' => function ($q)  {
                    $q->where([
                        'excluido' => null
                    ])
                    ->with([
                        'opcionaisModelosIdiomas' => function ($secq) {
                            $secq->whereHas('idiomas', function ($secr) {
                                $secr->Where('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        }
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'DESC');
                },
            ])
            ->first();

        if(!$opcional) {
            return Inertia::location(route('Manager.Opcionais.index'));
        }

        $opcionalData = [
            'id' => $opcional->id,
            'titulo' => count($opcional->opcionaisIdiomas) ? $opcional->opcionaisIdiomas[0]->titulo : null,
            'modelos' => $opcional->opcionaisModelos->map(function ($opcional) {
                return [
                    'id' => $opcional->id,
                    'visivel' => $opcional->visivel ? true : false,
                    'imagem' => asset('content/optionals/models/' . $opcional->imagem),
                    'nome' => count($opcional->opcionaisModelosIdiomas) ? $opcional->opcionaisModelosIdiomas[0]->nome : null,
                ];
            })->values()->all(),
        ];

        return Inertia::render('Manager/Opcionais/Modelos/index', [
            'opcional' => $opcionalData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar($id) {
        if (!$id) {
            return Inertia::location(route('Manager.Opcionais.index'));
        }

        $opcional = Opcional::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->first();

        if(!$opcional) {
            return Inertia::location(route('Manager.Opcionais.index'));
        }

        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        return Inertia::render('Manager/Opcionais/Modelos/adicionar', [
            'opcional' => $opcional
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostModelRequest $request, $id) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $opcional_modelo = new OpcionalModelo;
            $opcional_modelo_idioma = new OpcionalModeloIdioma;

            $slugBase = Str::slug($request['nome']);
            $slug = $slugBase;

            $count = 1;

            while (OpcionalModelo::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count;
                $count++;
            }

            $opcional_modelo->slug = $slug;

            $opcional_modelo->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            $opcional_modelo->opcional_id = $id;

            $response = $opcional_modelo->save();

            $opcional_modelo_idioma->nome = $request->nome;
            $opcional_modelo_idioma->descricao = $request->descricao;

            $opcional_modelo_idioma->opcional_modelo_id = $opcional_modelo->id;
            $opcional_modelo_idioma->idioma_id = $idioma->id;

            $response = $opcional_modelo_idioma->save();

            if ($response) {
                $image = $request->file('img')->move(public_path('content/optionals/models/'), $opcional_modelo->imagem);

                return to_route('Manager.Opcionais.Modelos.index', ['id' => $id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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

        $opcional_modelo = OpcionalModelo::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'opcionaisModelosIdiomas' => function ($q) use ($idioma) {
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

        if(!$opcional_modelo) {
            return Inertia::location(route('Manager.Opcionais.index'));
        }

        $opcionais = Opcional::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'opcionaisIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
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
                    'label' => $opcional->opcionaisIdiomas->isNotEmpty() ? $opcional->opcionaisIdiomas[0]->titulo : null,
                ];
            });

        $idioma = inertia()->getShared('idioma');

        $opcional_modeloData = [
            'id' => $opcional_modelo->id,
            'opcional_id' => $opcional_modelo->opcional_id,
            'imagem' => asset('content/optionals/models/' . $opcional_modelo->imagem),
            'nome' => count($opcional_modelo->opcionaisModelosIdiomas) ? $opcional_modelo->opcionaisModelosIdiomas[0]->nome : null,
            'descricao' => count($opcional_modelo->opcionaisModelosIdiomas) ? $opcional_modelo->opcionaisModelosIdiomas[0]->descricao : null,
        ];

        return Inertia::render('Manager/Opcionais/Modelos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'opcional_modelo' => $opcional_modeloData,
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
    public function atualizar(PostModelRequest $request, $id) {
        if($request->ajax()){
            $opcional_modelo = OpcionalModelo::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $opcional_modelo_idioma = OpcionalModeloIdioma::query()
                ->where([
                    'excluido' => null,
                    'opcional_modelo_id' => $opcional_modelo->id
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

            if (!$opcional_modelo) {
                return to_route('Manager.Opcionais.Modelos.index', ['id' => $opcional_modelo->opcional_id])->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($opcional_modelo, 'opcionaisIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Opcionais.Modelos.index', ['id' => $opcional_modelo->opcional_id])->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Opcionais.index'));
            }

            if (!$opcional_modelo_idioma) {
                $opcional_modelo_idioma = new OpcionalModeloIdioma;

                $opcional_modelo_idioma->opcional_modelo_id = $opcional_modelo->id;
                $opcional_modelo_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $opcional_modeloOriginal = $copier->copy($opcional_modelo);
            }

            $slug = $opcional_modelo->slug;

            if (!$request->query('lang')) {
                if ($request['nome'] !== $opcional_modelo_idioma->nome) {
                    $slugBase = Str::slug($request['nome']);
                    $slug = $slugBase;
                    $count = 1;

                    while (OpcionalModelo::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                        $slug = $slugBase . '-' . $count;
                        $count++;
                    }
                }
            }

            $opcional_modelo->opcional_id = $request->opcional_id;
            $opcional_modelo->slug = $slug;

            if ($request->file('img') && $request->file('img')->isValid()) {
                $opcional_modelo->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $opcional_modelo_idioma->nome = $request->nome;
            $opcional_modelo_idioma->descricao = $request->descricao;

            $response = $opcional_modelo->save();
            $response = $opcional_modelo_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($opcional_modelo->imagem && isset($opcional_modeloOriginal) && File::exists('content/optionals/models/' . $opcional_modeloOriginal->imagem)) {
                        File::delete('content/optionals/models/' . $opcional_modeloOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('content/optionals/models/'), $opcional_modelo->imagem);
                }

                return to_route('Manager.Opcionais.Modelos.index', ['id' => $opcional_modelo->opcional_id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Opcionais.Modelos.index', ['id' => $opcional_modelo->opcional_id])->with('error', ['type' => 'success', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = OpcionalModelo::query()
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

            $response = OpcionalModelo::query()
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
                    $registro = OpcionalModelo::query()
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