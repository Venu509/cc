<?php

namespace Domain\Lead\Models;

use App\Models\User;
use Domain\Lead\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Support\Database\Model;

class Lead extends Model
{
    use SoftDeletes;
    use HasSlug;

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }

    protected $fillable = [
        'title',
        'slug',
        'type',
        'status',
        'description',
        'is_active',
        'user_id',
        'modified_by',
        'added_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'date:Y-m-d H:i a',
        'updated_at' => 'date:Y-m-d H:i a',
        'deleted_at' => 'date:Y-m-d H:i a',
    ];

    protected static function newFactory(): LeadFactory
    {
        return new LeadFactory();
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

}
