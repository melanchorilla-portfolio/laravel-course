<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\MyCourse;
use App\Helper\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
    public function index(Request $request)
    {
        $myCourses = MyCourse::query()->with(['course', 'user']);
        $userId = $request->query('user_id');

        $myCourses->when($userId, function ($query) use ($userId) {
            return $query->where('user_id', '=', $userId);
        });

        return ApiHelper::sendResponse(message: "Get All My Courses", data: $myCourses->paginate(10));
    }

    public function store(Request $request)
    {
        $rules = [
            'course_id' => 'required|integer',
            'user_id' => 'required|integer'
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

        $userId = $request->input('user_id');
        $user = User::find($userId);
        if (!$user) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "User not found");
        }

        $isExistMyCourse = MyCourse::where('course_id', '=', $courseId)
            ->where('user_id', '=', $userId)
            ->exists();

        if ($isExistMyCourse) {
            return ApiHelper::sendResponse(status_code: 409, status: "error", message: "User already take this course");
        }

        if ($course->type === 'premium') {
            if ($course->price === 0) {
                return ApiHelper::sendResponse(status_code: 405, status: "error", message: "Price can\'t be 0");
            }
        } else {
            try {
                $myCourseCreated = MyCourse::create($data);

                return ApiHelper::sendResponse(201, message: "Data added succesfully", data: $myCourseCreated);
            } catch (Exception $e) {
                return ApiHelper::sendResponse(500, "error", $e->getMessage());
            }
        }
    }

    public function createPremiumAccess(Request $request)
    {

    }
}
