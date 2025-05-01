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

    public function allSubscriptions(Request $request)
{
    $search = $request->get('search');
    
    $subscriptions = Subscription::with('user') // assuming there's a relation
        ->when($search, function ($query) use ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('full_name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            });
        })
        ->orderBy('created_at', 'asc')
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
        $status = $request->input('status', ''); // New: status filter
    
        $members = Subscription::where('has_membership', true)
            ->when($status, fn($q) => $q->where('status', $status)) // Apply status filter
            ->where(function ($query) use ($searchTerm) {
                $query->where('full_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%');
            })
            ->withTrashed() // Include soft-deleted entries
            ->paginate(5)
            ->withQueryString();
    
        return Inertia::render('GymMembers', [
            'members' => $members,
            'searchTerm' => $searchTerm,
            'statusFilter' => $status, // Pass to frontend
        ]);
    }
    

public function destroy($id)
{
    $subscription = Subscription::findOrFail($id);
    $subscription->delete();

    return redirect()->back()->with('message', 'Subscription moved to trash.');
}

public function trashedSubscriptions()
{
    $subscriptions = Subscription::onlyTrashed()->with('user')->paginate(10);

    return Inertia::render('Trash', [
        'subscriptions' => $subscriptions
    ]);
}

public function trashed()
{
    $trashedSubscriptions = Subscription::onlyTrashed()->with('user')->get();

    return Inertia::render('Trash', [
        'subscriptions' => $trashedSubscriptions,
    ]);
}

public function forceDelete($id)
{
    try {
        // Find the subscription, even if it's soft deleted
        $subscription = Subscription::withTrashed()->findOrFail($id);
        
        // Permanently delete the subscription
        $subscription->forceDelete();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription permanently deleted.');
    } catch (\Exception $e) {
        // Handle errors, log them, and return a response
        \Log::error('Error in forceDelete: ' . $e->getMessage());
        return back()->with('error', 'There was an error deleting the subscription.');
    }
}
public function restore($id)
{
    try {
        // Find the soft-deleted subscription
        $subscription = Subscription::withTrashed()->findOrFail($id);

        // Restore the subscription
        $subscription->restore();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription restored successfully.');
    } catch (\Exception $e) {
        \Log::error('Error restoring subscription: ' . $e->getMessage());
        return back()->with('error', 'There was an error restoring the subscription.');
    }
}
}

