<?php

namespace App\Http\Controllers\Api;

use App\Models\Mentor;
use App\Helper\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{
    public function index()
    {
        $mentors = Mentor::all();

        return ApiHelper::sendResponse(data: $mentors, message: "Get All Mentors");
    }

    public function show(Mentor $mentor)
    {
        if (!$mentor) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Mentor not found");
        }


        return ApiHelper::sendResponse(data: $mentor, message: "Get Mentor By ID");
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'profile' => 'required|url',
            'profession' => 'required|string',
            'email' => 'required|email'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(status_code: 400, status: "error", message: $validator->messages());
        }

        try {
            $mentorCreated = Mentor::create($data);

            return ApiHelper::sendResponse(201, message: "Data added succesfully", data: $mentorCreated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function update(Request $request, Mentor $mentor)
    {
        $mentor = Mentor::find($mentor->id);

        if (!$mentor) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Mentor not found");
        }

        $rules = [
            'name' => 'required|string',
            'profile' => 'required|url',
            'profession' => 'required|string',
            'email' => 'required|email'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return ApiHelper::sendResponse(status_code: 400, status: "error", message: $validator->messages());
        }

        try {
            $mentorUpdated = $mentor->update($data);

            return ApiHelper::sendResponse(201, message: "Data updated succesfully",  data: $mentorUpdated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }

    public function destroy(Mentor $mentor)
    {
        if (!$mentor) {
            return ApiHelper::sendResponse(status_code: 404, status: "error", message: "Mentor not found");
        }

        try {
            $mentorDeleted = $mentor->delete();

            return ApiHelper::sendResponse(200, message: "Data deleted succesfully", data: $mentorDeleted);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }
}
