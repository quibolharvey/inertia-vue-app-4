<?php

// app/Http/Controllers/RegisteredAccountController.php

namespace App\Http\Controllers;


use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class RegisteredAccountController extends Controller
{
    public function index(Request $request)
{
    $users = User::query()
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->paginate(5) // Adjust the number per page
        ->withQueryString();

    return Inertia::render('RegisteredAccount', [
        'users' => $users,
        'search' => $request->search,
    ]);
}

}
