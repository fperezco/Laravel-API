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
        //dd('en render', $exception);

        if ($exception) {
            $fullResponse = $this->getDetailResponseFromException($exception);
            $response = $fullResponse['response'];
            $code = $fullResponse['code'];
            return response()->json($response, $code);
        }

        return parent::render($request, $exception);
    }

    /**
     * Return the class of the exception
     *
     * @param [type] $exception
     * @return void
     */
    private function getExceptionClass($exception)
    {
        $exceptionClass = get_class($exception);
        $reflect = new ReflectionClass($exception);
        $exceptionClass = $reflect->getShortName();
        return $exceptionClass;
    }

    private function getDetailResponseFromException($exception)
    {
        $exceptionClass = $this->getExceptionClass($exception);

        $code = 401;
        $message = 'Exception';
        switch ($exceptionClass) {
            case 'HttpException':
                $message = 'Http Exception**';
                $code = $exception->getStatusCode();
            break;
            case 'NotFoundHttpException':
                $message = 'Invalid API Route**';
                $code = 404;
            break;
            case 'MethodNotAllowedHttpException':
                $message = 'Method not allowed**';
            break;
            case 'UnauthorizedHttpException':
                $message = 'Unauthorized Error**';
            break;
            case 'JWTException':
                $message = 'Token Error**';
            break;
            case 'TokenExpiredException':
                  $message = 'Expired Token**';
            break;
            case 'TokenInvalidException':
                $message = 'Invalid Token**';
            break;
            case 'TokenBlacklistedException':
                $message = 'Blacklisted Token**';
            break;
            case 'QueryException': //pk obtener el token tb implica una consulta a la BD
                $code = 500;
                if ($exception->getCode() == 2002) { //error accediendo a BD
                    $message = 'Internal server Error';
                } else {
                    $message = $exception->getPrevious()->errorInfo[2];
                }
            break;
            default:
                $message = get_class($exception);
        }

        if ($exception->getMessage()) {
            //$data = $message . '(' . $exception->getMessage() . ')';
            $data = $message;
        } else {
            $data = $message;
        }

        $fullResponse = [
            'code' => $code,
            'response' => [
                'success' => false,
                'message' => 'Error',
                'data' => $data
            ]
        ];

        return $fullResponse;
    }
}
