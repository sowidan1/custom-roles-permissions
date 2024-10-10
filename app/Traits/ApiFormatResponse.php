<?php

namespace App\Traits;

use App\Enums\ApiResponseTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Log;

trait ApiFormatResponse
{
    public function respondSuccess($data = [], string $message = "Success", int $status = 200, array $extraHeaders = [], $pagination = null): JsonResponse
    {
        Log::debug("SUCCESS API Response");

        $response = [
            'status' => ApiResponseTypes::SUCCESS,
            'code' => $status,
            "message" => $message,
            "data" => $data,
        ];

        if (!is_null($pagination)) {
            $response['pagination'] = $pagination;
        }

        $extraHeaders = $this->getDebugExtraHeaders();
        return response()->json($response, $status, $extraHeaders);
    }

    public function respondSuccessWithPagination(Paginator $paginate, $data, string $message = "Success", int $status = 200, $extraHeaders = []): JsonResponse
    {
        Log::debug("SUCCESS API Response: with paginator");

        $data = array_merge($data, [
            'paginator' => [
                'total_count'  => $paginate->total(),
                'total_pages' => ceil($paginate->total() / $paginate->perPage()),
                'current_page' => $paginate->currentPage(),
                'limit' => $paginate->perPage(),
            ]
        ]);

        return $this->respondSuccess($data, $message, $status, $extraHeaders);
    }

    public function respondFail($errors, string $message = "Request/Response Failed", int $status = 400, array $extraHeaders = []): JsonResponse
    {
        Log::debug("FAIL API Response");

        $response = [
            'status' => ApiResponseTypes::FAILED,
            'code' => $status,
            "message" => $message,
        ];

        if (is_array($errors)) {
            $response['errors'] = $errors;
        } else {
            $response['error'] = $errors;
        }

        $extraHeaders = $this->getDebugExtraHeaders();
        return response()->json($response, $status, $extraHeaders);
    }


    public function respondError($errors, string $message = "Internal Server Error", int $status = 500, array $extraHeaders = []): JsonResponse
    {
        Log::debug("ERROR API Response");

        $response = [
            "status" => ApiResponseTypes::ERROR,
            "code" => $status,
            "message" => $message
        ];

        // checks if single error or multiple
        if (is_array($errors)) {
            $response['errors'] = $errors;
        } else {
            $response['error'] = $errors;
        }

        $extraHeaders = $this->getDebugExtraHeaders();
        return response()->json($response, $status, $extraHeaders);
    }

    public function respondOK($data = [], $message = 'Response Success - OK'): JsonResponse
    {
        $status = 200;
        return $this->respondSuccess($data, $message, $status);
    }

    public function respondCreated($data = [], $message = 'Response Created'): JsonResponse
    {
        $status = 201;
        return $this->respondSuccess($data, $message, $status);
    }

    public function respondAccepted($data = [], $message = 'Response Accepted'): JsonResponse
    {
        $status = 202;
        return $this->respondSuccess($data, $message, $status);
    }

    public function respondBadRequest($errors = [], $message = 'Bad Request'): JsonResponse
    {
        $status = 400;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondUnauthorized($errors = [], $message = 'Unauthorized'): JsonResponse
    {
        $status = 401;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondForbidden($errors = [], $message = 'Forbidden'): JsonResponse
    {
        $status = 403;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondNotFound($errors = [], $message = 'Not Found'): JsonResponse
    {
        $status = 404;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondNotAllowed($errors = [], $message = 'Not Found'): JsonResponse
    {
        $status = 405;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondNotAcceptable($errors = [], $message = 'Not Acceptable'): JsonResponse
    {
        $status = 406;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondConflict($errors = [], $message = 'Conflict'): JsonResponse
    {
        $status = 409;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondFailedValidation($errors = [], $message = 'Failed Validation'): JsonResponse
    {
        $status = 422;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondUnprocessable($errors = [], $message = 'Unprocessable Entity'): JsonResponse
    {
        $status = 422;
        return $this->respondFail($errors, $message, $status);
    }

    public function respondWithError(array $errors = [], string $message = 'Internal Server Error'): JsonResponse
    {
        $status = 500;
        return $this->respondError($errors, $message, $status);
    }

    public function respondUnknownError(array $errors = [], string $message = 'Internal Server Error'): JsonResponse
    {
        $status = 500;
        return $this->respondError($errors, $message, $status);
    }

    /**
     * Attach response headers for debug to get around CORS
     */
    private function getDebugExtraHeaders(): array
    {
        if (config('APP_DEBUG')) {
            return [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS, PUT, PATCH, DELETE',
                'Access-Control-Allow-Headers' => 'Origin,Content-Type,X-Requested-With,Accept,Authorization'
            ];
        }
        return [];
    }
}