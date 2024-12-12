<?php

namespace Domain\API\Authentication\Rules;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;

class CheckOldPassword implements Rule
{
    public ?User $user = null;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    public function passes($attribute, $value): bool
    {
        $user = $this->user ?? auth()->user();

        if (! $user) {
            return false;
        }

        return Hash::check($value, $user->password);
    }

    public function message(): string
    {
        return 'The provided old password is incorrect.';
    }
}
