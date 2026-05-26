<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'name',
        'category',
        'description',
        'price',
        'unit',
        'is_available',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'is_available' => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The restaurant this item belongs to. */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Only items currently available for ordering. */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /** Filter by category. */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // -------------------------------------------------------------------------
    // Formatted price helper
    // -------------------------------------------------------------------------

    /** Returns price formatted as BDT, e.g. "৳ 250.00" */
    public function formattedPrice(): string
    {
        return '৳ ' . number_format((float) $this->price, 2);
    }
}
