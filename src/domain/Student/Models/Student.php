<?php

namespace Domain\Student\Models;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\Student\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\Database\Model;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'dob',
        'mobile_number',
        'email',
        'address',
        'resume',
        'pan_number',
        'aadhaar_number',
        'qualification',
        'gender',
        'marital_status',
        'image',
        'student_id',
        'branch_id',
        'modified_by',
        'added_by',
    ];
    protected $table = "students";


    protected static function newFactory(): StudentFactory
    {
        return new StudentFactory();
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

    public function fileColumnNames(): array
    {
        return [];
    }
}
