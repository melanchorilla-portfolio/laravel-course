<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Review;
use App\Helper\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'note' => 'string'
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

        $isExistReview = Review::where('course_id', '=', $courseId)
            ->where('user_id', '=', $userId)
            ->exists();

        if ($isExistReview) {
            return ApiHelper::sendResponse(status_code: 409, status: "error", message: "User already review this course");
        }

        try {
            $reviewCreated = Review::create($data);

            return ApiHelper::sendResponse(201, message: "Data created succesfully",  data: $reviewCreated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function update(Request $request, Review $review)
    {
        $rules = [
            'rating' => 'integer|min:1|max:5',
            'note' => 'string'
        ];

        $data = $request->except('user_id', 'course_id');
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(status_code: 400, status: "error", message: $validator->messages());
        }

        $review = Review::find($review->id);
        if (!$review) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Review not found");
        }

        try {
            $reviewUpdated = $review->update($data);

            return ApiHelper::sendResponse(201, message: "Data updated succesfully",  data: $reviewUpdated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function destroy(Review $review)
    {
        $review = Review::find($review->id);
        if (!$review) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Review not found");
        }

        try {
            $reviewDeleted = $review->delete();

            return ApiHelper::sendResponse(200, message: "Data deleted succesfully", data: $reviewDeleted);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }
}
