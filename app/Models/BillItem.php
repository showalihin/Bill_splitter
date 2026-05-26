<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_session_id',
        'name',
        'price',
        'quantity'
    ];

    public function session()
    {
        return $this->belongsTo(BillSession::class, 'bill_session_id');
    }

    public function participants()
    {
        return $this->belongsToMany(BillParticipant::class, 'bill_item_participant');
    }

    /**
     * Total cost for this item (price * quantity).
     */
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Cost split per participant assigned to this item.
     */
    public function getSplitCostAttribute()
    {
        $count = $this->participants->count();
        if ($count === 0) return 0;
        return $this->total_price / $count;
    }
}
