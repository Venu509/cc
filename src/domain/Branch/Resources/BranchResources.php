<?php

namespace Domain\Branch\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Helper\Helper;

class BranchResources extends JsonResource
{
    use Helper;

    public function toArray(Request $request): array
    {
        $mainData =  [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'studentsCount' => $this->students->count(),
            'description' => $this->description,
        ];

        if($this->requestTypeCheck()) {
            return array_merge($mainData, [
                'students' => $this->students ? collect($this->students)
                    ->take(3)
                    ->map(function ($student) {
                        return [
                            'id' => $student->id,
                            'studentId' => $student->student_id,
                            'name' => $student->first_name . ' ' . $student->last_name,
                            'profilePicture' => $student->image ? imageCheck('students/thumbnail/' . $student->image) : "https://ui-avatars.com/api/?name=". $student->name . "&color=7F9CF5&&background=" . $this->getRandomBackgroundColor(),
                        ];
                    }) : [],
            ]);
        }

        return $mainData;
    }

    protected function getRandomBackgroundColor() {
        $backgroundColors = ['87e8ff', 'f5a623', 'ff6b6b', '48c774', '9b59b6'];
        return $backgroundColors[array_rand($backgroundColors)];
    }
}
