<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegisteredAccountController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'asc')
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('RegisteredAccount', [
            'users' => $users,
            'search' => $request->search,
        ]);
    }
}
