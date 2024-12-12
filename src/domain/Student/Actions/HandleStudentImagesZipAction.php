<?php

namespace Domain\Student\Actions;

use Domain\Student\Data\StudentBulkUploadData;
use Domain\Student\Models\Student;
use Exception;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Support\Helper\Helper;
use ZipArchive;

class HandleStudentImagesZipAction
{
    use Helper;

    /**
     * @throws Exception
     */
    public function execute(
        StudentBulkUploadData $studentBulkUploadData,
    ): void {

        $zip = new ZipArchive();
        $tempPath = storage_path('app/temp'); // Adjust the path as needed

        if ($zip->open($studentBulkUploadData->file->getPathname()) === TRUE) {
            $zip->extractTo($tempPath);
            $zip->close();

            $files = scandir($tempPath);

            foreach ($files as $fileName) {
                if ($fileName !== '.' && $fileName !== '..') {
                    // Assuming the file name corresponds to the student ID
                    $studentId = pathinfo($fileName, PATHINFO_FILENAME);  // Extract file name without extension

                    // Find the student by ID or name (assuming `findStudent` is your method)
                    $student = Student::where('student_id', $studentId)->first();

                    if ($student) {
                        $filePath = $tempPath . '/' . $fileName;

                        // Create an UploadedFile instance from the file path
                        $uploadedFile = new UploadedFile($filePath, $fileName, null, null, true);

                        // Call action to store the image for the student
                        (new StoreStudentImagesAction())->execute($uploadedFile, $student);
                    }
                }
            }

            // Clean up extracted files after processing
            array_map('unlink', glob("$tempPath/*"));
            rmdir($tempPath);
        } else {
            throw new RuntimeException('Unable to open the ZIP file');
        }
    }
}
