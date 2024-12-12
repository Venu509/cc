<?php

namespace Domain\Workshop\Models;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\Workshop\Factories\WorkshopFactory;
use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Support\Database\Model;

class Workshop extends Model
{

    protected $fillable = [
        'name',
        'branch_id',
        'semester',
        'description',
        'date',
        'modified_by',
        'added_by',
    ];
    protected $table = "workshops";

    protected static function newFactory(): WorkshopFactory
    {
        return new WorkshopFactory();
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

    public function workshopName(): BelongsTo
    {
        return $this->belongsTo(WorkshopName::class, 'name');
    }

    public function fileColumnNames(): array
    {
        return [];
    }
}
