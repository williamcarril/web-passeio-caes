<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function defaultJsonResponse($status = true, $messages = [], $data = null) {
        if (is_string($messages)) {
            $messages = [$messages];
        }

        return response()->json([
                    "status" => $status,
                    "messages" => $messages,
                    "data" => $data
        ]);
    }

}
