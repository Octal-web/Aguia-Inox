<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostDownloadRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class DownloadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tiposEsperados = ['segmento', 'produtocategoria', 'produto'];

        $downloads = Download::query()
            ->where([
                'excluido' => null
            ])
            ->get();

        $downloadsAgrupados = $downloads->groupBy(function ($download) {
            return strtolower(class_basename($download->relacionavel_type));
        });

        $resultado = [];

        foreach ($tiposEsperados as $tipo) {
            $resultado[$tipo] = $downloadsAgrupados->get($tipo, collect())->map(function ($download) {
                return [
                    'id' => $download->id,
                    'titulo' => $download->titulo,
                    'visivel' => $download->visivel ?? null,
                ];
            })->values();
        }

        return Inertia::render('Manager/Downloads/index', [
            'downloads' => $resultado
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar($tipo = null) {
        if (!in_array($tipo, ['segmento', 'categoria', 'produto'])) {
            return Inertia::location(route('Manager.Downloads.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $dados = collect();

        switch (strtolower($tipo)) {
            case 'segmento':
                $dados = \App\Models\Segmento::query()
                    ->where([
                        'excluido' => NULL,
                        'visivel' => true
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
                    ->map(function($categoria) {
                        return [
                            'value' => $categoria->id,
                            'label' => $categoria->segmentosIdiomas->isNotEmpty() ? $categoria->segmentosIdiomas[0]->nome : null,
                        ];
                    });
                break;

            case 'categoria':
                $dados = \App\Models\ProdutoCategoria::query()
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
                        'produtosCategoriasIdiomas' => function ($q) use ($idioma) {
                            $q->whereHas('idiomas', function ($r) use ($idioma) {
                                $r->where('codigo', $idioma)
                                ->orWhere('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        }
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
                break;

            case 'produto':
                $dados = \App\Models\Produto::query()
                    ->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'produtosIdiomas' => function ($q) use ($idioma) {
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
                            'label' => $categoria->produtosIdiomas->isNotEmpty() ? $categoria->produtosIdiomas[0]->nome : null,
                        ];
                    });
                break;
        }

        $idioma = inertia()->getShared('idioma');

        return Inertia::render('Manager/Downloads/adicionar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'dados' => $dados,
            'tipo' => $tipo,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostDownloadRequest $request, $tipo = null) {
        if (!in_array($tipo, ['segmento', 'categoria', 'produto'])) {
            return Inertia::location(route('Manager.Downloads.index'));
        }

        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $modelMap = [
                'segmento' => \App\Models\Segmento::class,
                'categoria' => \App\Models\ProdutoCategoria::class,
                'produto'   => \App\Models\Produto::class,
            ];
            
            $classeRelacionavel = $modelMap[$tipo];

            $download = new Download;
                
            $download->titulo = $request->titulo;
            $download->relacionavel_type = $classeRelacionavel;
            $download->relacionavel_id = $request->tipo_id;
            
            $download->arquivo = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('arq')->extension());
            
            if ($request->file('img') && $request->file('img')->isValid()) {
                $download->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $response = $download->save();

            if ($response) {
                $arquivo = $request->file('arq')->move(public_path('content/downloads/files/'), $download->arquivo);
                
                if ($request->file('img') && $request->file('img')->isValid()) {
                    $imagem = $request->file('img')->move(public_path('content/downloads/preview/'), $download->imagem);
                }

                return to_route('Manager.Downloads.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Manager.Downloads.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $download = Download::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->first();

        if(!$download) {
            return Inertia::location(route('Manager.Downloads.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $map = [
        'App\\Models\\Segmento' => 'segmento',
        'App\\Models\\ProdutoCategoria' => 'categoria',
        'App\\Models\\Produto' => 'produto',
    ];

    $tipo = $map[$download->relacionavel_type] ?? null;

    if (!$tipo) {
        return Inertia::location(route('Manager.Downloads.index'));
    }

    $idiomas = Idioma::query()
        ->orderBy('padrao', 'DESC')
        ->orderBy('id', 'DESC')
        ->get();

    $idioma = request('lang') ?? inertia()->getShared('idioma');

    $dados = collect();

    switch ($tipo) {
        case 'segmento':
            $dados = \App\Models\Segmento::query()
                ->where([
                    'excluido' => NULL,
                    'visivel' => true
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
                ->map(function($categoria) {
                    return [
                        'value' => $categoria->id,
                        'label' => $categoria->segmentosIdiomas->isNotEmpty() ? $categoria->segmentosIdiomas[0]->nome : null,
                    ];
                });
            break;

        case 'categoria':
            $dados = \App\Models\ProdutoCategoria::query()
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
                    'produtosCategoriasIdiomas' => function ($q) use ($idioma) {
                        $q->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    }
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
            break;

        case 'produto':
            $dados = \App\Models\Produto::query()
                ->where([
                    'excluido' => NULL,
                    'visivel' => true
                ])
                ->with([
                    'produtosIdiomas' => function ($q) use ($idioma) {
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
                        'label' => $categoria->produtosIdiomas->isNotEmpty() ? $categoria->produtosIdiomas[0]->nome : null,
                    ];
                });
            break;
        }

        $downloadData = [
            'id' => $download->id,
            'titulo' => $download->titulo,
            'tipo_id' => $download->relacionavel_id,
            'imagem' => $download->imagem ? asset('content/downloads/preview/' . $download->imagem) : null
        ];

        return Inertia::render('Manager/Downloads/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'download' => $downloadData,
            'dados' => $dados,
            'tipo' => $tipo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostDownloadRequest $request, $id) {
        if($request->ajax()){
            $download = Download::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            if (!$download) {
                return to_route('Manager.Downloads.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $copier = new DeepCopy();
            $downloadOriginal = $copier->copy($download);
        
            if ($request->file('arq') && $request->file('arq')->isValid()) {
                $download->arquivo = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('arq')->extension());
            }

            if ($request->file('img') && $request->file('img')->isValid()) {
                $download->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $download->titulo = $request->titulo;
            $download->relacionavel_id = $request->tipo_id;
            $download->titulo = $request->titulo;

            $response = $download->save();

            if ($response) {
                if ($request->file('arq') && $request->file('arq')->isValid()) {
                    if ($download->arquivo && isset($downloadOriginal) && File::exists(public_path('content/downloads/files/' . $downloadOriginal->arquivo))) {
                        File::delete(public_path('content/downloads/files/' . $downloadOriginal->arquivo));
                    }
                    $request->file('arq')->move(public_path('content/downloads/files/'), $download->arquivo);
                }
                
                if ($request->file('img') && $request->file('img')->isValid()) {
                    if ($download->imagem && isset($downloadOriginal) && File::exists(public_path('content/downloads/preview/' . $downloadOriginal->imagem))) {
                        File::delete(public_path('content/downloads/preview/' . $downloadOriginal->imagem));
                    }
                    $request->file('img')->move(public_path('content/downloads/preview/'), $download->imagem);
                }

                return to_route('Manager.Downloads.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Downloads.index')->with('error', ['type' => 'success', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Download::query()
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

            $response = Download::query()
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
                    $registro = Download::query()
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
    
    /**
     * Download the file of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function baixarArquivo($id) {
        if (!$id) {
            return redirect()->route('Manager.Home.index');
        }

        $download = Download::query()
            ->where([
                'id' => $id,
                'excluido' => NULL,
            ])
            ->first();

        if (!$download) {
            return redirect()->route('Manager.Downloads.index');
        }

        $caminho = public_path('content/downloads/files/' . $download->arquivo);

        $extensao = pathinfo($caminho)['extension'];

        if (!File::exists($caminho)) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Não foi possível encontrar o arquivo!']);
        }

        return response()->download($caminho, $download->titulo . '.' . $extensao);
    }
}