<?php

namespace App\Traits;


trait APIResponse
{

    static  $HTTP_OK = 200;
    static $HTTP_NOT_FOUND = 404;
    static $HTTP_FORBIDDEN = 403;
    static $HTTP_UNPROCESSABLE_ENTITY = 422;                                        // RFC4918
    static $HTTP_INTERNAL_SERVER_ERROR = 500;
    static $HTTP_UNAUTHORIZED = 500;


    /**
     * Returns a generic success (200) JSON response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($message = 'Success', $data = [])
    {

        return response()->json([
            'status'  => strval(self::$HTTP_OK),
            'message' => $message,
            'data'    => $data,
        ], self::$HTTP_OK);
    }


    /**
     * Returns a unprocessable entity (422) JSON response.
     *
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUnprocessable($errors)
    {
        return response()->json([
            'status' => strval(self::$HTTP_UNPROCESSABLE_ENTITY),
            'errors' => $errors,
        ], self::$HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Returns a server error (500) JSON response.
     *
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseServerError($errors = ['Server error.'])
    {
        return response()->json([
            'status' => strval(self::$HTTP_INTERNAL_SERVER_ERROR),
            'errors' => $errors
        ], self::$HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Returns a processing error (400) JSON response.
     *
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorProcessingRequest($errors = ['Error processing the request..'])
    {
        return response()->json([
            'status' => 400,
            'errors' => $errors
        ], 400);
    }


    /**
     * Returns a resource not found error (404) JSON response.
     *
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound($errors = ['An invalid resource was requested.'])
    {
        return response()->json([
            'status' => strval(self::$HTTP_NOT_FOUND),
            'errors' => $errors
        ], self::$HTTP_NOT_FOUND);
    }



    /**
     * Returns a same pickup dropoff error (422) JSON response.
     *
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function samePickupDropoff($errors = ['Pickup and Dropoff can’t be the same.'])
    {
        return response()->json([
            'status' => 422,
            'errors' => $errors
        ], 422);
    }
}
