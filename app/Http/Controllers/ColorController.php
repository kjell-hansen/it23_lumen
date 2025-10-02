<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ColorController extends Controller {
    function show() {
        return View::make('farger');
    }

    function post(Request $request ) {
        $bakgrund=$request->request->get('backColor');
        $text=$request->request->get('textColor');

        return View::make('farger',['backcolor'=>$bakgrund,'textcolor'=>$text]);
    }
}
