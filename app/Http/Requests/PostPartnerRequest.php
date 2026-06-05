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
            'nome.required' => __('partner.nome.required'),
            'email.required' => __('partner.email.required'),
            'email.email' => __('partner.email.email'),
            'cnpj.required' => __('partner.cnpj.required'),
            'cnpj.cnpj' => __('partner.cnpj.cnpj'),
            'telefone.required' => __('partner.telefone.required'),
            // 'assunto.required'  => __('partner.assunto.required'),
            'mensagem.required'  => __('partner.mensagem.required'),
            'politica.required' => __('partner.politica.required'),
            'politica.accepted' => __('partner.politica.accepted'),
        ];
    }
}
