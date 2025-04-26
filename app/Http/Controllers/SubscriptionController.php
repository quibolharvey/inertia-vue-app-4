<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'subscription_type' => 'required|in:daily,monthly,yearly',
            'has_membership' => 'required|boolean',
            'amount' => 'required|integer',
        ]);
        $expiryDate = match ($request->subscription_type) {
            'daily' => Carbon::now()->addDay(),
            'monthly' => Carbon::now()->addMonth(),
            'yearly' => Carbon::now()->addYear(),
            default => null,
        };

        Subscription::create([
            ...$validated,
            'user_id' => Auth::id(),
            'expiry_date' => $expiryDate,
        ]);

        return redirect()->route('my-subscription-details');
    }

    public function mySubscriptions()
    {
        $subscriptions = Subscription::where('user_id', Auth::id())->get();

        return Inertia::render('MySubscriptionDetails', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function allSubscriptions()
    {
        $subscriptions = Subscription::with('user') // assuming there's a relation
        ->latest()
        ->paginate(5); // ✅ only 6 per page

    return Inertia::render('SubscriptionDetails', [
        'subscriptions' => $subscriptions,
    ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,paid,expired']);

        $subscription = Subscription::findOrFail($id);
        $subscription->update(['status' => $request->status]);

        return back();
    }
    public function getGymMembers(Request $request)
{
    $searchTerm = $request->input('search', '');

    $members = Subscription::where('has_membership', true)
                            ->where(function($query) use ($searchTerm) {
                                $query->where('full_name', 'like', '%' . $searchTerm . '%')
                                      ->orWhere('email', 'like', '%' . $searchTerm . '%');
                            })
                            ->paginate(5) // Add this to paginate results (5 per page)
                            ->withQueryString(); // Keep the search query when navigating pages

    return Inertia::render('GymMembers', [
        'members' => $members,
        'searchTerm' => $searchTerm,
    ]);
}



}

