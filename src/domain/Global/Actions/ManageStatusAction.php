<?php

namespace Domain\Global\Actions;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class ManageStatusAction
{
    public function execute(
        Model $model,
        Request $request,
        ?string $column = 'is_active',
        ?string $target = 'status',
    ): Model {
        $model->forceFill([
            $column => $request[$target],
            'modified_by' => auth()->user()->id,
        ])->save();

        $model->refresh();

        return $model;
    }
}
