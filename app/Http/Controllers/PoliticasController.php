<?php

namespace App\Http\Controllers;


use Inertia\Inertia;

class PoliticasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacidade() {

        return Inertia::render('PoliticaPrivacidade');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cookies() {

        return Inertia::render('PoliticaCookies');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function canalDenuncia() {

        return Inertia::render('PoliticaCanalDenuncia');
    }
};