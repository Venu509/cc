<?php

namespace Domain\Attendance\Models;

use App\Models\User;
use Domain\Attendance\Factories\AttendanceFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\Database\Model;

class Attendance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'clock_in_at',
        'clock_out_at',
        'clock_in_ip',
        'clock_out_ip',
        'clock_in_coordinates',
        'clock_out_coordinates',
        'working_from',
        'is_completed',
        'user_id',
        'modified_by',
        'added_by',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'created_at' => 'date:Y-m-d H:i a',
        'updated_at' => 'date:Y-m-d H:i a',
        'deleted_at' => 'date:Y-m-d H:i a',
    ];

    protected static function newFactory(): AttendanceFactory
    {
        return new AttendanceFactory();
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
