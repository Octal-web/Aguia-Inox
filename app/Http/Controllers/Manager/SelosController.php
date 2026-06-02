<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Selo;
use App\Models\SeloIdioma;
use App\Models\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostStampRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class SelosController extends Controller
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

        return Inertia::render('Manager/Selos/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostStampRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $selo = new Selo;
            $selo_idioma = new SeloIdioma;
            
            $selo->selo = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img_stamp')->extension());
            $selo->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            $selo->video = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('vid')->extension());

            $response = $selo->save();

            $selo_idioma->nome = $request->nome;
            $selo_idioma->descricao = $request->descricao;

            $selo_idioma->selo_id = $selo->id;
            $selo_idioma->idioma_id = $idioma->id;

            $response = $selo_idioma->save();

            if ($response) {
                $image = $request->file('img_stamp')->move(public_path('content/stamps/thumbs/'), $selo->imagem);
                $image = $request->file('img')->move(public_path('content/stamps/poster/'), $selo->imagem);
                $image = $request->file('vid')->move(public_path('content/stamps/video/'), $selo->video);

                return to_route('Manager.Institucional.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Manager.Institucional.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $selo = Selo::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'selosIdiomas' => function ($q) use ($idioma) {
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

        if(!$selo) {
            return Inertia::location(route('Manager.Institucional.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $selo = [
            'id' => $selo->id,
            'imagem' => asset('content/stamps/poster/' . $selo->imagem),
            'selo' => asset('content/stamps/thumbs/' . $selo->selo),
            'nome' => count($selo->selosIdiomas) ? $selo->selosIdiomas[0]->nome : null,
            'descricao' => count($selo->selosIdiomas) ? $selo->selosIdiomas[0]->descricao : null,
        ];

        return Inertia::render('Manager/Selos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'selo' => $selo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostStampRequest $request, $id) {
        if($request->ajax()){
            $selo = Selo::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $selo_idioma = SeloIdioma::query()
                ->where([
                    'excluido' => null,
                    'selo_id' => $selo->id
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

            if (!$selo) {
                return to_route('Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($selo, 'selosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Institucional.index'));
            }

            if (!$selo_idioma) {
                $selo_idioma = new SeloIdioma;

                $selo_idioma->selo_id = $selo->id;
                $selo_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $seloOriginal = $copier->copy($selo);
            }

            if ($request->file('img_stamp') && $request->file('img_stamp')->getError() == 0) {
                $selo->selo = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img_stamp')->extension());
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $selo->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            if ($request->file('vid') && $request->file('vid')->getError() == 0) {
                $selo->video = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('vid')->extension());
            }

            $selo_idioma->nome = $request->nome;
            $selo_idioma->descricao = $request->descricao;

            $response = $selo->save();
            $response = $selo_idioma->save();

            if ($response) {
                if ($request->file('img_stamp') && $request->file('img_stamp')->getError() == 0) {
                    if ($selo->selo && isset($seloOriginal) && File::exists('content/stamps/thumbs/' . $seloOriginal->selo)) {
                        File::delete('content/stamps/thumbs/' . $seloOriginal->selo);
                    }

                    $image = $request->file('img_stamp')->move(public_path('content/stamps/thumbs/'), $selo->imagem);
                }
                
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($selo->imagem && isset($seloOriginal) && File::exists('content/stamps/poster/' . $seloOriginal->imagem)) {
                        File::delete('content/stamps/poster/' . $seloOriginal->imagem);
                    }

                    $image = $request->file('img')->move(public_path('content/stamps/poster/'), $selo->imagem);
                }
                
                if ($request->file('vid') && $request->file('vid')->getError() == 0) {
                    if ($selo->video && isset($seloOriginal) && File::exists('content/stamps/video/' . $seloOriginal->video)) {
                        File::delete('content/stamps/video/' . $seloOriginal->video);
                    }

                    $image = $request->file('vid')->move(public_path('content/stamps/video/'), $selo->video);
                }

                return to_route('Manager.Institucional.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Institucional.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Selo::query()
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

            $response = Selo::query()
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
                    $registro = Selo::query()
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