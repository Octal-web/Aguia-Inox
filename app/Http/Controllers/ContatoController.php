<?php

namespace App\Http\Controllers;

use App\Models\Departamento;

use Inertia\Inertia;

use App\Http\Requests\PostContactRequest;
use App\Services\ContactService;

class ContatoController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        parent::__construct();
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $departamentos = Departamento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'departamentosIdiomas' => function ($q) use ($idioma) {
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
            ->map(function($departamento) {
                return [
                    'value' => $departamento->id,
                    'label' => $departamento->departamentosIdiomas->first()?->nome,
                ];
            });

        return Inertia::render('Contato', [
            'departamentos' => $departamentos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function enviar(PostContactRequest $request) {
        if($request->post()){
            $data = $request->validated();
        
            $contato = $this->contactService->create($data);

            return back()->with('message', [
                'type' => 'success',
                'msg' => 'Contato enviado com sucesso!',
            ]);
        }

        return Inertia::location(route('Contato.index'));
    }
};