<?php

// app/Http/Controllers/MySubscriptionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Inertia\Inertia;

class MySubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $subscriptions = Subscription::where('user_id', $user->id)->get();

        return Inertia::render('MySubscriptionDetails', [
            'subscriptions' => $subscriptions
        ]);
    }
}

