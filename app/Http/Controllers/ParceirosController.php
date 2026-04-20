<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Http\Requests\PostPartnerRequest;
use App\Services\PartnerService;

class ParceirosController extends Controller
{
    protected $partnerService;

    public function __construct(PartnerService $partnerService)
    {
        parent::__construct();
        $this->partnerService = $partnerService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function enviar(PostPartnerRequest $request) {
        if($request->post()){
            $data = $request->validated();
        
            $contato = $this->partnerService->create($data);

            return back()->with('message', [
                'type' => 'success',
                'msg' => 'Contato enviado com sucesso!',
            ]);
        }

        return Inertia::location(route('Home.index'));
    }
};