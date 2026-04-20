<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostDepartamentRequest extends FormRequest
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
            'nome'  => 'required',
            'emails' => 'required|array',
            'emails.*' => 'required|email',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $errors = $validator->errors();
            $messages = $errors->messages();
            
            foreach ($messages as $field => $fieldMessages) {
                if (preg_match('/^emails\.\d+/', $field)) {
                    $errors->forget($field);
                    
                    foreach ($fieldMessages as $message) {
                        $errors->add('emails', $message);
                    }
                }
            }
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'nome.required'  => 'Por favor, informe o nome.',
            'emails.required'  => 'Por favor, informe ao menos um e-mail.',
            'emails.*.required'  => 'Por favor, informe ao menos um e-mail.',
            'emails.*.email'  => 'Por favor, informe e-mails válidos.',
        ];
    }
}