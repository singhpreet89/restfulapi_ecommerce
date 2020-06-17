<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $modelName = Str::lower(class_basename($exception->getModel()));

            return response([
                "message" => "Model not found.",
                "errors" => [
                    $modelName => [
                        "No {$modelName} found with the provided id."
                    ]
                ]
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response([
                "message" => "Invalid Url.",
                "errors" => [
                    "url" => [
                        "{$request->fullUrl()} is invalid."
                    ]
                ],
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response([
                "message" => "Method not allowed.",
                "errors" => [
                    "url" => [
                        "The {$request->method()} method is not supported for {$request->fullUrl()}"
                    ]
                ],
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof AuthorizationException) {
            return response([
                "message" => "Not authorized.",
                "errors" => [
                    'user' => [
                        "{$exception->getMessage()}"
                    ]
                ],
            ], Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof HttpException) {
            return response([
                "message" => "{$exception->getMessage()}",
                "errors" => [
                    'reason' => [
                        "{$exception->getMessage()}"
                    ]
                ],
            ], Response::HTTP_FORBIDDEN);
        }

        // TODO: Handle other types of SQL exceptions here by checking other type of errorInfo
        if ($exception instanceof QueryException) {
            $errorCode =  $exception->errorInfo[1];

            if ($errorCode === 1451) {
                return response([
                    "message" => "Resource conflict.",
                    "errors" => [
                        'resource' => [
                            "This resource can't be removed due to a conflict with another resource."
                        ]
                    ],
                ], Response::HTTP_CONFLICT);
            }

            if ($errorCode === 1062) {
                return response([
                    "message" => "Resource conflict.",
                    "errors" => [
                        'resource' => [
                            "This resource can't be added because it already exists."
                        ]
                    ],
                ], Response::HTTP_CONFLICT);
            }
        }

        /**
         * ! Overriding the default Implementation of ValidationException rom \Vendor\Laravel\framework\src\illuminate\Foundation\Exception\Handler.php
         * ? To make sure that the JSON responses could be returned when VALIDATION FAILS for query_parameters in the GET requests.
         * ? Because, GET requests usually do not send the Accept: application/json HEADER along with the request which if bein checked by Default to return the JSON Response
         */
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        /**
         * If the exception is not one of the exceptions listed above
         * i.e. Database not connected 
         * ! IF -- The application is in DEBUG mode
         *      Display the Detailed ERROR
         * ! ELSE -- In all other cases, send the Internal Server Error
         */
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return response([
            "message" => "Internal server error.",
            "errors" => [
                'server' => [
                    "Please try again."
                ]
            ],
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * ! OVERRIDDEN FUNCTIONS
     */

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        // return $request->expectsJson()
        //             ? $this->invalidJson($request, $e)
        //             : $this->invalid($request, $e);
        
        return $this->isFrontend($request) ? $this->invalid($request, $e) : $this->invalidJson($request, $e);
    }


    /**
     * ! UTILITY FUNCTIONS
     */
    // Checking if the request is an HTML  request, using collections, find the middleware corresponding to the route and check if it contains 'web'
    private function isFrontend($request) {
        Log::info('i am here');
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
