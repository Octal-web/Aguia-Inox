<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostGeneralDataRequest extends FormRequest
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
            'endereco'  => 'required',
            'cep'  => 'required|formato_cep',
            'cidade'  => 'required',
            'telefone' => 'required',
            'whatsapp_mkt' => 'required',
            'whatsapp_rh' => 'required',
            'email' => 'required|email',
            'instagram' => 'nullable|url',
            'facebook' => 'nullable|url',
            'linkedin' => 'nullable|url',
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
            'endereco.required' => 'Por favor, informe o endereço.',
            'cep.required' => 'Por favor, informe o CEP.',
            'cep.formato_cep' => 'Por favor, informe um CEP válido.',
            'cidade.required' => 'Por favor, informe a cidade.',
            'telefone.required' => 'Por favor, informe o telefone.',
            'whatsapp_mkt.required' => 'Por favor, informe o número do whatsapp marketing.',
            'whatsapp_rh.required' => 'Por favor, informe o número do whatsapp RH.',
            'email.required' => 'Por favor, informe o email.',
            'email.email' => 'Por favor, informe um e-mail válido.',
            'instagram.url' => 'Por favor, informe um link de instagram válido.',
            'facebook.url' => 'Por favor, informe um link de facebook válido.',
            'linkedin.url' => 'Por favor, informe um link de linkedin válido.',
        ];
    }
}