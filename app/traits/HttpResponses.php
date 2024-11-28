<?php

namespace App\Traits;

trait HttpResponses
{
    public function successResponse($data, $message= null, $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message?? 'Request successful',
            'data' => $data,
        ], $status);
    }
    public function errorResponse($data, $message= null, $status)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message?? 'Something went wrong!',
            'data' => $data,
        ], $status);
    }
}