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
            ->pluck('endereco');

        Mail::send('emails.contact', $data, function ($message) use ($data, $destinatarios) {
            $message->from('naoresponder@aguiainox.ind.br', 'Águia Inox')
                    ->to('atendimento@aguiainox.com')
                    ->to($destinatarios)
                    ->bcc(['rafael@8poroito.com.br', 'aguiainox@aguiainox.ind.br'])
                    ->subject('Um novo contato foi enviado através do site!');
        });
    }
}
