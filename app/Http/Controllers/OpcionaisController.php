<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\OpcionalCategoria;
use App\Models\Opcional;

use Carbon\Carbon;

class OpcionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');
            
        $opcionaisCategorias = OpcionalCategoria::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'opcionaisCategoriasIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'opcionais' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with('opcionaisIdiomas', function ($query) use ($idioma) {
                        $query->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    })
                    ->orderBy('ordem', 'ASC');
                },
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($categoria) {
                return [
                    'id' => $categoria->id,
                    'nome' => $categoria->opcionaisCategoriasIdiomas->isNotEmpty() ? $categoria->opcionaisCategoriasIdiomas[0]->nome : null,
                    'slug' => $categoria->slug,
                    'opcionais' => $categoria->opcionais->map(function ($opcional) {
                        return [
                            'id' => $opcional->id,
                            'titulo' => $opcional->opcionaisIdiomas->isNotEmpty() ? $opcional->opcionaisIdiomas->first()->titulo : null,
                            'descricao' => $opcional->opcionaisIdiomas->isNotEmpty() ? $opcional->opcionaisIdiomas->first()->descricao : null,
                            'slug' => $opcional->slug,
                        ];
                    }),
                ];
            });
            
        return Inertia::render('Opcionais', [
            'opcionaisCategorias' => $opcionaisCategorias
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function opcional($categoria = null, $slug = null) {
        if(!$slug || !$categoria) {
            return Inertia::location(route('Home.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $opcional = Opcional::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'slug' => $slug
            ])
            ->whereHas('categoria', function ($q) use ($categoria) {
                $q->where([
                    'excluido' => NULL,
                    'visivel' => true,
                    'slug' => $categoria
                ]);
            })
            ->with([
                'opcionaisIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'opcionaisModelos' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with([
                        'opcionaisModelosIdiomas' => function ($qi) use ($idioma) {
                            $qi->whereHas('idiomas', function ($ri) use ($idioma) {
                                $ri->where('codigo', $idioma)
                                   ->orWhere('padrao', true);
                            })->orderBy('idioma_id', 'DESC');
                        }
                    ])
                    ->orderBy('ordem', 'ASC');
                }
            ])
            ->first();

        if(!$opcional) {
            return Inertia::location(route('Opcionais.index'));
        }

        $opcionalData = [
            'id' => $opcional->id,
            'slug' => $opcional->slug,
            'titulo' => $opcional->opcionaisIdiomas->first()?->titulo,
            'categoria' => $opcional->categoria->opcionaisCategoriasIdiomas->first()?->nome,
            'categoria_slug' => $opcional->categoria->slug,
            'descricao' => $opcional->opcionaisIdiomas->first()?->descricao,
            'video' => $opcional->video ? getEmbedUrl($opcional->video) : null,
            'modelos' => $opcional->opcionaisModelos->map(function ($modelo) {
                return [
                    'id' => $modelo->id,
                    'nome' => $modelo->opcionaisModelosIdiomas->first()?->nome,
                    'descricao' => $modelo->opcionaisModelosIdiomas->first()?->descricao,
                    'imagem' => rafator('content/optionals/models/' . $modelo->imagem),
                    'slug' => $modelo->slug,
                ];
            })
        ];

        return Inertia::render('Opcional', [
            'opcional' => $opcionalData
        ]);
    }
};