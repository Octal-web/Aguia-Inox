<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostOptionalRequest extends FormRequest
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
            'titulo'  => 'required',
            'img_grafico' => 'nullable|image|mimes:png,jpg|max:2048',
            'opcional_categoria_id'  => 'required|integer',
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
            'titulo.required'  => 'Por favor, informe o título.',
            'img_grafico.image' => 'Por favor, selecione uma imagem válida.',
            'img_grafico.mimes' => 'Os formatos de imagem válidos são: JPG e PNG.',
            'img_grafico.max' => 'Por favor, envie um arquivo menor que 2MB.',
            'opcional_categoria_id.required'   => 'Por favor, informe a categoria.',
            'opcional_categoria_id.integer'   => 'Por favor, informe a categoria.',
        ];
    }
}
