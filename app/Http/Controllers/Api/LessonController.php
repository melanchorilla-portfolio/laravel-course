<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use App\Models\Chapter;
use App\Helper\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $lessons = Lesson::query()->with(['chapter']);
        $chapterId = $request->query('chapter_id');

        $lessons->when($chapterId, function ($query) use ($chapterId) {
            return $query->where('chapter_id', '=', $chapterId);
        });

        return ApiHelper::sendResponse(data: $lessons->get(), message: "Get All Lessons");
    }

    public function show(Lesson $lesson)
    {
        $lesson = Lesson::with(['chapter'])->find($lesson->id);

        if (!$lesson) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Lesson not found");
        }

        return ApiHelper::sendResponse(data: $lesson, message: "Get Lesson By ID");
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id' => 'required|integer'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(status_code: 400, status: "error", message: $validator->messages());
        }

        $chapterId = $request->input('chapter_id');
        $chapter = Chapter::find($chapterId);

        if (!$chapter) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Chapter not found");
        }

        try {
            $lessonCreated = Lesson::create($data);

            return ApiHelper::sendResponse(201, message: "Data added succesfully", data: $lessonCreated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function update(Request $request, Lesson $lesson)
    {
        $lesson = Lesson::find($lesson->id);
        if (!$lesson) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Lesson not found");
        }

        $rules = [
            'name' => 'string',
            'video' => 'string',
            'chapter_id' => 'integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return ApiHelper::sendResponse(status_code: 400, status: "error", message: $validator->messages());
        }

        $chapterId = $request->input('chapter_id');
        if ($chapterId) {
            $chapter = Chapter::find($chapterId);
            if (!$chapter) {
                return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Chapter not found");
            }
        }

        try {
            $lessonUpdated = $lesson->update($data);

            return ApiHelper::sendResponse(201, message: "Data updated succesfully",  data: $lessonUpdated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function destroy(Lesson $lesson)
    {
        $lesson = Lesson::find($lesson->id);

        if (!$lesson) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Lesson not found");
        }

        try {
            $lessonDeleted = $lesson->delete();

            return ApiHelper::sendResponse(201, message: "Data updated succesfully",  data: $lessonDeleted);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }
}
