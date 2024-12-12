<?php

namespace Domain\Branch\Models;

use App\Models\User;
use Domain\Branch\Factories\BranchFactory;
use Domain\Student\Models\Student;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Support\Database\Model;

class Branch extends Model
{
    use HasSlug;
    use SoftDeletes;

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'modified_by',
        'added_by',
    ];

    protected static function newFactory(): BranchFactory
    {
        return new BranchFactory();
    }

    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by')->withTrashed();
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by')->withTrashed();
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class ,'branch_id');
    }

    public function fileColumnNames(): array
    {
        return [];
    }
}
