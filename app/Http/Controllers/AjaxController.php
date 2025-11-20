<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AjaxController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $user = User::create([
            'name' => $request->name
        ]);

        return response()->json($user);
    }
}
