<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostSegmentRequest extends FormRequest
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
            'descricao'  => 'required',
            'img' => inertia()->getShared('action') === 'novo'
                ? 'required|image|mimes:png,jpg|max:2048'
                : 'nullable|image|mimes:png,jpg|max:2048',
            'img_banner' => inertia()->getShared('action') === 'novo'
                ? 'required|image|mimes:png,jpg|max:2048'
                : 'nullable|image|mimes:png,jpg|max:2048',
            'destaque' => 'boolean',
            'titulo_pagina'  => 'required|max:150',
            'descricao_pagina'  => 'required|max:220',
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
            'nome.required'  => 'Por favor, informe o nome.',
            'descricao.required'  => 'Por favor, informe a descrição.',
            
            'img.required' => 'Por favor, selecione uma imagem.',
            'img.image' => 'Por favor, selecione uma imagem válida.',
            'img.mimes' => 'Os formatos de imagem válidos são: JPG e PNG.',
            'img.max' => 'Por favor, envie um arquivo menor que 2MB.',
            
            'img_banner.required' => 'Por favor, selecione um banner.',
            'img_banner.image' => 'Por favor, selecione um banner válido.',
            'img_banner.mimes' => 'Os formatos de banner válidos são: JPG e PNG.',
            'img_banner.max' => 'Por favor, envie um arquivo menor que 2MB.',
            
            'destaque.boolean' => 'O campo destaque deve ser verdadeiro ou falso.',

            'titulo_pagina.required'  => 'Por favor, informe o título da página.',
            'titulo_pagina.max'  => 'O título da página não pode exceder 150 caracteres.',

            'descricao_pagina.required'  => 'Por favor, informe a descrição da página.',
            'descricao_pagina.max'  => 'A descrição da página não pode exceder 220 caracteres.',
        ];
    }
}