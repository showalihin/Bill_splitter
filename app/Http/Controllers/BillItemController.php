<?php

namespace App\Http\Controllers;

use App\Models\BillSession;
use App\Models\BillItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillItemController extends Controller
{
    /**
     * Store a new item manually.
     */
    public function store(Request $request, BillSession $bill)
    {
        if ($bill->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:bill_participants,id'
        ]);

        $item = $bill->items()->create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity']
        ]);

        if (!empty($validated['participants'])) {
            $item->participants()->sync($validated['participants']);
        }

        return redirect()->back()->with('status', 'Item added.');
    }

    /**
     * Store multiple items from the restaurant's menu.
     */
    public function storeFromMenu(Request $request, BillSession $bill)
    {
        if ($bill->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'menu_items' => 'required|array',
            'menu_items.*.id' => 'required|exists:menu_items,id',
            'menu_items.*.quantity' => 'required|integer|min:0'
        ]);

        foreach ($validated['menu_items'] as $menuData) {
            if ($menuData['quantity'] > 0) {
                $menuItem = MenuItem::find($menuData['id']);
                if ($menuItem && $menuItem->restaurant_id === $bill->restaurant_id) {
                    $bill->items()->create([
                        'name' => $menuItem->name,
                        'price' => $menuItem->price,
                        'quantity' => $menuData['quantity']
                    ]);
                }
            }
        }

        return redirect()->back()->with('status', 'Menu items added.');
    }

    /**
     * Assign an item to participants (Sync).
     */
    public function assignParticipants(Request $request, BillSession $bill, BillItem $item)
    {
        if ($bill->user_id !== Auth::id() || $item->bill_session_id !== $bill->id) abort(403);

        $validated = $request->validate([
            'participants' => 'nullable|array',
        ]);

        // Sync the pivot table
        $item->participants()->sync($validated['participants'] ?? []);

        return redirect()->back()->with('status', 'Item assignment updated.');
    }

    /**
     * Remove an item.
     */
    public function destroy(BillSession $bill, BillItem $item)
    {
        if ($bill->user_id !== Auth::id() || $item->bill_session_id !== $bill->id) abort(403);

        $item->delete();

        return redirect()->back()->with('status', 'Item removed.');
    }
}
