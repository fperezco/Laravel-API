<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use ReflectionClass;

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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception) {
            $response = [
                'success' => false,
                'message' => 'Error',
                'data' => $this->exceptionToMessage($exception) . '(' . $exception->getMessage() . ')'
            ];

            // Default response of 400
            $code = 400;

            // If this exception is an instance of HttpException
            if ($this->isHttpException($exception)) {
                // Grab the HTTP status code from the Exception
                $code = $exception->getStatusCode();
            }

            return response()->json($response, $code);
        }

        return parent::render($request, $exception);
    }

    private function exceptionToMessage($exception)
    {
        $exceptionClass = get_class($exception);
        $reflect = new ReflectionClass($exception);
        $exceptionClass = $reflect->getShortName();
        //dd($exceptionClass);
        switch ($exceptionClass) {
            case 'NotFoundHttpException':
                $message = 'Invalid API Route';
            break;
            case 'UnauthorizedHttpException':
                $message = 'Unauthorized Token required';
            break;
            case 'MethodNotAllowedHttpException':
                $message = 'Method not allowed';
            break;
            default:
                $message = get_class($exception);
        }

        return $message;
    }
}
