<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostNewsletterRequest;
use Inertia\Inertia;

use App\Models\Newsletter;

use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function enviar(PostNewsletterRequest $request) {
        if($request->post()){
            $newsletter = new Newsletter;

            $newsletter->email = $request->email;

            $response = $newsletter->save();

            if ($response) {
                $data = [
                    'email' => $request->email
                ];

                Mail::send('emails.newsletter', $data, function ($message) use ($data) {
                    $message->from('naoresponder@aguiainox.ind.br', 'Águia Inox')
                            ->to('atendimento@aguiainox.com')
                            ->bcc(['rafael@8poroito.com.br', 'aguiainox@aguiainox.ind.br'])
                            ->subject('Uma nova assinatura de newsletter foi feita através do site!');
                });

                return back()->with('message', [
                    'type' => 'newsSuccess',
                    'msg' => 'Contato enviado com sucesso!',
                ]);
            }
        }

        return Inertia::location(route('Home.index'));
    }
};