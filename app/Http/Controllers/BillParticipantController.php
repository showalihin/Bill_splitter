<?php

namespace App\Http\Controllers;

use App\Models\BillSession;
use App\Models\BillParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillParticipantController extends Controller
{
    /**
     * Store a new participant in the bill session.
     */
    public function store(Request $request, BillSession $bill)
    {
        if ($bill->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        $bill->participants()->create([
            'name' => $validated['name'],
            'amount_paid' => $validated['amount_paid'] ?? 0,
        ]);

        return redirect()->back()->with('status', 'Participant added.');
    }

    /**
     * Update participant's amount paid.
     */
    public function update(Request $request, BillSession $bill, BillParticipant $participant)
    {
        if ($bill->user_id !== Auth::id() || $participant->bill_session_id !== $bill->id) abort(403);

        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $participant->update([
            'amount_paid' => $validated['amount_paid']
        ]);

        return redirect()->back()->with('status', 'Payment updated.');
    }

    /**
     * Remove a participant.
     */
    public function destroy(BillSession $bill, BillParticipant $participant)
    {
        if ($bill->user_id !== Auth::id() || $participant->bill_session_id !== $bill->id) abort(403);

        $participant->delete();

        return redirect()->back()->with('status', 'Participant removed.');
    }
}
