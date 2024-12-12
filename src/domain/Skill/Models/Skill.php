<?php

namespace Domain\Skill\Models;

use App\Models\User;
use Domain\Skill\Factories\SkillFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Support\Database\Model;

class Skill extends Model
{
    use HasSlug;
    use SoftDeletes;

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
        'is_active',
        'modified_by',
        'added_by',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected static function newFactory(): SkillFactory
    {
        return new SkillFactory();
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
