<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostDownloadRequest extends FormRequest
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
            'titulo' => 'required|string|max:120',
            'tipo_id' => 'required|integer',
            'arq' => inertia()->getShared('action') == 'novo' ? 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png' : 'nullable|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png'
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
            'tipo_id.required'  => 'Por favor, informe o tipo do arquivo.',
            'arq.required' => 'Por favor, selecione um arquivo.',
            'arq.image' => 'Por favor, selecione um arquivo válido.',
            'arq.mimes' => 'Os formatos de imagem válidos são: PDF, DWG, DXF, DOC, DOCX, XLS, XLSX, PPT e PPTX.',
            'arq.max' => 'Por favor, envie um arquivo menor que 50MB.',
        ];
    }
}