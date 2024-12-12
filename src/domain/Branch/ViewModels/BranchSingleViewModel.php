<?php

namespace Domain\Branch\ViewModels;

use Domain\Branch\Models\Branch;
use Spatie\ViewModels\ViewModel;

class BranchSingleViewModel extends ViewModel
{
    public function __construct(
        public ?Branch $oldBranch = null
    )
    {
    }

    public function branch(): array
    {
        return [
            'id' => $this->oldBranch?->id,
            'slug' => $this->oldBranch->slug ?? '',
            'name' => $this->oldBranch->name ?? '',
            'description' => $this->oldBranch->description ?? '',
        ];
    }
}
