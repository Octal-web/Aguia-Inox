<?php

namespace App\Http\Controllers;

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
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'selosIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($selo) {
                return [
                    'id' => $selo->id,
                    'selo' => rafator('content/stamps/thumbs/' . $selo->selo),
                    'video' => rafator('content/stamps/video/' . $selo->video),
                    'imagem' => rafator('content/stamps/poster/' . $selo->imagem),
                    'nome' => $selo->selosIdiomas->isNotEmpty() ? $selo->selosIdiomas[0]->nome : null,
                    'descricao' => $selo->selosIdiomas->isNotEmpty() ? $selo->selosIdiomas[0]->descricao : null,
                ];
            });
            
        $diferenciais = Diferencial::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'diferenciaisIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($diferencial) {
                return [
                    'id' => $diferencial->id,
                    'nome' => $diferencial->diferenciaisIdiomas->isNotEmpty() ? $diferencial->diferenciaisIdiomas[0]->nome : null,
                    'descricao' => $diferencial->diferenciaisIdiomas->isNotEmpty() ? $diferencial->diferenciaisIdiomas[0]->descricao : null,
                ];
            });

        return Inertia::render('Empresa', [
            'selos' => $selos,
            'diferenciais' => $diferenciais
        ]);
    }
};