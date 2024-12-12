<?php

namespace Domain\Banner\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\Database\Model;
use Domain\Banner\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'url',
        'is_active',
        'modified_by',
        'added_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'date:Y-m-d H:i a',
        'updated_at' => 'date:Y-m-d H:i a',
    ];

    protected static function newFactory(): BannerFactory
    {
        return new BannerFactory();
    }

    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function fileColumnNames(): array
    {
        return ['image'];
    }
}
