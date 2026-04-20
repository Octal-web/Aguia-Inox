<?php

namespace App\Services;

use App\Models\Contato;
use App\Models\DepartamentoEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    public function create(array $data): Contato
    {
        return DB::transaction(function () use ($data) {
            $contactData = collect($data)
                ->except(['policy'])
                ->toArray();

            $contact = Contato::create($contactData);

            $this->sendEmail($data);

            return $contact;
        });
    }

    protected function sendEmail(array $data): void
    {
        $destinatarios = DepartamentoEmail::query()
            ->where([
                'excluido' => null
            ])
            ->whereHas('departamento', function ($q) use ($data) {
                $q->where([
                    'excluido' => null,
                    'visivel' => true,
                    'id' => $data['departamento_id']
                ]);
            })
            ->pluck('endereco')
            ->toArray();

        Mail::send('emails.contact', $data, function ($message) use ($data, $destinatarios) {
                $todosDestinatarios = array_merge(
                    ['aguiainox@aguiainox.ind.br', 'bianca.emer@aguiainox.ind.br'],
                    $destinatarios
                );

                $message->from('naoresponder@aguiainox.ind.br', 'Águia Inox')
                        ->to($todosDestinatarios)
                        ->bcc(['rafael@8poroito.com.br'])
                        ->subject('Um novo contato foi enviado através do site!');
            });
    }
}
