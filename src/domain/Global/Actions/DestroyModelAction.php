<?php

namespace Domain\Global\Actions;

use Support\Helper\Helper;
use Illuminate\Database\Eloquent\Model;

class DestroyModelAction
{
    use Helper;

    public function execute(Model $model, array $relatedDeletions = []): void
    {
        foreach ($relatedDeletions as $relation => $deletionCallback) {
            if (method_exists($model, $relation)) {
                $deletionCallback($model->$relation());
            }
        }

        $model->delete();

        foreach (collect($model->fileColumnNames()) as $columnName) {
            foreach (folderTypes() as $folder) {
                $this->deleteFileIfExists($model->getTable().'/'. $folder . '/' . $model[$columnName]);
                if(isset($model->slug)) {
                    $this->deleteFileIfExists($model->getTable().'/'. $model->slug .'/' . $folder . '/' . $model[$columnName]);
                }
            }
        }
    }
}
