<?php

namespace App\Exceptions;

use App\Models\ErrorLog;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Authentication failed. Please provide a valid token.',
                'code' => 498
            ], 498);
        }

        return redirect()->guest(route('login'));
    }

    public function report(Throwable $exception)
    {
        try {
            ErrorLog::create([
                'error_message' => $exception->getMessage(),
                'error_code'    => $exception->getCode(),
                'error_file'    => $exception->getFile(),
                'error_line'    => $exception->getLine(),
                'error_trace'   => $exception->getTraceAsString(),
                'url'           => Request::fullUrl(),
                'method'        => Request::method(),
                'request_data'  => json_encode(Request::all()),
                'user_id'       => Auth::check() ? Auth::id() : null,
            ]);
        } catch (\Exception $e) {
            \Log::error('ErrorLog save failed: ' . $e->getMessage());
        }

        parent::report($exception);
    }

    // For View Custome Server Error page errors.500
    // public function render($request, Throwable $exception)
    // {
    //     // Handle database-related errors
    //     if ($exception instanceof QueryException) {
    //         return response()->view('errors.500', [], 500);
    //     }

    //     // Handle general server errors
    //     if ($exception instanceof HttpException && $exception->getStatusCode() == 500) {
    //         return response()->view('errors.500', [], 500);
    //     }

    //     return parent::render($request, $exception);
    // }
}
