<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\DepartamentoIdioma;
use App\Models\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostDepartamentRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class DepartamentosController extends Controller
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

        return Inertia::render('Manager/Departamentos/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostDepartamentRequest $request) {
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $departamento = new Departamento;
            $departamento_idioma = new DepartamentoIdioma;

            $response = $departamento->save();

            $departamento_idioma->nome = $request->nome;

            $departamento_idioma->departamento_id = $departamento->id;
            $departamento_idioma->idioma_id = $idioma->id;

            $response = $departamento_idioma->save();

            $emails = $request->input('emails', []);
            
            if (is_string($emails)) {
                $emails = array_map('trim', explode(',', $emails));
            }

            $emails = array_filter($emails, function($email) {
                return !empty(trim($email));
            });

            foreach ($emails as $endereco) {
                $departamento->departamentosEmails()->create([
                    'endereco' => trim($endereco)
                ]);
            }

            if ($response) {
                return to_route('Manager.Contato.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Manager.Contato.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $departamento = Departamento::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'departamentosIdiomas' => function ($q) use ($idioma) {
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
                'departamentosEmails' => function ($q) {
                    $q->where([
                        'excluido' => null
                    ]);
                }
            ])
            ->first();

        if(!$departamento) {
            return Inertia::location(route('Manager.Contato.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $departamento = [
            'id' => $departamento->id,
            'nome' => count($departamento->departamentosIdiomas) ? $departamento->departamentosIdiomas[0]->nome : null,
            'emails' => $departamento->departamentosEmails
                ->pluck('endereco')
                ->filter()
                ->values()
                ->toArray()
        ];
        return Inertia::render('Manager/Departamentos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'departamento' => $departamento
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostDepartamentRequest $request, $id) {
        if($request->ajax()){
            $departamento = Departamento::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $departamento_idioma = DepartamentoIdioma::query()
                ->where([
                    'excluido' => null,
                    'departamento_id' => $departamento->id
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

            if (!$departamento) {
                return to_route('Manager.Contato.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($departamento, 'departamentosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Contato.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Contato.index'));
            }

            if (!$departamento_idioma) {
                $departamento_idioma = new DepartamentoIdioma;

                $departamento_idioma->departamento_id = $departamento->id;
                $departamento_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $departamentoOriginal = $copier->copy($departamento);
            }

            $departamento_idioma->nome = $request->nome;;

            $response = $departamento->save();
            $response = $departamento_idioma->save();
            
            $novosEmails = $request->input('emails', []);

            if (is_string($novosEmails)) {
                $novosEmails = array_map('trim', explode(',', $novosEmails));
            }

            $novosEmails = array_filter($novosEmails, fn($e) => !empty(trim($e)));
            $novosEmails = array_map('strtolower', $novosEmails); // normaliza

            $emailsAtuais = $departamento->departamentosEmails()
                ->whereNull('excluido')
                ->pluck('endereco')
                ->map(fn($e) => strtolower(trim($e)))
                ->toArray();

            $emailsRemovidos = array_diff($emailsAtuais, $novosEmails);

            if (!empty($emailsRemovidos)) {
                $departamento->departamentosEmails()
                    ->whereIn('endereco', $emailsRemovidos)
                    ->whereNull('excluido')
                    ->update(['excluido' => now()]);
            }

            $emailsNovos = array_diff($novosEmails, $emailsAtuais);

            foreach ($emailsNovos as $email) {
                $departamento->departamentosEmails()->create([
                    'endereco' => $email,
                    'excluido' => null,
                ]);
            }

            if ($response) {
                return to_route('Manager.Contato.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Contato.index')->with('error', ['type' => 'success', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Departamento::query()
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

            $response = Departamento::query()
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
                    $registro = Departamento::query()
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