<?php

namespace App\Services;

use App\Models\Parceiro;
use App\Models\DepartamentoEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PartnerService
{
    public function create(array $data): Parceiro
    {
        return DB::transaction(function () use ($data) {
            $partnerData = collect($data)
                ->except(['policy'])
                ->toArray();

            $partner = Parceiro::create($partnerData);

            $this->sendEmail($data);

            return $partner;
        });
    }

    protected function sendEmail(array $data): void
    {
        Mail::send('emails.partner', $data, function ($message) use ($data) {
            $message->from('naoresponder@aguiainox.ind.br', 'Águia Inox')
                    ->to('aguiainox@aguiainox.ind.br')
                    ->bcc('rafael@8poroito.com.br')
                    ->subject('Um novo parceiro foi enviado através do site!');
        });
    }
}
