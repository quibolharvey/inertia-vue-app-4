<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Membership;

class MembershipController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'isLifetime' => 'required|boolean',
            'amount' => 'required|integer',
        ]);

        Membership::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'is_lifetime' => $request->isLifetime,
                'amount' => $request->amount,
            ]
        );

        return redirect()->back()->with('success', 'Membership applied successfully.');
    }
}
