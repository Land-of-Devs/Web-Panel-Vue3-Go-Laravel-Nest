<?php
 
namespace App\Traits;
 
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
 
trait ApiResponseTrait {

	public static function apiServerError($message = "Internal Server Error")
	{
		return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	public static function apiResponseError($errors, $message = "Invaild Data", $code = JsonResponse::HTTP_BAD_REQUEST)
	{
		return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
        ], $code);
	}

	public static function apiResponseSuccess($data, $message = "Success", $code = JsonResponse::HTTP_OK)
	{
		return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
	}
}