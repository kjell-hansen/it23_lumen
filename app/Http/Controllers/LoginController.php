<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class LoginController extends Controller {
    public function show() {
        return View::make('login');
    }

    public function login(Request $request, AuthenticationService $auth) {
        // Skapa login-objekt
        $login = Login::create($request->request->all());

        // Kolla inmatad data mot databasen
        $user = $auth->attemptLogin($login);

        // Returnera om inloggningen lyckades
        if ($user) {
            // returnera lyckad inloggning
            $request->session()->put('user_id', $user->id);
            $request->session()->save();
            return redirect("/{$user->namn}");
        }
        else {
            // Misslyckad inloggning
            return View::make('login', ['message' => 'Fel epost eller lösenord']);
        }
    }
    public function register(Request $request, AuthenticationService $auth) {
        // Validera input
        $validated = $request->validate([
            'namn' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string', // kräver password_confirmation
        ]);

        // Skapa användare i databasen
        $user = User::create([
            'namn' => $validated['namn'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Logga in användaren automatiskt efter registrering
        $request->session()->put('user_id', $user->id);
        $request->session()->save();

        // Skicka användaren till sin profilsida
        return redirect("/{$user->namn}");
    }
}
