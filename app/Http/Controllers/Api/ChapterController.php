<?php

namespace App\Http\Controllers\Api;

use App\Models\Chapter;
use App\Models\Course;
use App\Helper\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        $chapters = Chapter::query()->with(['course']);
        $courseId = $request->query('course_id');

        $chapters->when($courseId, function ($query) use ($courseId) {
            return $query->where('course_id', '=', $courseId);
        });

        return ApiHelper::sendResponse(data: $chapters->get(), message: "Get All Chapters");
    }

    public function show(Chapter $chapter)
    {
        $chapter = Chapter::find($chapter->id);

        if (!$chapter) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Chapter not found");
        }

        return ApiHelper::sendResponse(data: $chapter, message: "Get Chapter By ID");
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
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
            $chapterCreated = Chapter::create($data);

            return ApiHelper::sendResponse(201, message: "Data added succesfully", data: $chapterCreated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }


    public function update(Request $request, Chapter $chapter)
    {
        $chapter = Chapter::find($chapter->id);

        if (!$chapter) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Chapter not found");
        }

        $rules = [
            'name' => 'string',
            'course_id' => 'integer'
        ];

        $data = $request->all();
        $courseId = $request->input('course_id');

        if ($courseId) {
            $course = Course::find($courseId);
            if (!$course) {
                return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Course not found");
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(status_code: 400, status: "error", message: $validator->messages());
        }

        try {
            $chapterUpdated = $chapter->update($data);

            return ApiHelper::sendResponse(201, message: "Data updated succesfully",  data: $chapterUpdated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function destroy(Chapter $chapter)
    {
        $chapter = Chapter::find($chapter->id);
        if (!$chapter) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Chapter not found");
        }

        try {
            $chapterDeleted = $chapter->delete();

            return ApiHelper::sendResponse(200, message: "Data deleted succesfully", data: $chapterDeleted);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }
}
