<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostContactRequest extends FormRequest
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
            'departamento_id' => 'required|exists:departamentos,id',
            'assunto' => 'required',
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
            'nome.required' => __('contact.nome.required'),
            'email.required' => __('contact.email.required'),
            'email.email' => __('contact.email.email'),
            'departamento_id.required' => __('contact.departamento_id.required'),
            'departamento_id.exists' => __('contact.departamento_id.exists'),
            'assunto.required'  => __('contact.assunto.required'),
            'mensagem.required'  => __('contact.mensagem.required'),
            'politica.required' => __('contact.politica.required'),
            'politica.accepted' => __('contact.politica.accepted'),
        ];
    }
}
