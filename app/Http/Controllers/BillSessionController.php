<?php

namespace App\Http\Controllers;

use App\Models\BillSession;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillSessionController extends Controller
{
    /**
     * Display a listing of the user's bill sessions.
     */
    public function index()
    {
        $bills = Auth::user()->billSessions()->with(['restaurant', 'items'])->latest()->get();
        return view('bills.index', compact('bills'));
    }

    /**
     * Show the form for creating a new bill session.
     */
    public function create()
    {
        // Get restaurants the user can access (global + their own private ones)
        $restaurants = Restaurant::where('scope', 'global')
            ->orWhere('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        return view('bills.create', compact('restaurants'));
    }

    /**
     * Store a newly created bill session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'service_charge_percentage' => 'nullable|numeric|min:0|max:100',
            'service_charge_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $bill = Auth::user()->billSessions()->create([
            'name' => $validated['name'],
            'restaurant_id' => $validated['restaurant_id'],
            'status' => 'open',
            'vat_percentage' => $validated['vat_percentage'] ?? 0,
            'service_charge_percentage' => $validated['service_charge_percentage'] ?? 0,
            'service_charge_amount' => $validated['service_charge_amount'] ?? 0,
            'discount_amount' => $validated['discount_amount'] ?? 0,
        ]);

        return redirect()->route('bills.show', $bill)->with('status', 'Bill session started!');
    }

    /**
     * Display the bill session dashboard.
     */
    public function show(BillSession $bill)
    {
        // Ensure user owns this bill
        if ($bill->user_id !== Auth::id()) {
            abort(403);
        }

        // Eager load only the root level relationships. 
        // The models have been updated to calculate everything in-memory from these!
        $bill->load([
            'participants',
            'items.participants',
            'restaurant.menuItems'
        ]);

        return view('bills.show', compact('bill'));
    }

    /**
     * Update the bill session settings (taxes, discounts, status).
     */
    public function update(Request $request, BillSession $bill)
    {
        if ($bill->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'service_charge_percentage' => 'nullable|numeric|min:0|max:100',
            'service_charge_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:open,settled',
        ]);

        $bill->update([
            'name' => $validated['name'],
            'vat_percentage' => $validated['vat_percentage'] ?? 0,
            'service_charge_percentage' => $validated['service_charge_percentage'] ?? 0,
            'service_charge_amount' => $validated['service_charge_amount'] ?? 0,
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('status', 'Settings updated.');
    }

    /**
     * Add an item to the custom session menu.
     */
    public function addCustomMenuItem(Request $request, BillSession $bill)
    {
        if ($bill->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        $menu = $bill->custom_menu ?? [];
        $menu[] = [
            'id' => uniqid(),
            'name' => $validated['name'],
            'price' => (float) $validated['price']
        ];

        $bill->update(['custom_menu' => $menu]);

        return redirect()->back()->with('status', 'Item added to session menu!');
    }

    /**
     * Remove the bill session.
     */
    public function destroy(BillSession $bill)
    {
        if ($bill->user_id !== Auth::id()) {
            abort(403);
        }

        $bill->delete();

        return redirect()->route('bills.index')->with('status', 'Bill deleted.');
    }
}
