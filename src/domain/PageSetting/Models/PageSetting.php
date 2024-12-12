<?php

namespace Domain\PageSetting\Models;

use App\Models\User;
use Domain\PageSetting\Factories\PageSettingFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\Database\Model;

class PageSetting extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'type',
        'title',
        'description',
        'email',
        'mobile',
        'is_active',
        'modified_by',
        'added_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'date:Y-m-d H:i a',
        'updated_at' => 'date:Y-m-d H:i a',
        'deleted_at' => 'date:Y-m-d H:i a',
    ];

    protected static function newFactory(): PageSettingFactory
    {
        return new PageSettingFactory();
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
