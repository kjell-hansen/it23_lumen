<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Interfaces\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserController extends Controller {

    public function __construct(private UserRepo $repo) {

    }
    function show() {
        return View::make('user');
    }

    function add(Request $request) {
        $user=User::factory()->make($request->request->all());
        $this->repo->add($user);
    }
}
