<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Selo;
use App\Models\Diferencial;

use Carbon\Carbon;

class InstitucionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $selos = Selo::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'selosIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($selo) {
                return [
                    'id' => $selo->id,
                    'visivel' => $selo->visivel,
                    'imagem' => rafator('content/stamps/poster/' . $selo->imagem),
                    'nome' => $selo->selosIdiomas->isNotEmpty() ? $selo->selosIdiomas[0]->nome : null,
                ];
            });
            
        $diferenciais = Diferencial::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'diferenciaisIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($diferencial) {
                return [
                    'id' => $diferencial->id,
                    'visivel' => $diferencial->visivel,
                    'nome' => $diferencial->diferenciaisIdiomas->isNotEmpty() ? $diferencial->diferenciaisIdiomas[0]->nome : null
                ];
            });

        return Inertia::render('Manager/Institucional/index', [
            'selos' => $selos,
            'diferenciais' => $diferenciais
        ]);
    }
};