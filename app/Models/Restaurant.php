<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'cuisine',
        'address',
        'phone',
        'image',
        'description',
        'scope',
        'status',
        'rejection_reason',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The user who owns/created this restaurant (null for admin-global ones). */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** All menu items belonging to this restaurant. */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    // -------------------------------------------------------------------------
    // Scopes — use these for clean querying in controllers
    // -------------------------------------------------------------------------

    /** Only restaurants visible to everyone. */
    public function scopeGlobal($query)
    {
        return $query->where('scope', 'global')->where('is_active', true);
    }

    /** Only restaurants owned by a specific user. */
    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /** Restaurants that have a pending global request. */
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending');
    }

    // -------------------------------------------------------------------------
    // Helper methods
    // -------------------------------------------------------------------------

    public function isGlobal(): bool
    {
        return $this->scope === 'global';
    }

    public function isPrivate(): bool
    {
        return $this->scope === 'private';
    }

    public function isPendingApproval(): bool
    {
        return $this->status === 'pending';
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}
