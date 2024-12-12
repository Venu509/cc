<?php

namespace Domain\Notification\Models;

use App\Models\User;
use Domain\Notification\Factories\NotificationFactory;
use Support\Database\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'domain',
        'title',
        'type',
        'message',
        'data',
        'read_at',
        'is_active',
        'is_read',
        'modified_by',
        'added_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_read' => 'boolean',
        'read_at' => 'date:Y-m-d H:i a',
        'created_at' => 'date:Y-m-d H:i a',
        'updated_at' => 'date:Y-m-d H:i a',
    ];

    protected static function newFactory(): NotificationFactory
    {
        return new NotificationFactory();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by')->withTrashed();
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by')->withTrashed();
    }

    public function fileColumnNames(): array
    {
        return [];
    }
}
