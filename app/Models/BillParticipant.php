<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_session_id',
        'name',
        'amount_paid'
    ];

    public function session()
    {
        return $this->belongsTo(BillSession::class, 'bill_session_id');
    }

    public function items()
    {
        return $this->belongsToMany(BillItem::class, 'bill_item_participant');
    }

    // --- Calculation Logic ---

    /**
     * Participant's share of the raw subtotal from their assigned items.
     */
    public function getSubtotalAttribute()
    {
        $session = $this->session;
        if (!$session) return 0;
        
        // Use the eagerly loaded session items instead of querying the database again
        return collect($session->items)->filter(function($item) {
            return collect($item->participants)->contains('id', $this->id);
        })->sum('split_cost');
    }

    /**
     * Participant's proportional share of the VAT.
     */
    public function getVatAmountAttribute()
    {
        $session = $this->session;
        if (!$session) return 0;

        return ($this->subtotal * ($session->vat_percentage / 100));
    }

    /**
     * Participant's proportional share of the Service Charge.
     */
    public function getServiceChargeAmountAttribute()
    {
        $session = $this->session;
        if (!$session || $session->total_subtotal == 0) return 0;

        // Either they take a direct percentage...
        if ($session->service_charge_percentage > 0) {
            return ($this->subtotal * ($session->service_charge_percentage / 100));
        }

        // Or they take a proportional chunk of the flat service charge amount
        if ($session->service_charge_amount > 0) {
            $proportion = $this->subtotal / $session->total_subtotal;
            return $session->service_charge_amount * $proportion;
        }

        return 0;
    }

    /**
     * Participant's proportional share of the discount.
     */
    public function getDiscountAmountAttribute()
    {
        $session = $this->session;
        if (!$session || $session->total_subtotal == 0 || $session->discount_amount <= 0) return 0;

        $proportion = $this->subtotal / $session->total_subtotal;
        return $session->discount_amount * $proportion;
    }

    /**
     * Total amount this participant owes.
     */
    public function getTotalOwedAttribute()
    {
        return $this->subtotal 
             + $this->vat_amount 
             + $this->service_charge_amount 
             - $this->discount_amount;
    }

    /**
     * Net balance (how much they owe vs how much they paid).
     * Positive = owes money. Negative = gets money back.
     */
    public function getNetBalanceAttribute()
    {
        return $this->total_owed - $this->amount_paid;
    }

    /**
     * How much the participant should get back (if they overpaid).
     */
    public function getReturnAmountAttribute()
    {
        return max(0, $this->amount_paid - $this->total_owed);
    }

    /**
     * How much the participant still needs to pay.
     */
    public function getRemainingOwedAttribute()
    {
        return max(0, $this->total_owed - $this->amount_paid);
    }
}
