<?php

namespace Domain\ProjectName\Models;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\ProjectName\Factories\ProjectNameFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Support\Database\Model;

class ProjectName extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'modified_by',
        'added_by',
    ];
    protected $table = "projects_names";

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }

    protected static function newFactory(): ProjectNameFactory
    {
        return new ProjectNameFactory();
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
