<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\ImageCourse;
use App\Helper\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'image' => 'required|url',
            'course_id' => 'required|integer'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(status_code: 400, status: "error", message: $validator->messages());
        }

        $courseId = $request->input('course_id');
        $course = Course::find($courseId);
        if (!$course) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Course not found");
        }

        try {
            $imageCourseCreated = ImageCourse::create($data);

            return ApiHelper::sendResponse(201, message: "Data added succesfully", data: $imageCourseCreated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function destroy(ImageCourse $imageCourse)
    {
        $imageCourse = ImageCourse::find($imageCourse->id);

        if (!$imageCourse) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Image Course not found");
        }

        try {
            $imageCourseDeleted = $imageCourse->delete();

            return ApiHelper::sendResponse(200, message: "Data deleted succesfully", data: $imageCourseDeleted);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }
}
