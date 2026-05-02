<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class QRLoginToken extends Model
{
    protected $table = 'admin_qr_login_tokens';

    protected $fillable = [
        'token',
        'admin_user_id',
        'ip_address',
        'user_agent',
        'expires_at',
        'used_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Generate a unique token
     */
    public static function generateToken(): string
    {
        do {
            $token = Str::random(64);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Create a new QR login token
     */
    public static function createToken(int $adminUserId, string $ipAddress = null, string $userAgent = null): self
    {
        return self::create([
            'token' => self::generateToken(),
            'admin_user_id' => $adminUserId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'expires_at' => now()->addMinutes(5), // Token expires in 5 minutes
        ]);
    }

    /**
     * Check if token is valid
     */
    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }

    /**
     * Mark token as used
     */
    public function markAsUsed(): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }

    /**
     * Relationship with AdminUser
     */
    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class);
    }

    /**
     * Clean up expired tokens
     */
    public static function cleanupExpired(): void
    {
        self::where('expires_at', '<', now())
            ->orWhere(function ($query) {
                $query->where('is_used', true)
                      ->where('used_at', '<', now()->subDays(1));
            })
            ->delete();
    }
}
