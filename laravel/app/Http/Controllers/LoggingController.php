<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoggingController extends Controller
{
    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials) && auth()->user()->email === env('ADMIN_EMAIL')) {

            $request->session()->regenerate();

            return redirect()->intended('logging/telescope');
        }

        return view("logging.auth")->withErrors([
            'email' => 'Неверный email или пароль.',
        ]);
    }

    public function login(Request $request)
    {
        return view("logging.auth");
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
