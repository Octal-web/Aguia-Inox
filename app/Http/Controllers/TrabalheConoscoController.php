<?php

namespace App\Http\Controllers;


use Inertia\Inertia;


class TrabalheConoscoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return Inertia::render('TrabalheConosco');
    }
};