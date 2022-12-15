<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        $data = array(
            "data" => null,
            "error_message" => "Exit from Space",
            "status" => 500
        );

        return response()->json($data, 500);
    }
}
