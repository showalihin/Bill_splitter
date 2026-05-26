<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'name',
        'status',
        'vat_percentage',
        'service_charge_percentage',
        'service_charge_amount',
        'discount_amount',
        'grand_total',
        'custom_menu'
    ];

    protected $casts = [
        'custom_menu' => 'array',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($billSession) {
            if (empty($billSession->share_token)) {
                $billSession->share_token = \Illuminate\Support\Str::random(10);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function participants()
    {
        return $this->hasMany(BillParticipant::class);
    }

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    // --- Calculation Logic ---

    /**
     * Get the total subtotal of all items in this bill session.
     */
    public function getTotalSubtotalAttribute()
    {
        return $this->items->sum('total_price');
    }

    /**
     * Get total VAT amount based on VAT percentage of the subtotal.
     */
    public function getTotalVatAttribute()
    {
        return ($this->total_subtotal * ($this->vat_percentage / 100));
    }

    /**
     * Get total service charge (flat amount or percentage).
     */
    public function getTotalServiceChargeAttribute()
    {
        if ($this->service_charge_amount > 0) {
            return $this->service_charge_amount;
        }
        return ($this->total_subtotal * ($this->service_charge_percentage / 100));
    }

    /**
     * Get the final Grand Total of the bill.
     */
    public function getCalculatedGrandTotalAttribute()
    {
        return $this->total_subtotal + $this->total_vat + $this->total_service_charge - $this->discount_amount;
    }
}
