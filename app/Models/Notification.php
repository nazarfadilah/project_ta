<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'related_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Helper to send a notification to a specific user.
     *
     * @param int $userId
     * @param string $type
     * @param string $message
     * @param string|null $relatedId
     * @return self
     */
    public static function send(int $userId, string $type, string $message, ?string $relatedId = null): self
    {
        return self::create([
            'user_id'    => $userId,
            'type'       => $type,
            'message'    => $message,
            'related_id' => $relatedId,
        ]);
    }
}
