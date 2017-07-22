<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($object, $code, $message, $location = null) {
            $response = [
                'meta' => [
                    'code' => $code,
                    'message' => $message
                ],
                'response' => $object
            ];
            if ($location) {
                $response['meta']['location'] = $location;
            }
            return Response::json($response);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
