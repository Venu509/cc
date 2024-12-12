<?php

namespace Domain\Project\Models;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\Project\Factories\ProjectFactory;
use Domain\ProjectName\Models\ProjectName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Support\Database\Model;

class Project extends Model
{

    protected $fillable = [
        'name',
        'type',
        'branch_id',
        'semester',
        'description',
        'modified_by',
        'added_by',
    ];
    protected $table = "projects";

    protected static function newFactory(): ProjectFactory
    {
        return new ProjectFactory();
    }

    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by')->withTrashed();
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by')->withTrashed();
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function projectName(): BelongsTo
    {
        return $this->belongsTo(ProjectName::class, 'name');
    }

    public function fileColumnNames(): array
    {
        return [];
    }
}
