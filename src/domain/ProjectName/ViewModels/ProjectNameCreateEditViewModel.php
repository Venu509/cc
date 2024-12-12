<?php

namespace Domain\ProjectName\ViewModels;

use Domain\Branch\Models\Branch;
use Domain\ProjectName\Models\ProjectName;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;

class ProjectNameCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?ProjectName $oldProjectName = null
    )
    {
    }

    public function projectName(): array
    {
        return [
            'id' => $this->oldProjectName?->id,
            'name' => $this->oldProjectName?->name,
        ];
    }
}
