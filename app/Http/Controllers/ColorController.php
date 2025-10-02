<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ColorController extends Controller {
    function show(Request $request) {
        $bakgrund=$request->get('back');
        $text=$request->get('front');

        return View::make('farger',['backcolor'=>$bakgrund,'textcolor'=>$text]);
    }

    function post(Request $request ) {
        $bakgrund=$request->request->get('backColor');
        $text=$request->request->get('textColor');

        return View::make('farger',['backcolor'=>$bakgrund,'textcolor'=>$text]);
    }

    function withParams(Request $request) {
        $bakgrund=$request->route('back');
        $text=$request->route('front');

        return View::make('farger',['backcolor'=>$bakgrund,'textcolor'=>$text]);
    }

}

