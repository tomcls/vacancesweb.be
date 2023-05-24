<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoController extends Controller
{
    //
    public function index(Request $request) {
        return response()
        ->view('logo', [
            'color' => $request['color']??'b3e6f4',
            'square' =>$request['square']??false
        ], 200)
        ->header('Content-Type', 'image/svg+xml');
    }
}
