<?php

namespace App\Providers;

use App\Repayment;
use App\Observers\RepaymentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addResponseMacros();

        Repayment::observe(RepaymentObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Add Response macros.
     *
     * @return void
     */
    protected function addResponseMacros()
    {
        Response::macro('api', function (array $data = [], $status = 200, array $headers = [], $options = 0, $callback = null) {
            $message = $status >= 200 && $status < 400 ? 'Success' : 'Error';

            $data = [
                'meta' => [
                    'status' => $status,
                    'message' => array_get($data, 'meta_message', $message),
                ],
                'data' => array_except($data, 'meta_message'),
            ];

            $response = Response::json($data, $status, $headers, $options);

            return $callback ? $response->withCallback($callback) : $response;
        });
    }
}
