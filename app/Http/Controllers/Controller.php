<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success_response($message = 'Success', $data = [])
    {
        return response()->json(['message' => $message, 'data' => $data]);
    }

    public function error_response($message = 'Server Error', $data = [])
    {
        return response()->json(['message' => $message, 'data' => $data], 422);
    }

    public function render_response($view, $url = '', $method = 'post')
    {
        return response()->json(['message' => 'Success', 'view' => $view, 'url' => $url, 'method' => $method]);
    }

    public function render_custom_response($view, $data = [])
    {
        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'data' => $data
        ]);
    }
}
