<?php

namespace Domain\WorkshopName\Models;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\WorkshopName\Factories\WorkshopNameFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Support\Database\Model;

class WorkshopName extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'modified_by',
        'added_by',
    ];
    protected $table = "workshops_names";

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }

    protected static function newFactory(): WorkshopNameFactory
    {
        return new WorkshopNameFactory();
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
