<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Parceiro;

use Illuminate\Http\Request;
use Inertia\Inertia;

use Carbon\Carbon;
use DeepCopy\DeepCopy;

class ParceriasController extends Controller
{
    public function visualizar($id) {
        if (!$id) {
            return Inertia::location(route('Manager.Contato.index'));
        }
        
        $parceiro = Parceiro::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->first();

        if(!$parceiro) {
            return Inertia::location(route('Manager.Contato.index'));
        }

        $parceiro = [
            'id' => $parceiro->id,
            'email' => $parceiro->email,
            'cnpj' => $parceiro->cnpj,
            'telefone' => $parceiro->telefone,
            'cargo' => $parceiro->cargo,
            'assunto' => $parceiro->assunto,
            'mensagem' => $parceiro->mensagem,
            'data' => $parceiro->criado->format('d/m/Y H:i')
        ];

        return Inertia::render('Manager/Parcerias/visualizar', [
            'parceiro' => $parceiro
        ]);
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

            $exclusao = Parceiro::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $id
                ])
                ->update([
                    'excluido' => Carbon::now()
                ]);

            if ($exclusao == true) {
                return redirect(route('Manager.Contato.index'))->with('message', ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.']);
            } else {
                return redirect(route('Manager.Contato.index'))->with('message', ['type' => 'error', 'msg' => 'Não foi possível excluir o registro.']);
            }
        }
    }
}