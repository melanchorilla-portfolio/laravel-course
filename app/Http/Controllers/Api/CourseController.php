<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Chapter;
use App\Helper\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query();
        $q = $request->query('q');
        $status = $request->query('status');

        $courses->when($q, function ($query) use ($q) {
            return $query->whereRaw("name LIKE '%" . strtolower($q) . "%'");
        });

        $courses->when($status, function ($query) use ($status) {
            return $query->where('status', '=', $status);
        });

        return ApiHelper::sendResponse(message: "Get All Courses", data: $courses->paginate(10));
    }

    public function show(Course $course)
    {
        $course = Course::with(['chapters.lessons', 'mentor', 'images', 'reviews'])->find($course->id);

        if (!$course) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Course not found");
        }

        $totalStudent = MyCourse::where('course_id', '=', $course->id)->count();
        $totalVideos = Chapter::where('course_id', '=', $course->id)->withCount('lessons')->get()->toArray();
        $finalTotalVideos = array_sum(array_column($totalVideos, 'lessons_count'));

        $course['total_videos'] = $finalTotalVideos;
        $course['total_student'] = $totalStudent;

        return ApiHelper::sendResponse(data: $course, message: "Get Course By ID");
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'mentor_id' => 'required|integer',
            'description' => 'string'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(status_code: 400, status: "error", message: $validator->messages());
        }

        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);
        if (!$mentor) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Mentor not found");
        }

        try {
            $courseCreated = Course::create($data);

            return ApiHelper::sendResponse(201, message: "Data added succesfully", data: $courseCreated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function update(Request $request, Course $course)
    {
        $course = Course::find($course->id);
        if (!$course) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Course not found");
        }

        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'mentor_id' => 'required|integer',
            'description' => 'string'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(status_code: 400, status: "error", message: $validator->messages());
        }

        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);
        if (!$mentor) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Mentor not found");
        }

        try {
            $courseUpdated = $course->update($data);

            return ApiHelper::sendResponse(201, message: "Data updated succesfully",  data: $courseUpdated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function destroy(Course $course)
    {
        $course = Course::find($course->id);

        if (!$course) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Course not found");
        }

        try {
            $courseDeleted = $course->delete();

            return ApiHelper::sendResponse(200, message: "Data deleted succesfully", data: $courseDeleted);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

}
