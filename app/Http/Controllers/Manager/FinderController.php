<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;

class FinderController extends Controller
{
    public function enviar(Request $request) {
        if($request->ajax()){
            try {
                $imagefile = $request->file('img');
                $filename = $imagefile->getClientOriginalName();

                $image = $imagefile->move(public_path('content/files/'), $filename);

                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Imagem carregada com sucesso!', 'url' => asset('content/files/' . $filename)]);
            
            } catch (\Exception $e) {
                \Log::error('Erro no upload de imagem: ' . $e->getMessage());


                return back()->with('message', ['type' => 'error', 'msg' => 'Erro interno do servidor. Tente novamente']);
            }
        }
    }
}