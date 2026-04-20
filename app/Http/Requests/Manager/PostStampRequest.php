<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostStampRequest extends FormRequest
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
            'img_stamp' => inertia()->getShared('action') === 'novo'
                ? 'required|image|mimes:png,jpg|max:2048'
                : 'nullable|image|mimes:png,jpg|max:2048',
            'vid' => inertia()->getShared('action') === 'novo'
                ? 'required|mimetypes:video/mp4,video/x-msvideo,video/webm|max:51200'
                : 'nullable|mimetypes:video/mp4,video/x-msvideo,video/webm|max:51200',
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

            'img_stamp.required' => 'Por favor, selecione um selo.',
            'img_stamp.image' => 'Por favor, selecione um selo válido.',
            'img_stamp.mimes' => 'Os formatos de selo válidos são: JPG e PNG.',
            'img_stamp.max' => 'Por favor, envie um arquivo menor que 2MB.',

            'vid.required' => 'Por favor, selecione um vídeo.',
            'vid.mimetypes' => 'Os formatos de vídeo válidos são: MP4, AVI e WEBM.',
            'vid.max' => 'Por favor, envie um arquivo menor que 50MB.',
        ];
    }
}