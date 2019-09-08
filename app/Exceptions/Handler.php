<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->is('*')) {
            if ($exception instanceof ValidationException)
                return response()->json(['meta' => ['success' => false, 'message' => $exception->errors()]], $exception->status);

            if ($exception instanceof ModelNotFoundException)
                return response()->json(['meta' => ['success' => false, 'message' => 'A busca não gerou resultados.']], 404);

            if ($exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException)
                return response()->json(['meta' => ['success' => false, 'message' => 'Rota não encontrada.']], 404);

            if ($exception instanceof UnauthorizedException)
                return response()->json(['meta' => ['success' => false, 'message' => 'O usuário não tem permissões para realizar essa operação.']], 403);

        }

        return parent::render($request, $exception);
    }
}
