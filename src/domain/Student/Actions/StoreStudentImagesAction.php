<?php

namespace Domain\Student\Actions;

use Domain\Student\Models\Student;
use Illuminate\Http\UploadedFile;
use Support\Helper\Helper;

class StoreStudentImagesAction
{
    use Helper;

    public function execute(
        UploadedFile $file,
        Student $student = new Student()
    ): void {

        $student->forceFill([
            'image' => $this->saveFile(
                $student,
                $file,
                'image',
                'students/',
            ),
        ]);


        $student->save();

        $student->refresh();
    }
}
