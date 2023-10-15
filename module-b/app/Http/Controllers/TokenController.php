<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function revoke($id) {
        $token = Token::find($id);
        $token->revocation_date = now();
        $token->save();

        return redirect(route('workspaces.show', ['id' => $token->workspace->id]));
    }

    public function create(Request $request, $id) {

        $validated = $request->validate([
            'name' => 'required',
            'token' => 'required',
        ]);
        $validated['workspace_id'] = $id;

        $token = Token::create($validated);

        return redirect(route('workspaces.show', ['id' => $token->workspace->id]));
    }
}
