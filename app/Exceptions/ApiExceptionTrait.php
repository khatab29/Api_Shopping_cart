<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ApiExceptionTrait
{
    public function apiException($request, $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'error' => 'Route Is Incorrect'
            ], 404);
        }

        if ($exception instanceof QueryException) {
            return response()->json([
            'error' => 'Not Found'
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
