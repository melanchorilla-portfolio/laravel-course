<?php

namespace App\Helper;

class ApiHelper
{
  public static function sendResponse($status_code = 200, $status='success', $message = '', $data = [])
  {
    return response()->json([
      'status' => $status,
      'message' => $message,
      'data' => $data
    ], $status_code);
  }
}
