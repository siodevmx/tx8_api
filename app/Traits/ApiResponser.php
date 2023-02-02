<?php

namespace App\Traits;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
    /**
     * @param $data
     * @param null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data, $message = null, $code_name = '', int $code = 200)
    {
        return response()->json([
            'data' => $data,
            'status' => 'success',
            'code_name' => $code_name,
            'message' => $message,
            'statusCode' => $code
        ], $code);
    }


    /**
     * @param null $message
     * @return array
     */
    protected function returnSuccessCollection($message = null, $code)
    {
        return [
            'status' => 'success',
            'message' => $message,
            'statusCode' => $code,

        ];
    }


    /**
     * @param null $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = null, $code)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null,
            'statusCode' => $code
        ], $code);
    }

    /**
     * @param null $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorsResponse($message = null, $code)
    {
        return response()->json([
            'status' => 'error',
            'errors' => $message,
            'data' => null,
            'statusCode' => $code
        ], $code);
    }


    /**
     *  Get the token array structure.
     * @param $token
     * @param null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ],
            'statusCode' => $code
        ], $code);
    }
}
