<?php

namespace Domain\User\ViewModels;

use App\Models\User;
use Spatie\ViewModels\ViewModel;

class UserCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?User $oldUser = null
    )
    {
    }

    public function employee(): array
    {
        return [
            'id' => $this->oldUser?->id,
            'name' => $this->oldUser->name ?? '',
            'phone' => $this->oldUser->phone ?? '',
            'email' => $this->oldUser->email ?? '',
            'via' => $this->oldUser->login_via ?? 'phone',
            'role' => $this->oldUser ? [
                'value' => $this->oldUser->roles()->first()->id,
                'label' => $this->oldUser->roles()->first()->display_name,
                'slug' => $this->oldUser->roles()->first()->name,
            ] : [],
            'imagePreview' => $this->oldUser ? imageCheck('user-details/thumbnail/' . $this->oldUser->avatar) : null,
        ];
    }
}
