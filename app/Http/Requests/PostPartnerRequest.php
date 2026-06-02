<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostPartnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'nome' => 'required',
            'email' => 'required|email',
            'cnpj' => 'required|cnpj',
            'telefone' => 'required',
            'cargo' => 'nullable',
            // 'assunto' => 'required',
            'mensagem' => 'required',
            'politica' => 'required|accepted',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'nome.required' => 'Por favor, insira seu nome.',
            'email.required' => 'Por favor, insira seu e-mail.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'cnpj.required' => 'Por favor, insira seu CNPJ.',
            'cnpj.cnpj' => 'Por favor, insira um CNPJ válido.',
            'telefone.required' => 'Por favor, insira seu telefone.',
            // 'assunto.required'  => 'Por favor, informe o assunto da sua mensagem.',
            'mensagem.required'  => 'Por favor, informe a sua mensagem.',
            'politica.required' => 'Para continuar, você deve concordar com os termos.',
            'politica.accepted' => 'Para continuar, você deve concordar com os termos.',
        ];
    }
}