<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, Request $request) {
            // Обрабатываем только API запросы
            if ($request->is('api/*') || $request->wantsJson()) {
                return $this->handleApiException($e);
            }
        });
    }

    /**
     * Обработка исключений для API
     */
    private function handleApiException(Throwable $e): JsonResponse
    {
        $statusCode = $this->getStatusCode($e);
        $response = [
            'success' => false,
            'error' => $this->getErrorMessage($e, $statusCode),
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Определение HTTP статус-кода для исключения
     */
    private function getStatusCode(Throwable $e): int
    {
        return match (true) {
            $e instanceof ValidationException => 422,
            $e instanceof AuthenticationException => 401,
            $e instanceof ModelNotFoundException => 404,
            $e instanceof NotFoundHttpException => 404,
            $e instanceof MethodNotAllowedHttpException => 405,
            $e instanceof QueryException && $this->isUniqueConstraintViolation($e) => 409,
            $e instanceof QueryException => 500,
            method_exists($e, 'getStatusCode') => $e->getStatusCode(),
            default => 500,
        };
    }

    /**
     * Получение понятного сообщения об ошибке
     */
    private function getErrorMessage(Throwable $e, int $statusCode): string
    {
        return match (true) {
            $e instanceof ValidationException => 'Validation failed',
            $e instanceof AuthenticationException => 'Unauthenticated',
            $e instanceof ModelNotFoundException => 'Resource not found',
            $e instanceof NotFoundHttpException => 'Endpoint not found',
            $e instanceof MethodNotAllowedHttpException => 'Method not allowed',
            $e instanceof QueryException && $this->isUniqueConstraintViolation($e) => 'Duplicate entry',
            $statusCode === 500 => 'Internal server error',
            default => $e->getMessage(),
        };
    }

    /**
     * Проверка на нарушение уникального ограничения
     */
    private function isUniqueConstraintViolation(QueryException $e): bool
    {
        $errorCode = $e->getCode();
        $errorMessage = strtolower($e->getMessage());

        // Коды ошибок для разных СУБД
        $uniqueErrorCodes = [
            '23505', // PostgreSQL unique violation
            '23000', // MySQL general error (может быть unique)
            1062,    // MySQL duplicate entry
        ];

        // Проверка по коду ошибки
        if (in_array($errorCode, $uniqueErrorCodes, true)) {
            return true;
        }

        // Проверка по тексту ошибки
        $uniqueKeywords = [
            'unique', 
            'duplicate',
        ];

        foreach ($uniqueKeywords as $keyword) {
            if (str_contains($errorMessage, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
