<?php

namespace Domain\API\Authentication\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsernameValidation implements Rule
{
    protected ?string $via;
    protected ?int $id;
    protected ?string $column;

    public function __construct(?string $via = null, ?int $id = null, ?string $column = 'username')
    {
        $this->via = $via;
        $this->id = $id;
        $this->column = $column;
    }

    public function passes($attribute, $value): bool
    {
        if(isset($this->via)) {
            if ($this->via === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            if ($this->via === 'phone' && !preg_match('/^\d{10,12}$/', $value)) {
                return false;
            }
        }
        $query = DB::table('users')->where($this->column, $value);
        if ($this->id !== null) {
            $query->whereNot('id', $this->id);
        }
        return !$query->exists();
    }

    public function message(): string
    {
        if(isset($this->via)) {
            if ($this->via === 'email') {
                return 'The :attribute must be a valid email address and unique.';
            }

            if ($this->via === 'phone') {
                return 'The :attribute must be a valid phone number and unique.';
            }
        }

        return 'The :attribute must be unique.';
    }
}
