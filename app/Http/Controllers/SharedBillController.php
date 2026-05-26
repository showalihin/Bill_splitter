<?php

namespace App\Http\Controllers;

use App\Models\BillSession;
use Illuminate\Http\Request;

class SharedBillController extends Controller
{
    /**
     * Display a read-only view of a bill session using its public share token.
     */
    public function show($token)
    {
        $bill = BillSession::with(['restaurant', 'items.participants', 'participants', 'user'])
            ->where('share_token', $token)
            ->firstOrFail();

        return view('bills.shared', compact('bill'));
    }
}
