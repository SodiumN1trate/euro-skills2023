<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request) {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt($validated)) {
            return redirect(route('workspaces'));
        }
        $validator = ['error' => 'Incorrect credentials'];
        return view('login')->withErrors($validator);
    }
}
