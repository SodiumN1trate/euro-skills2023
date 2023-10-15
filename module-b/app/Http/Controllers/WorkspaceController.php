<?php

namespace App\Http\Controllers;

use App\Models\Quota;
use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function index() {
        return view('workspaces')->with([
            'workspaces' => Workspace::where('user_id', auth()->user()->id)->get(),
        ]);
    }

    public function showView($id) {
        if(auth()->user() === null) {
            return view('login');
        }
        return view('workspaces_show')->with([
            'workspace' => Workspace::where('user_id', auth()->user()->id)->where('id', $id)->first(),
            'quotas' => Quota::all(),
        ]);
    }

    public function createView() {
        if(auth()->user() === null) {
            return view('login');
        }
        return view('workspaces_create');
    }

    public function create(Request $request) {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'description' => '',
        ]);
        $validated['user_id'] = auth()->user()->id;
        Workspace::create($validated);

        return redirect(route('workspaces'));
    }

    public function edit(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'description' => '',
            'quota_id' => '',
        ]);

        if($validated['quota_id'] === 'null') {
            $validated['quota_id'] = null;
        }

        $validated['user_id'] = auth()->user()->id;
        Workspace::find($id)->update($validated);

        return redirect(route('workspaces'));
    }


    public function bill(Request $request, $id) {
        if(auth()->user() === null) {
            return view('login');
        }
        $validated = $request->validate([
            'month' => '',
        ]);

        return view('bill')->with([
            'workspace' => Workspace::find($id),
            'month' => $validated['month'] ?? null,
        ]);
    }

}
